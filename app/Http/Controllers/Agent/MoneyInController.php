<?php

namespace App\Http\Controllers\Agent;

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
use App\Models\Admin\CryptoTransaction;
use App\Models\Admin\TransactionSetting;
use App\Traits\ControlDynamicInputFields;
use Illuminate\Support\Facades\Validator;
use App\Http\Helpers\PushNotificationHelper;
use App\Models\Admin\PaymentGatewayCurrency;
use Illuminate\Support\Facades\Notification;
use App\Http\Helpers\MoneyIn as MoneyInHelper;
use App\Notifications\Agent\MoneyInNotification;

class MoneyInController extends Controller
{
    use ControlDynamicInputFields;
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

        $transactions               = Transaction::agentAuth()->with(['agent','currency'])
                                        ->where('type',PaymentGatewayConst::MONEYIN)
                                        ->latest()->take(3)->get();
        
        return view('agent.sections.money-in.index',compact(
            'page_title',
            'payment_gateway',
            'transaction_settings',
            'transactions'
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
                    'agent_profit_status'   => $agent_profit_status ?? false,
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
        $page_title         = "Transactions Confirmation";
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
                if(!$temp_data) return redirect()->route('agent.moneyin.index')->with(['success' => ['Transaction request sended successfully!']]);
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

        $page_title = "Payment Instructions";
        

        return view('agent.sections.money-in.manual.payment_confirmation',compact(
            "gateway",
            "page_title",
            "token",
            "amount"
        ));
    }
    /**
     * Method for submit data in manual format
     * @param $token
     * @param Illuminate\Http\Request $request
     */
    public function manualSubmit(Request $request,$token) {
        $basic_setting = BasicSettings::first();
        $user          = auth()->user();
        $request->merge(['identifier' => $token]);
        $tempDataValidate = Validator::make($request->all(),[
            'identifier'        => "required|string|exists:temporary_datas",
        ])->validate();

        $tempData = TemporaryData::search($tempDataValidate['identifier'])->first();
        if(!$tempData || $tempData->data == null || !isset($tempData->data->payment_gateway->id)) return redirect()->route('agent.moneyin.index')->with(['error' => ['Invalid request']]);
        $gateway_currency = PaymentGatewayCurrency::find($tempData->data->payment_gateway->id);
        if(!$gateway_currency || !$gateway_currency->gateway->isManual()) return redirect()->route('agent.moneyin.index')->with(['error' => ['Selected gateway is invalid']]);
        $gateway = $gateway_currency->gateway;
        $amount = $tempData->data->amount ?? null;
        if(!$amount) return redirect()->route('agent.moneyin.index')->with(['error' => ['Transaction Failed. Failed to save information. Please try again']]);
        
        $this->file_store_location  = "transaction";
        $dy_validation_rules        = $this->generateValidationRules($gateway->input_fields);
        
        $validated  = Validator::make($request->all(),$dy_validation_rules)->validate();
        $get_values = $this->placeValueWithFields($gateway->input_fields,$validated);
       
        
        $data   = TemporaryData::where('identifier',$tempData->data->form_data)->first();
        
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
            (new PushNotificationHelper())->prepare([1],[
                'title' => "Money In from " . "(" . $user->username . ")" . "Transaction ID :". $trx_id . " created successfully.",
                'desc'  => "",
                'user_type' => 'admin',
            ])->send();

            DB::table("temporary_datas")->where("identifier",$token)->delete();
            DB::commit();
        }catch(Exception $e) {
            DB::rollBack();
            return redirect()->route('agent.moneyin.manual.form',$token)->with(['error' => ['Something went wrong! Please try again']]);
        }
        return redirect()->route("agent.moneyin.index")->with(['success' => ['Successfully MoneyIn']]);
    }
    public function cryptoPaymentAddress(Request $request, $trx_id) {

        $page_title = "Crypto Payment Address";
        $transaction = Transaction::where('trx_id', $trx_id)->firstOrFail();
        

        if($transaction->currency->gateway->isCrypto() && $transaction->details?->payment_info?->receiver_address ?? false) {
            return view('agent.sections.money-in.payment.crypto.address', compact(
                'transaction',
                'page_title',
            ));
        }

        return abort(404);
    }

    public function cryptoPaymentConfirm(Request $request, $trx_id) 
    {
        $transaction = Transaction::where('trx_id',$trx_id)->where('status', global_const()::REMITTANCE_STATUS_REVIEW_PAYMENT)->firstOrFail();


        $dy_input_fields = $transaction->details->payment_info->requirements ?? [];
        $validation_rules = $this->generateValidationRules($dy_input_fields);

        $validated = [];
        if(count($validation_rules) > 0) {
            $validated = Validator::make($request->all(), $validation_rules)->validate();
        }

        if(!isset($validated['txn_hash'])) return back()->with(['error' => ['Transaction hash is required for verify']]);

        $receiver_address = $transaction->details->payment_info->receiver_address ?? "";

        // check hash is valid or not
        $crypto_transaction = CryptoTransaction::where('txn_hash', $validated['txn_hash'])
                                                ->where('receiver_address', $receiver_address)
                                                ->where('asset',$transaction->currency->currency_code)
                                                ->where(function($query) {
                                                    return $query->where('transaction_type',"Native")
                                                                ->orWhere('transaction_type', "native");
                                                })
                                                ->where('status',PaymentGatewayConst::NOT_USED)
                                                ->first();
                                                
        if(!$crypto_transaction) return back()->with(['error' => ['Transaction hash is not valid! Please input a valid hash']]);

        if($crypto_transaction->amount >= $transaction->total_payable == false) {
            if(!$crypto_transaction) return back()->with(['error' => ['Insufficient amount added. Please contact with system administrator']]);
        }

        DB::beginTransaction();
        try{

            // update crypto transaction as used
            DB::table($crypto_transaction->getTable())->where('id', $crypto_transaction->id)->update([
                'status'        => PaymentGatewayConst::USED,
            ]);

            // update transaction status
            $transaction_details = json_decode(json_encode($transaction->details), true);
            $transaction_details['payment_info']['txn_hash'] = $validated['txn_hash'];

            DB::table($transaction->getTable())->where('id', $transaction->id)->update([
                'details'       => json_encode($transaction_details),
                'status'        => global_const()::REMITTANCE_STATUS_CONFIRM_PAYMENT,
            ]);

            DB::commit();

        }catch(Exception $e) {
            DB::rollback();
            return back()->with(['error' => ['Something went wrong! Please try again']]);
        }

        return back()->with(['success' => ['Payment Confirmation Success!']]);
    }
}
