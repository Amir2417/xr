<?php

namespace App\Http\Controllers\Api\V1\Agent;

use Exception;
use Carbon\Carbon;
use App\Models\Transaction;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Constants\PaymentGatewayConst;
use App\Models\Admin\AdminNotification;
use App\Models\Admin\TransactionSetting;
use App\Traits\ControlDynamicInputFields;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\PaymentGatewayCurrency;
use Illuminate\Support\Facades\Notification;
use App\Http\Helpers\MoneyIn as MoneyInHelper;
use App\Notifications\Agent\MoneyInNotification;

class MoneyInController extends Controller
{
    use ControlDynamicInputFields;
    /**
     * Method for get money in data
     */
    public function index(){
        $payment_gateway            = PaymentGatewayCurrency::whereHas('gateway', function ($gateway) {
            $gateway->where('slug', PaymentGatewayConst::remittance_money_slug());
            $gateway->where('status', 1);
        })->get()->map(function($data){
            if($data->gateway->isManual()){
                $name = $data->name . '(' . ("Manual") .')';
            }else{
                $name = $data->name;
            }
            return [
                'name'              => $name,
                'alias'             => $data->alias
            ];
        });

        $transaction_settings   = TransactionSetting::where('slug',GlobalConst::MONEY_IN)->where('status',true)->first();
        $limits_data            = [
            'min_limit'         => get_amount($transaction_settings->min_limit),
            'max_limit'         => get_amount($transaction_settings->max_limit),
        ];

        return Response::success(['Money in data fetch successfully.'],[
            'base_currency'         => get_default_currency_code(),
            'payment_gateway'       => $payment_gateway,
            'limits_data'           => $limits_data
        ],200);
    }
    /**
     * Method for submit money in request
     * @param Illuminate\Http\Request $request
     */
    public function submit(Request $request){
        $validator                  = Validator::make($request->all(),[
            'amount'                =>'required|numeric',
            'payment_gateway'       => 'required'
        ]);
        if($validator->fails()){
            return Response::validation(['error' => $validator->errors()->all()]);
        }
        $validated                  = $validator->validate();
        $amount                     = $validated['amount'];
        $transaction_settings       = TransactionSetting::where('slug',GlobalConst::MONEY_IN)->where('status',true)->first();
        if(!$transaction_settings){
            return Response::error(['Transaction settings not found'],[],400);
        }
        if($amount < $transaction_settings->min_limit || $amount > $transaction_settings->max_limit){
            return Response::error(['Please follow the transaction limit.'],[],400);
        }
        //
        $payment_gateway_currency   = PaymentGatewayCurrency::where('alias',$validated['payment_gateway'])->first();
        if(!$payment_gateway_currency){
            return Response::error(['Payment gateway not found.'],[],400);
        }

        $payment_gateway_rate       = $payment_gateway_currency->rate;
        $payment_gateway_code       = $payment_gateway_currency->currency_code;
        $fixed_charge               = $transaction_settings->fixed_charge;
        $percent_charge             = ($transaction_settings->percent_charge * $amount) / 100;
        $total_charge               = $fixed_charge + $percent_charge;
        $payable_amount             = ($amount + $total_charge) * $payment_gateway_rate;

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
            'type'                  => PaymentGatewayConst::MONEYIN,
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
                    'agent_profit_status'   => $agent_profit_status,
                    'fixed_commission'      => floatval($agent_fixed_commissions),
                    'percent_commission'    => floatval($agent_percent_commissions),
                    'total_commission'      => floatval($total_commissions),
                ],
                'amount'            => floatval($amount),
                'fixed_charge'      => floatval($fixed_charge),
                'percent_charge'    => floatval($percent_charge),
                'total_charge'      => floatval($total_charge),
                'payable_amount'    => floatval($payable_amount),
                'receive_amount'    => floatval($amount),
                'exchange_rate'     => floatval($payment_gateway_rate)
            ]                 
        ];
        try{
           $temporary_data = TemporaryData::create($data);
        }catch(Exception $e){
            return Response::error(['Something went wrong! Please try again.'],[],400);
        }
        return Response::success(['Money in information.'],[
            'temporary_data'  => $temporary_data,
        ],200);                
    }
    /**
     * Method for confirm money in process
     * @param Illuminate\Http\Request $request
     */
    public function confirm(Request $request){
        try{
            $instance = MoneyInHelper::init($request->all())->type(PaymentGatewayConst::MONEYIN)->gateway()->api()->render();
        }catch(Exception $e){
            return back()->with(['error' => [$e->getMessage()]]);
        }
        if($instance instanceof RedirectResponse === false && isset($instance['gateway_type']) && $instance['gateway_type'] == PaymentGatewayConst::MANUAL) {
            return Response::error([__('Can\'t submit manual gateway in automatic link')],[],400);
        }
        return Response::success([__('Payment gateway response successful')],[
            'redirect_url'          => $instance['redirect_url'],
            'redirect_links'        => $instance['redirect_links'],
            'action_type'           => $instance['type']  ?? false, 
            'address_info'          => $instance['address_info'] ?? [],
        ],200); 
    }
    /**
     * This method for success alert of PayPal
     * @method POST
     * @param Illuminate\Http\Request $request
     * @return Illuminate\Http\Request
     */
    public function success(Request $request, $gateway){
        try{
            
            $token = MoneyInHelper::getToken($request->all(),$gateway);
            
            $temp_data = TemporaryData::where("identifier",$token)->first();
            
            if(Transaction::where('callback_ref', $token)->exists()) {
                if(!$temp_data) return Response::success(['Transaction request sended successfully!'],[],200);
            }else {
                if(!$temp_data) return Response::error(['Transaction failed. Record didn\'t saved properly. Please try again.'],[],200);
            }

            $update_temp_data = json_decode(json_encode($temp_data->data),true);
            
            $update_temp_data['callback_data']  = $request->all();

            $temp_data->update([
                'data'  => $update_temp_data,
            ]);
            $temp_data = $temp_data->toArray();
            $instance = MoneyInHelper::init($temp_data)->responseReceive();
           
            if($instance instanceof RedirectResponse) return $instance;
        }catch(Exception $e) {
            
            return back()->with(['error' => [$e->getMessage()]]);
        }
        return Response::success(["Payment successful, please go back your app"],[],200);
    }
    /**
     * This method for cancel alert of PayPal
     * @method POST
     * @param Illuminate\Http\Request $request
     * @return Illuminate\Http\Request
     */
    public function cancel(Request $request, $gateway) {
        if($request->has('token')) {
            $identifier = $request->token;
            if($temp_data = TemporaryData::where('identifier', $identifier)->first()) {
                $temp_data->delete();
            }
        }
        return Response::success([__('Payment process cancel successfully!')],[],200);
    }
    /**
     * This method for post success alert of SSL
     */
    public function postSuccess(Request $request, $gateway){
        try{
            
            $token = MoneyInHelper::getToken($request->all(),$gateway);
            $temp_data = TemporaryData::where("identifier",$token)->first();
            if($temp_data && $temp_data->data->creator_guard != 'agent_api') {
                Auth::guard($temp_data->data->creator_guard)->loginUsingId($temp_data->data->creator_id);
            }
        }catch(Exception $e) {
            return Response::error([$e->getMessage()]);
        }
        return $this->success($request, $gateway);
    }
    /**
     * This method for post cancel alert of SSL
     */
    public function postCancel(Request $request, $gateway){
        try{
            $token = MoneyInHelper::getToken($request->all(),$gateway);
            $temp_data = TemporaryData::where("identifier",$token)->first();
            if($temp_data && $temp_data->data->creator_guard != 'agent_api') {
                Auth::guard($temp_data->data->creator_guard)->loginUsingId($temp_data->data->creator_id);
            }
        }catch(Exception $e) {
            
            return Response::error([$e->getMessage()]);
        }
        return $this->cancel($request, $gateway);
    }
    /**
     * Redirect Users for collecting payment via Button Pay (JS Checkout)
     */
    public function redirectBtnPay(Request $request, $gateway){
        try{
            return MoneyInHelper::init([])->type(PaymentGatewayConst::MONEYIN)->handleBtnPay($gateway, $request->all());
        }catch(Exception $e) {
            return Response::error([$e->getMessage()], [], 500);
        }
    }
    /**
     * Method for getting manual input fields
     * @param Illuminate\Http\Request $request
     */
    public function manualInputFields(Request $request) {
       
        $validator = Validator::make($request->all(),[
            'alias'         => "required|string|exists:payment_gateway_currencies",
        ]);

        if($validator->fails()) {
            return Response::error($validator->errors()->all(),[],400);
        }

        $validated = $validator->validate();
        $gateway_currency = PaymentGatewayCurrency::where("alias",$validated['alias'])->first();

        $gateway = $gateway_currency->gateway;

        if(!$gateway->isManual()) return Response::error([__('Can\'t get fields. Requested gateway is automatic')],[],400);

        if(!$gateway->input_fields || !is_array($gateway->input_fields)) return Response::error([__("This payment gateway is under constructions. Please try with another payment gateway")],[],503);

        try{
            $input_fields = json_decode(json_encode($gateway->input_fields),true);
            $input_fields = array_reverse($input_fields);
        }catch(Exception $e) {
            return Response::error([__("Something went wrong! Please try again")],[],500);
        }
        
        return Response::success([__('Payment gateway input fields fetch successfully!')],[
            'gateway'           => [
                'desc'          => $gateway->desc
            ],
            'input_fields'      => $input_fields,
            'currency'          => $gateway_currency->only(['alias']),
        ],200);
    }
    /**
     * Method for submit manual input fields
     * @param Illuminate\Http\Request $request
     */
    /**
     * Method for submit data in manual format
     * @param Illuminate\Http\Request $request
     */
    public function manualSubmit(Request $request) {
        $basic_setting = BasicSettings::first();
        $user          = auth()->user();
        $tempDataValidate = Validator::make($request->all(),[
            'identifier'        => "required|string|exists:temporary_datas",
        ])->validate();

        $tempData = TemporaryData::where('identifier',$tempDataValidate['identifier'])->first();
        if(!$tempData || $tempData->data == null || !isset($tempData->data->payment_gateway->id)) return Response::error(['Invalid request!'],[],400);
        $gateway_currency = PaymentGatewayCurrency::find($tempData->data->payment_gateway->id);
        if(!$gateway_currency || !$gateway_currency->gateway->isManual()) return Response::error(['Selected gateway is invalid.'],[],400);
        $gateway = $gateway_currency->gateway;
        $amount = $tempData->data->amount ?? null;
        if(!$amount) return Response::error(['Transaction Failed. Failed to save information. Please try again'],[],400);
        
        $this->file_store_location  = "transaction";
        $dy_validation_rules        = $this->generateValidationRules($gateway->input_fields);
        
        $validated  = Validator::make($request->all(),$dy_validation_rules)->validate();
        $get_values = $this->placeValueWithFields($gateway->input_fields,$validated);
       
        $data   = TemporaryData::where('identifier',$tempData->identifier)->first();
        
        $agent_wallet       = AgentWallet::auth()->first();
        $trx_id = generateTrxString("transactions","trx_id","SR",8);
        
        // Make Transaction
        DB::beginTransaction();
        try{
            $id = DB::table("transactions")->insertGetId([
                'agent_id'                      => auth()->user()->id,
                'agent_wallet_id'               => $agent_wallet->id,
                'payment_gateway_currency_id'   => $gateway_currency->id,
                'type'                          => PaymentGatewayConst::MONEYIN,
                'remittance_data'               => json_encode([
                    'type'                      => $data->type,
                    'data'                      => $data->data,
                ]),
                'trx_id'                        => $trx_id,
                'request_amount'                => $data->data->amount,
                'exchange_rate'                 => $data->data->exchange_rate,
                'payable'                       => $data->data->payable_amount,
                'fees'                          => $data->data->total_charge,
                'will_get_amount'               => $data->data->receive_amount,
                'remark'                        => 'Manual',
                'details'                       => "MoneyIn Successfull",
                'status'                        => global_const()::REMITTANCE_STATUS_PENDING,
                'attribute'                     => PaymentGatewayConst::RECEIVED,
                'created_at'                    => now(),
                'callback_ref'                  => $output['callback_ref'] ?? null,
            ]);
            
            if( $basic_setting->agent_email_notification == true){
                Notification::route("mail",$user->email)->notify(new MoneyInNotification($user,$data,$trx_id));
            }
            //agent notification
            AgentNotification::create([
                'agent_id'          => $user->id,
                'message'  => "Money In  (Payable amount: ".get_amount($data->data->payable_amount)." ". $data->data->payment_gateway->currency .",
                Get Amount: ".get_amount($data->data->receive_amount)." ". $data->data->base_currency->currency .") Successfully Received.", 
            ]);
            // for admin notification
            $notification_message = [
                'title'     => "Money In from " . "(" . $user->username . ")" . "Transaction ID :". $trx_id . " created successfully.",
                'time'      => Carbon::now()->diffForHumans(),
                'image'     => get_image($user->image,'agent-profile'),
            ];
            AdminNotification::create([
                'type'      => "Money In",
                'admin_id'  => 1,
                'message'   => $notification_message,
            ]);
            
            DB::commit();
        }catch(Exception $e) {
            DB::rollBack();
            return Response::success(['Something went wrong! Please try again'],[],200);
        }
        return Response::success(['Successfully MoneyIn'],[],200);
    }
    
}
