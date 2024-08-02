<?php

namespace App\Http\Controllers\Api\V1\Agent;

use Exception;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TemporaryData;
use App\Constants\GlobalConst;
use App\Http\Helpers\Response;
use App\Models\Agent\AgentWallet;
use App\Models\AgentNotification;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\BasicSettings;
use App\Http\Controllers\Controller;
use App\Constants\PaymentGatewayConst;
use App\Models\Admin\AdminNotification;
use App\Models\Admin\TransactionSetting;
use App\Traits\ControlDynamicInputFields;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\PaymentGatewayCurrency;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Agent\MoneyOutNotification;

class MoneyOutController extends Controller
{
    use ControlDynamicInputFields;
    /**
     * Method for get money out data
     * @return response
     */
    public function index(){
        $payment_gateway        = PaymentGatewayCurrency::whereHas('gateway', function ($gateway) {
            $gateway->where('slug', PaymentGatewayConst::money_out_slug());
            $gateway->where('status', 1);
        })->get()->map(function($data){
            return [
                'name'          => $data->name,
                'alias'         => $data->alias,
            ];
        });
        $transaction_settings   = TransactionSetting::where('slug',GlobalConst::MONEY_OUT)->where('status',true)->first();
        $limits_data       = [
            'min_limit'         => get_amount($transaction_settings->min_limit),
            'max_limit'         => get_amount($transaction_settings->max_limit),
        ];
        

        return Response::success(['Money out data fetch successfully.'],[
            'base_currency'         => get_default_currency_code(),
            'payment_gateway'       => $payment_gateway,
            'limits_data'           => $limits_data,
        ],200);
    }
    /**
     * Method for submit money out information
     * @param Illuminate\Http\Request $request
     */
    public function submit(Request $request){
        $validator              = Validator::make($request->all(),[
            'amount'            => 'required|numeric',
            'payment_gateway'   => 'required'
        ]);
        if($validator->fails()) return Response::validation(['error' => $validator->errors()->all()]);
        $validated                  = $validator->validate();
        $amount                     = $validated['amount'];
        $agent_wallet               = AgentWallet::auth()->first();
        if($amount > $agent_wallet->balance) return Response::error(['Sorry! Insufficient Balance.'],[],400);
        $transaction_settings       = TransactionSetting::where('slug',GlobalConst::MONEY_OUT)->where('status',true)->first();
        if(!$transaction_settings){
            return Response::error(['Transaction settings not found'],[],400);
        }
        if($amount < $transaction_settings->min_limit || $amount > $transaction_settings->max_limit){
            return Response::error(['Please follow the transaction limit.'],[],400);
        }
        //
        $payment_gateway_currency   = PaymentGatewayCurrency::where('alias',$validated['payment_gateway'])->first();
        if(!$payment_gateway_currency){
            return Response::error(['Payment gateway not found'],[],400);
        }
        $gateway                    = $payment_gateway_currency->gateway;
        $payment_gateway_rate       = $payment_gateway_currency->rate;
        $payment_gateway_code       = $payment_gateway_currency->currency_code;
        $fixed_charge               = $transaction_settings->fixed_charge;
        $percent_charge             = ($transaction_settings->percent_charge * $amount) / 100;
        $total_charge               = $fixed_charge + $percent_charge;
        $total_amount               = $amount + $total_charge;
        $payable_amount             = $total_amount * $payment_gateway_rate;
        
        //agent profit calculation
        if($transaction_settings->agent_profit == true){
            $agent_profit_status            = $transaction_settings->agent_profit;
            $agent_fixed_commissions        = $transaction_settings->agent_fixed_commissions;
            $agent_percent_commissions      = ($transaction_settings->agent_percent_commissions * $amount) / 100;
            $total_commissions              = $agent_fixed_commissions + $agent_percent_commissions; 
        }else{
            $agent_profit_status            = false;
            $agent_fixed_commissions        = 0;
            $agent_percent_commissions      = 0;
            $total_commissions              = 0; 
        }

        $data                       = [
            'type'                  => PaymentGatewayConst::MONEYOUT,
            'identifier'            => Str::uuid(),
            'data'                  => [
                'base_currency'     => [
                    'currency'      => get_default_currency_code(),
                    'rate'          => get_default_currency_rate()
                ],
                'payment_gateway'   => [
                    'id'            => $payment_gateway_currency->id,
                    'alias'         => $payment_gateway_currency->alias,
                    'rate'          => $payment_gateway_rate,
                    'currency'      => $payment_gateway_code,
                    'name'          => $payment_gateway_currency->name
                ],
                'agent_profit'      => [
                    'agent_profit_status'   => $agent_profit_status ?? false,
                    'fixed_commission'      => floatval($agent_fixed_commissions) ?? 0,
                    'percent_commission'    => floatval($agent_percent_commissions) ?? 0,
                    'total_commission'      => floatval($total_commissions) ?? 0,
                ],
                'amount'            => floatval($amount),
                'fixed_charge'      => floatval($fixed_charge),
                'percent_charge'    => floatval($percent_charge),
                'total_charge'      => floatval($total_charge),
                'total_amount'      => floatval($total_amount),
                'payable_amount'    => floatval($payable_amount),
                'exchange_rate'     => floatval($payment_gateway_rate)
            ]                 
        ];
        try{
           $temporary_data = TemporaryData::create($data);
        }catch(Exception $e){
            return Response::error(['Something went wrong! Please try again.'],[],400);
        }
        return Response::success(['Temporary data created successfully.'],[
            'temporary_data'    => $temporary_data,
            'input_fields'      => $gateway->input_fields
        ],200);
    }
    /**
     * Method for confirm money out request
     * @param $identifier
     * @param Illuminate\Http\Request $request
     */
    public function confirm(Request $request){
        $tempDataValidate = Validator::make($request->all(),[
            'identifier'        => "required|string|exists:temporary_datas",
        ])->validate();

        $temp_data = TemporaryData::where('identifier',$tempDataValidate['identifier'])->first();
        if(!$temp_data || $temp_data->data == null || !isset($temp_data->data->payment_gateway->id)) return Response::error(['Invalid request'],[],400);
        $gateway_currency = PaymentGatewayCurrency::find($temp_data->data->payment_gateway->id);
        
        if(!$gateway_currency || !$gateway_currency->gateway->isManual())return Response::error(['Selected gateway is invalid'],[],400);

        $gateway = $gateway_currency->gateway;
        $dy_validation_rules                        = $this->generateValidationRules($gateway->input_fields);
        $validated                                  = Validator::make($request->all(),$dy_validation_rules)->validate();
        $get_values                                 = $this->placeValueWithFields($gateway->input_fields,$validated);
        $trx_id                                     = generateTrxString("transactions","trx_id","MO",8);
        $agent_wallet                               = AgentWallet::auth()->first();
        try{
            $insert_record_id = $this->insertRecord($trx_id,$temp_data,$agent_wallet,$get_values);
            
            if($temp_data->data->agent_profit->agent_profit_status == true){
                $this->agentprofits($insert_record_id,$temp_data);
            }
        }catch(Exception $e){
            return Response::error(['Something went wrong! Please try again.'],[],400);
        }
        return Response::success(['Money out request successfully sent to admin.'],[],200);        
    }
    /**
     * function for insert record
     * @param $trx_id,$temp_data,$agent_wallet,$get_values
     */
    function insertRecord($trx_id,$temp_data,$agent_wallet,$get_values){
        DB::beginTransaction();
        try{
            $id = DB::table('transactions')->insertGetId([
                'agent_id'                          => auth()->user()->id,
                'agent_wallet_id'                   => $agent_wallet->id,
                'payment_gateway_currency_id'       => $temp_data->data->payment_gateway->id,
                'type'                              => PaymentGatewayConst::MONEYOUT,
                'remittance_data'                   => json_encode([
                    'data'                          => $temp_data->data,
                ]),
                'trx_id'                            => $trx_id,
                'request_amount'                    => $temp_data->data->amount,
                'exchange_rate'                     => $temp_data->data->exchange_rate,
                'payable'                           => $temp_data->data->payable_amount,
                'fees'                              => $temp_data->data->total_charge,
                'remark'                            => "Money Out Successfull.",
                'details'                           => json_encode(['gateway_input_values' => $get_values]),
                'status'                            => GlobalConst::REMITTANCE_STATUS_PENDING,
                'attribute'                         => PaymentGatewayConst::SEND,
                'created_at'                        => now(),
            ]);
            $this->updateWalletBalance($agent_wallet,$temp_data->data->total_amount);
            $this->insertNotificationData($trx_id,$temp_data);

            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
        return $id;
    }
    /**
     * Function for update wallet balance
     * @param $agent_wallet,$amount
     */
    function updateWalletBalance($agent_wallet,$amount) {
        $balance        = $agent_wallet->balance - $amount;
        $agent_wallet->update([
            'balance'       => $balance,
        ]);
    }
    /**
     * Function for insert notification data
     * @param $data
     */
    function insertNotificationData($trx_id,$data){
        $basic_setting      = BasicSettings::first();
        $user               = auth()->user();
        if( $basic_setting->agent_email_notification == true){
            Notification::route("mail",$user->email)->notify(new MoneyOutNotification($user,$data,$trx_id));
        }
        //agent notification
        AgentNotification::create([
            'agent_id'          => $user->id,
            'message'           => "Money Out Request (Payable amount: ".get_amount($data->data->payable_amount)." ". $data->data->payment_gateway->currency .") Successfully Send.", 
        ]);
        // for admin notification
        $notification_message = [
            'title'     => "Money Out from " . "(" . $user->username . ")" . "Transaction ID :". $trx_id . " created successfully.",
            'time'      => Carbon::now()->diffForHumans(),
            'image'     => get_image($user->image,'agent-profile'),
        ];
        AdminNotification::create([
            'type'      => "Money Out",
            'admin_id'  => 1,
            'message'   => $notification_message,
        ]);
        
        DB::table("temporary_datas")->where("identifier",$data->identifier)->delete();
    }
    /**
     * function for save agent profits
     * @param $transaction_id,$data
     */
    function agentprofits($transaction_id,$data){
        $user           = auth()->user();
        DB::beginTransaction();
        try{
            DB::table('agent_profits')->insert([
                'agent_id'              => $user->id,
                'transaction_id'        => $transaction_id,
                'fixed_commissions'     => $data->data->agent_profit->fixed_commission,
                'percent_commissions'   => $data->data->agent_profit->percent_commission,
                'total_commissions'     => $data->data->agent_profit->total_commission,
                'created_at'            => now()
            ]);
            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
            return Response::error(['Something went wrong! Please try again.'],[],400);
        }
    }
}
