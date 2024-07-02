<?php

namespace App\Http\Controllers\Agent;

use Exception;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TemporaryData;
use App\Constants\GlobalConst;
use App\Http\Helpers\Response;
use App\Models\Agent\AgentWallet;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Constants\PaymentGatewayConst;
use App\Models\Admin\TransactionSetting;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\PaymentGatewayCurrency;
use App\Http\Helpers\MoneyIn as MoneyInHelper;

class MoneyInController extends Controller
{
    /**
     * Method for show money in index page
     * @return view
     */
    public function index(){
        $page_title                 = "MoneyIn";
        $payment_gateway            = PaymentGatewayCurrency::whereHas('gateway', function ($gateway) {
            $gateway->where('slug', PaymentGatewayConst::remittance_money_slug());
            $gateway->where('status', 1);
        })->get();
        $transaction_settings       = TransactionSetting::where('status',true)
                                        ->where('slug',GlobalConst::MONEY_IN)
                                        ->first();

        return view('agent.sections.money-in.index',compact(
            'page_title',
            'payment_gateway',
            'transaction_settings'
        ));
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
            return back()->withErrors($validator)->withInput($request->all());
        }
        $validated                  = $validator->validate();
        $amount                     = $validated['amount'];
        $transaction_settings       = TransactionSetting::where('slug',GlobalConst::MONEY_IN)->where('status',true)->first();
        if(!$transaction_settings){
            return back()->with(['error' => ['Transaction settings not found']]);
        }
        if($amount < $transaction_settings->min_limit || $amount > $transaction_settings->max_limit){
            return back()->with(['error' => ['Please follow the transaction limit. ']]);
        }
        //
        $payment_gateway_currency   = PaymentGatewayCurrency::where('id',$validated['payment_gateway'])->first();
        if(!$payment_gateway_currency){
            return back()->with(['error' => ['Payment gateway not found']]);
        }

        $payment_gateway_rate       = $payment_gateway_currency->rate;
        $payment_gateway_code       = $payment_gateway_currency->currency_code;
        $fixed_charge               = $transaction_settings->fixed_charge;
        $percent_charge             = ($transaction_settings->percent_charge * $amount) / 100;
        $total_charge               = $fixed_charge + $percent_charge;
        $payable_amount             = ($amount + $total_charge) * $payment_gateway_rate;

