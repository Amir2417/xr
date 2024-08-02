<?php

namespace App\Http\Controllers\Api\V1\Agent;

use Exception;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TemporaryData;
use App\Constants\GlobalConst;
use App\Http\Helpers\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Constants\PaymentGatewayConst;
use App\Models\Admin\TransactionSetting;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\PaymentGatewayCurrency;
use App\Http\Helpers\MoneyIn as MoneyInHelper;

class MoneyInController extends Controller
{
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
}
