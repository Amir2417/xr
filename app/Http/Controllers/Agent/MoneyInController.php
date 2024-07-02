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
        return redirect()->route('agent.moneyin.preview',$temporary_data->identifier);     
                
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
        return redirect()->route("agent.moneyin.index")->with(['success' => ['Successfully Money In']]);
    }
    /**
     * Method for pagadito success
     */
    public function successPagadito(Request $request, $gateway){
        $token = MoneyInHelper::getToken($request->all(),$gateway);
        $temp_data = TemporaryData::where("identifier",$token)->first();
        if($temp_data->data->creator_guard == 'agent'){
            Auth::guard($temp_data->data->creator_guard)->loginUsingId($temp_data->data->creator_id);
            try{
                if(Transaction::where('callback_ref', $token)->exists()) {
                    if(!$temp_data) return redirect()->route("agent.moneyin.index")->with(['success' => [__('Successfully Money in')]]);
                }else {
                    if(!$temp_data) return redirect()->route('index')->with(['error' => [__("transaction_record")]]);
                }

                $update_temp_data = json_decode(json_encode($temp_data->data),true);
                $update_temp_data['callback_data']  = $request->all();
                $temp_data->update([
                    'data'  => $update_temp_data,
                ]);
                $temp_data = $temp_data->toArray();
                $instance = MoneyInHelper::init($temp_data)->type(PaymentGatewayConst::MONEYIN)->responseReceive();
            }catch(Exception $e) {
                return back()->with(['error' => [$e->getMessage()]]);
            }
            return redirect()->route("agent.moneyin.index")->with(['success' => ['Successfully Money in']]);
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
                        return Response::success([__('Successfully Money in')],[],400);
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
        return redirect()->route('agent.moneyin.index');
    }
    /**
     * This method for call back alert
     * @method POST
     * @param Illuminate\Http\Request $request
     * @return Illuminate\Http\Request
     */
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
    /**
     * This method for post success alert of SSL
     */
    public function postSuccess(Request $request, $gateway)
    {
        try{
            $token = MoneyInHelper::getToken($request->all(),$gateway);
            $temp_data = TemporaryData::where("identifier",$token)->first();
            
            Auth::guard($temp_data->data->creator_guard)->loginUsingId($temp_data->data->creator_id);
        }catch(Exception $e) {
            return redirect()->route('index');
        }
        return $this->success($request, $gateway);
    }
    /**
     * This method for post cancel alert of SSL
     */
    public function postCancel(Request $request, $gateway)
    {
        try{
            $token = MoneyInHelper::getToken($request->all(),$gateway);
            $temp_data = TemporaryData::where("identifier",$token)->first();
            Auth::guard($temp_data->data->creator_guard)->loginUsingId($temp_data->data->creator_id);
        }catch(Exception $e) {
            
            return redirect()->route('index');
        }
        return $this->cancel($request, $gateway);
    }
    /**
     * Redirect Users for collecting payment via Button Pay (JS Checkout)
     */
    public function redirectBtnPay(Request $request, $gateway)
    {
        try{
            return MoneyInHelper::init([])->type(PaymentGatewayConst::MONEYIN)->handleBtnPay($gateway, $request->all());
        }catch(Exception $e) {
            return redirect()->route('agent.moneyin.index')->with(['error' => [$e->getMessage()]]);
        }
    }
    /**
     * Method for create manual payment info
     */
    public function handleManualPayment($payment_info) {
        // Insert temp data
        $data = [
            'type'          => PaymentGatewayConst::MONEYIN,
            'identifier'    => generate_unique_string("temporary_datas","identifier",16),
            'data'          => [
                'gateway_currency_id'    => $payment_info['currency']->id,
                'amount'                 => $payment_info['amount'],
                'form_data'              => $payment_info['form_data']['identifier'],
            ],
        ];

        try{
            TemporaryData::create($data);
        }catch(Exception $e) {
            return redirect()->route('agent.moneyin.index')->with(['error' => ['Failed to save data. Please try again']]);
        }
        return redirect()->route('agent.moneyin.manual.form',$data['identifier']);
    }
    /**
     * Method for view manual form
     * @return view
     */
    public function showManualForm($token) {
        
        $tempData = TemporaryData::search($token)->first();
        if(!$tempData || $tempData->data == null || !isset($tempData->data->gateway_currency_id)) return redirect()->route('user.send.remittance.index')->with(['error' => ['Invalid request']]);
        $gateway_currency = PaymentGatewayCurrency::find($tempData->data->gateway_currency_id);
        if(!$gateway_currency || !$gateway_currency->gateway->isManual()) return redirect()->route('user.send.remittance.index')->with(['error' => ['Selected gateway is invalid']]);
        $gateway = $gateway_currency->gateway;
        if(!$gateway->input_fields || !is_array($gateway->input_fields)) return redirect()->route('user.send.remittance.index')->with(['error' => ['This payment gateway is under constructions. Please try with another payment gateway']]);
        $amount = $tempData->data->amount;

        $page_title = "- Payment Instructions";
        

        return view('agent.sections.money-in.manual.payment_confirmation',compact(
            "gateway",
            "page_title",
            "token",
            "amount"
        ));
    }

    public function manualSubmit(Request $request,$token) {
       
        $basic_setting = BasicSettings::first();
        $user          = auth()->user();
        $request->merge(['identifier' => $token]);
        $tempDataValidate = Validator::make($request->all(),[
            'identifier'        => "required|string|exists:temporary_datas",
        ])->validate();

        $tempData = TemporaryData::search($tempDataValidate['identifier'])->first();
        if(!$tempData || $tempData->data == null || !isset($tempData->data->gateway_currency_id)) return redirect()->route('user.send.remittance.index')->with(['error' => ['Invalid request']]);
        $gateway_currency = PaymentGatewayCurrency::find($tempData->data->gateway_currency_id);
        if(!$gateway_currency || !$gateway_currency->gateway->isManual()) return redirect()->route('user.send.remittance.index')->with(['error' => ['Selected gateway is invalid']]);
        $gateway = $gateway_currency->gateway;
        $amount = $tempData->data->amount ?? null;
        if(!$amount) return redirect()->route('user.send.remittance.index')->with(['error' => ['Transaction Failed. Failed to save information. Please try again']]);
        
        $this->file_store_location  = "transaction";
        $dy_validation_rules        = $this->generateValidationRules($gateway->input_fields);
        
        $validated  = Validator::make($request->all(),$dy_validation_rules)->validate();
        $get_values = $this->placeValueWithFields($gateway->input_fields,$validated);
       
        
        $data   = TemporaryData::where('identifier',$tempData->data->form_data)->first();
       
        $trx_id = generateTrxString("transactions","trx_id","SR",8);
        
        // Make Transaction
        DB::beginTransaction();
        try{
            $id = DB::table("transactions")->insertGetId([
                'user_id'                       => auth()->user()->id,
                'payment_gateway_currency_id'   => $gateway_currency->id,
                'type'                          => PaymentGatewayConst::TYPESENDREMITTANCE,
                'remittance_data'               => json_encode([
                    'type'                      => $data->type,
                    'sender_name'               => $data->data->sender_name,
                    'sender_email'              => $data->data->sender_email,
                    'sender_currency'           => $data->data->sender_currency,
                    'receiver_currency'         => $data->data->receiver_currency,
                    'sender_ex_rate'            => $data->data->sender_ex_rate,
                    'sender_base_rate'          => $data->data->sender_base_rate,
                    'receiver_ex_rate'          => $data->data->receiver_ex_rate,
                    'coupon_id'                 => $data->data->coupon_id,
                    'coupon_type'               => $data->data->coupon_type,
                    'first_name'                => $data->data->first_name,
                    'middle_name'               => $data->data->middle_name,
                    'last_name'                 => $data->data->last_name,
                    'email'                     => $data->data->email,
                    'country'                   => $data->data->country,
                    'city'                      => $data->data->city,
                    'state'                     => $data->data->state,
                    'zip_code'                  => $data->data->zip_code,
                    'phone'                     => $data->data->phone,
                    'method_name'               => $data->data->method_name,
                    'account_number'            => $data->data->account_number,
                    'address'                   => $data->data->address,
                    'document_type'             => $data->data->document_type,
                    'front_image'               => $data->data->front_image,
                    'back_image'                => $data->data->back_image,
                    
                    'sending_purpose'           => $data->data->sending_purpose->name,
                    'source'                    => $data->data->source->name,
                    'currency'                  => [
                        'name'                  => $data->data->currency->name,
                        'code'                  => $data->data->currency->code,
                        'rate'                  => $data->data->currency->rate,
                    ],
                    'send_money'                => $data->data->send_money,
                    'fees'                      => $data->data->fees,
                    'convert_amount'            => $data->data->convert_amount,
                    'payable_amount'            => $data->data->payable_amount,
                    'remark'                    => $data->data->remark,
                ]),
                'trx_id'                        => $trx_id,
                'request_amount'                => $data->data->send_money,
                'exchange_rate'                 => $data->data->currency->rate,
                'payable'                       => $data->data->payable_amount,
                'fees'                          => $data->data->fees,
                'convert_amount'                => $data->data->convert_amount,
                'will_get_amount'               => $data->data->receive_money,
                'remark'                        => 'Manual',
                'details'                       => "COMPLETED",
                'status'                        => global_const()::REMITTANCE_STATUS_PENDING,
                'attribute'                     => PaymentGatewayConst::SEND,
                'created_at'                    => now(),
                'callback_ref'                  => $output['callback_ref'] ?? null,
            ]);
            if($data->data->coupon_id != 0){
                if($data->data->coupon_type == GlobalConst::COUPON){
                    $coupon_id  = $data->data->coupon_id;
                    $user   = auth()->user();
                    CouponTransaction::create([
                        'user_id'   => $user->id,
                        'coupon_id'   => $coupon_id,
                        'transaction_id'   => $id,
                    ]);
                }else{
                    $user_coupon_id = $data->data->coupon_id;
                    $user   = auth()->user();
                    CouponTransaction::create([
                        'user_id'           => $user->id,
                        'user_coupon_id'    => $user_coupon_id,
                        'transaction_id'    => $id,
                    ]);
                }
            }
            if( $basic_setting->email_notification == true){
                Notification::route("mail",$user->email)->notify(new manualEmailNotification($user,$data,$trx_id));
            }
            $notification_message = [
                'title'     => "Send Remittance from " . "(" . $user->username . ")" . "Transaction ID :". $trx_id . " created successfully.",
                'time'      => Carbon::now()->diffForHumans(),
                'image'     => get_image($user->image,'user-profile'),
            ];
            AdminNotification::create([
                'type'      => "Send Remittance",
                'admin_id'  => 1,
                'message'   => $notification_message,
            ]);
            (new PushNotificationHelper())->prepare([1],[
                'title' => "Send Remittance from " . "(" . $user->username . ")" . "Transaction ID :". $trx_id . " created successfully.",
                'desc'  => "",
                'user_type' => 'admin',
            ])->send();
            DB::table("temporary_datas")->where("identifier",$token)->delete();
            DB::commit();
        }catch(Exception $e) {
            DB::rollBack();
            return redirect()->route('user.send.remittance.manual.form',$token)->with(['error' => ['Something went wrong! Please try again']]);
        }
        return redirect()->route("user.payment.confirmation",$trx_id)->with(['success' => ['Successfully send remittance']]);
    }

}