        //agent profit calculation
        if($transaction_settings->agent_profit == true){
            
            $agent_fixed_commissions         = $transaction_settings->agent_fixed_commissions;
            $agent_percent_commissions      = ($transaction_settings->agent_percent_commissions * $amount) / 100;
            $total_commissions               = $agent_fixed_commissions + $agent_percent_commissions; 
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
                    'fixed_commission'      => floatval($agent_fixed_commissions) ?? 0,
                    'percent_commission'    => floatval($agent_percent_commissions) ?? 0,
                    'total_commission'      => floatval($total_commissions) ?? 0,
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
            return back()->with(['error' => ['Something went wrong! Please try again.']]);
        }
        return redirect()->route('agent.moneyin.preview',$temporary_data->identifier)->with(['success' => ['Money in information saved successfully.']]);     
                
    }
    /**
     * Method for view money in preview page
     * @param $identifier
     */
    public function preview($identifier){
    $page_title         = "Transactions Conformation";
        $temporary_data     = TemporaryData::where('identifier',$identifier)->first();
        if(!$temporary_data) return back()->with(['error' => ['Sorry! Data not found.']]);

        return view('agent.sections.money-in.preview',compact(
            'page_title',
            'temporary_data'
        ));

    }
    /**
     * Method for confirm money in process
     * @param Illuminate\Http\Request $request
     */
    public function confirm(Request $request){
        try{
            $instance = MoneyInHelper::init($request->all())->type(PaymentGatewayConst::MONEYIN)->gateway()->render();
            if($instance instanceof RedirectResponse === false && isset($instance['gateway_type']) && $instance['gateway_type'] == PaymentGatewayConst::MANUAL) {
                $manual_handler = $instance['distribute'];
                return $this->$manual_handler($instance);
            }
        }catch(Exception $e){
            return back()->with(['error' => [$e->getMessage()]]);
        }
        return $instance;
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
                if(!$temp_data) return redirect()->route('agent.moneyin.index')->with(['success' => ['Transaction request sended successfully!']]);;
            }else {
                if(!$temp_data) return redirect()->route('agent.moneyin.index')->with(['error' => ['Transaction failed. Record didn\'t saved properly. Please try again.']]);
            }

            $update_temp_data = json_decode(json_encode($temp_data->data),true);
            
            $update_temp_data['callback_data']  = $request->all();

            $temp_data->update([
                'data'  => $update_temp_data,
            ]);
            $temp_data = $temp_data->toArray();
            $instance = MoneyInHelper::init($temp_data)->type(PaymentGatewayConst::MONEYIN)->responseReceive();
           
            
            if($instance instanceof RedirectResponse) return $instance;
        }catch(Exception $e) {
            
            return back()->with(['error' => [$e->getMessage()]]);
        }
        
        return redirect()->route("user.payment.confirmation",$instance)->with(['success' => ['Successfully Send Remittance']]);
    }
    /**
     * Method for pagadito success
     */
    public function successPagadito(Request $request, $gateway){
        $token = MoneyInHelper::getToken($request->all(),$gateway);
        $temp_data = TemporaryData::where("identifier",$token)->first();
        if($temp_data->data->creator_guard == 'web'){
            Auth::guard($temp_data->data->creator_guard)->loginUsingId($temp_data->data->creator_id);
            try{
                if(Transaction::where('callback_ref', $token)->exists()) {
                    if(!$temp_data) return redirect()->route("agent.moneyin.index")->with(['success' => [__('Successfully Send Remittance')]]);
                }else {
                    if(!$temp_data) return redirect()->route('index')->with(['error' => [__("transaction_record")]]);
                }

                $update_temp_data = json_decode(json_encode($temp_data->data),true);
                $update_temp_data['callback_data']  = $request->all();
                $temp_data->update([
                    'data'  => $update_temp_data,
                ]);
                $temp_data = $temp_data->toArray();
                $instance = MoneyInHelper::init($temp_data)->type(PaymentGatewayConst::TYPESENDREMITTANCE)->responseReceive();
            }catch(Exception $e) {
                return back()->with(['error' => [$e->getMessage()]]);
            }
            return redirect()->route("user.payment.confirmation",$instance)->with(['success' => ['Successfully Send Remittance']]);
        }elseif($temp_data->data->creator_guard =='api'){
            $creator_table = $temp_data->data->creator_table ?? null;
            $creator_id = $temp_data->data->creator_id ?? null;
            $creator_guard = $temp_data->data->creator_guard ?? null;
            $api_authenticated_guards = PaymentGatewayConst::apiAuthenticateGuard();
            if($creator_table != null && $creator_id != null && $creator_guard != null) {
                if(!array_key_exists($creator_guard,$api_authenticated_guards)) return Response::success([__('Request user doesn\'t save properly. Please try again')],[],400);
                $creator = DB::table($creator_table)->where("id",$creator_id)->first();
                if(!$creator) return Response::success([__('Request user doesn\'t save properly. Please try again')],[],400);
                $api_user_login_guard = $api_authenticated_guards[$creator_guard];
                Auth::guard($api_user_login_guard)->loginUsingId($creator->id);
            }
            try{
                if(!$temp_data) {
                    if(Transaction::where('callback_ref',$token)->exists()) {
                        return Response::success([__('Successfully Send Remittance')],[],400);
                    }else {
                        return Response::error([__('transaction_record')],[],400);
                    }
                }
                $update_temp_data = json_decode(json_encode($temp_data->data),true);
                $update_temp_data['callback_data']  = $request->all();
                $temp_data->update([
                    'data'  => $update_temp_data,
                ]);
                $temp_data = $temp_data->toArray();
                $instance = MoneyInHelper::init($temp_data)->type(PaymentGatewayConst::MONEYIN)->responseReceive();

                // return $instance;
            }catch(Exception $e) {
                return Response::error([$e->getMessage()],[],500);
            }
            
            return Response::success(["Payment successful, please go back your app"],[],200);
        }

    }
    public function cancel(Request $request, $gateway) {
        if($request->has('token')) {
            $identifier = $request->token;
            if($temp_data = TemporaryData::where('identifier', $identifier)->first()) {
                $temp_data->delete();
            }
        }
        return redirect()->route('agent.moneyin.index');
    }
    public function callback(Request $request,$gateway) {

        $callback_token = $request->get('token');
        $callback_data = $request->all();
        
        try{
            MoneyInHelper::init([])->type(PaymentGatewayConst::MONEYIN)->handleCallback($callback_token,$callback_data,$gateway);
        }catch(Exception $e) {
            // handle Error
            logger($e);
        }
    }

}
