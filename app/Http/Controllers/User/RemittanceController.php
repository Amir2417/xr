<?php

namespace App\Http\Controllers\User;

use PDF;
use Exception;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\AppliedCoupon;
use App\Models\TemporaryData;
use App\Models\Admin\Currency;
use App\Models\Admin\SetupKyc;
use App\Models\UserNotification;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\BasicSettings;
use App\Http\Controllers\Controller;
use App\Models\Admin\PaymentGateway;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Constants\PaymentGatewayConst;
use Illuminate\Support\Facades\Session;
use App\Notifications\paypalNotification;
use App\Traits\ControlDynamicInputFields;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\PaymentGatewayCurrency;
use Illuminate\Support\Facades\Notification;
use App\Providers\Admin\BasicSettingsProvider;
use App\Http\Helpers\PaymentGateway as PaymentGatewayHelper;

class RemittanceController extends Controller
{
   
    use ControlDynamicInputFields;
    
    /**
     * Method for buy crypto submit
     * @param Illuminate\Http\Request $request
     */
    public function submit(Request $request){
       
        try{
            $instance = PaymentGatewayHelper::init($request->all())->gateway()->render();
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
            $token = PaymentGatewayHelper::getToken($request->all(),$gateway);
            $temp_data = TemporaryData::where("identifier",$token)->first();
            

            if(Transaction::where('callback_ref', $token)->exists()) {
                if(!$temp_data) return redirect()->route('user.send.remittance.index')->with(['success' => ['Transaction request sended successfully!']]);;
            }else {
                if(!$temp_data) return redirect()->route('user.send.remittance.index')->with(['error' => ['Transaction failed. Record didn\'t saved properly. Please try again.']]);
            }

            $update_temp_data = json_decode(json_encode($temp_data->data),true);
            
            $update_temp_data['callback_data']  = $request->all();

            $temp_data->update([
                'data'  => $update_temp_data,
            ]);
            $temp_data = $temp_data->toArray();
            
            $instance = PaymentGatewayHelper::init($temp_data)->type(PaymentGatewayConst::TYPESENDREMITTANCE)->responseReceive();
            
            if($instance instanceof RedirectResponse) return $instance;
        }catch(Exception $e) {
            
            return back()->with(['error' => [$e->getMessage()]]);
        }
        return redirect()->route("user.payment.confirmation",$instance)->with(['success' => ['Successfully Send Remittance']]);
    }
    public function cancel(Request $request, $gateway) {
        if($request->has('token')) {
            $identifier = $request->token;
            if($temp_data = TemporaryData::where('identifier', $identifier)->first()) {
                $temp_data->delete();
            }
        }
        return redirect()->route('user.send.remittance.index');
    }
    public function callback(Request $request,$gateway) {

        $callback_token = $request->get('token');
        $callback_data = $request->all();

        try{
            PaymentGatewayHelper::init([])->type(PaymentGatewayConst::TYPESENDREMITTANCE)->handleCallback($callback_token,$callback_data,$gateway);
        }catch(Exception $e) {
            // handle Error
            logger($e);
        }
    }
    public function stripePaymentSuccess($trx){
        
        $token = $trx;
        $checkTempData = TemporaryData::where("type",PaymentGatewayConst::STRIPE)->where("identifier",$token)->first();
        if(!$checkTempData) return redirect()->route('user.send.remittance.index')->with(['error' => ['Transaction Failed. Record didn\'t saved properly. Please try again.']]);
        $checkTempData = $checkTempData->toArray();

        try{
            $transaction = PaymentGatewayHelper::init($checkTempData)->type(PaymentGatewayConst::TYPESENDREMITTANCE)->responseReceive(); 
            
        }catch(Exception $e) {
            
            return back()->with(['error' => ["Something Is Wrong..."]]);
        }
        return redirect()->route("user.payment.confirmation",$transaction)->with(['success' => ['Successfully send remittance']]);
    }
    
    /**
     * This method for stripe payment
     * @method GET
     * @param $gateway
     */
    public function payment(Request $request,$gateway){
        $page_title = "Stripe Payment";
        $client_ip = request()->ip() ?? false;
        $user_country = geoip()->getLocation($client_ip)['country'] ?? "";
        $user         = auth()->user();
        $notifications = UserNotification::where('user_id',$user->id)->latest()->take(10)->get();
        $tempData = Session::get('identifier');
        
        $hasData = TemporaryData::where('identifier', $tempData)->where('type',$gateway)->first();
        
        if(!$hasData){
            return redirect()->route('user.send.remittance.index');
        }
        return view('user.sections.money-transfer.automatic.'.$gateway,compact(
            "page_title",
            "hasData",
            'user_country',
            'user',
            'notifications'
        ));
    }
    
    public function handleManualPayment($payment_info) {

        
        // Insert temp data
        $data = [
            'type'          => PaymentGatewayConst::TYPESENDREMITTANCE,
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
            return redirect()->route('user.send.remittance.index')->with(['error' => ['Failed to save data. Please try again']]);
        }
        return redirect()->route('user.send.remittance.manual.form',$data['identifier']);
    }

    

    public function showManualForm($token) {
        
        $tempData = TemporaryData::search($token)->first();
        if(!$tempData || $tempData->data == null || !isset($tempData->data->gateway_currency_id)) return redirect()->route('user.send.remittance.index')->with(['error' => ['Invalid request']]);
        $gateway_currency = PaymentGatewayCurrency::find($tempData->data->gateway_currency_id);
        if(!$gateway_currency || !$gateway_currency->gateway->isManual()) return redirect()->route('user.send.remittance.index')->with(['error' => ['Selected gateway is invalid']]);
        $gateway = $gateway_currency->gateway;
        if(!$gateway->input_fields || !is_array($gateway->input_fields)) return redirect()->route('user.send.remittance.index')->with(['error' => ['This payment gateway is under constructions. Please try with another payment gateway']]);
        $amount = $tempData->data->amount;

        $page_title = "- Payment Instructions";
        $client_ip      = request()->ip() ?? false;
        $user_country   = geoip()->getLocation($client_ip)['country'] ?? "";
        $kyc_data       = SetupKyc::userKyc()->first();
        $user           = auth()->user();
        $notifications  = UserNotification::where('user_id',$user->id)->latest()->take(10)->get();

        return view('user.sections.money-transfer.manual.payment_confirmation',compact("gateway","page_title","token","amount",'user_country',
        'user',
        'notifications'));
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
                $user   = auth()->user();
                $user->update([
                    'coupon_status'     => 1,
                ]);
                
                AppliedCoupon::create([
                    'user_id'   => $user->id,
                    'coupon_id'   => $data->data->coupon_id,
                    'transaction_id'   => $id,
                ]);
            }
            if( $basic_setting->email_notification == true){
                Notification::route("mail",$user->email)->notify(new paypalNotification($user,$data,$trx_id));
            }
            DB::table("temporary_datas")->where("identifier",$token)->delete();
            DB::commit();
        }catch(Exception $e) {
            DB::rollBack();
            return redirect()->route('user.send.remittance.manual.form',$token)->with(['error' => ['Something went wrong! Please try again']]);
        }
        return redirect()->route("user.payment.confirmation",$trx_id)->with(['success' => ['Successfully send remittance']]);
    }
    //sslcommerz success
    public function sllCommerzSuccess(Request $request){
        
        $data           = $request->all();
        $token          = $data['tran_id'];
        $checkTempData  = TemporaryData::where("type",PaymentGatewayConst::SSLCOMMERZ)->where("identifier",$token)->first();
        
        if(!$checkTempData) return redirect()->route('user.send.remittance.index')->with(['error' => ['Transaction Failed. Record didn\'t saved properly. Please try again.']]);
        $checkTempData  = $checkTempData->toArray();
        $creator_id     = $checkTempData['data']->creator_id ?? null;
        $creator_guard  = $checkTempData['data']->creator_guard ?? null;

        $user = Auth::guard($creator_guard)->loginUsingId($creator_id);
        if( $data['status'] != "VALID"){
            return redirect()->route("user.send.remittance.index")->with(['error' => ['Send Remittance Failed']]);
        }
        try{
            $transaction= PaymentGatewayHelper::init($checkTempData)->type(PaymentGatewayConst::TYPESENDREMITTANCE)->responseReceive();
            
        }catch(Exception $e) {
            
            return back()->with(['error' => ["Something Is Wrong..."]]);
        }
        return redirect()->route("user.payment.confirmation",$transaction)->with(['success' => ['Successfully Send Remittance']]);
    }
    //sslCommerz fails
    public function sllCommerzFails(Request $request){
        $data = $request->all();
        $token = $data['tran_id'];
        $checkTempData = TemporaryData::where("type",PaymentGatewayConst::SSLCOMMERZ)->where("identifier",$token)->first();
        if(!$checkTempData) return redirect()->route('user.send.remittance.index')->with(['error' => ['Transaction Failed. Record didn\'t saved properly. Please try again.']]);
        $checkTempData = $checkTempData->toArray();
        $creator_id = $checkTempData['data']->creator_id ?? null;
        $creator_guard = $checkTempData['data']->creator_guard ?? null;
        $user = Auth::guard($creator_guard)->loginUsingId($creator_id);
        if( $data['status'] == "FAILED"){
            TemporaryData::destroy($checkTempData['id']);
            return redirect()->route("user.send.remittance.index")->with(['error' => ['Send Remittance Failed']]);
        }

    }
    //sslCommerz canceled
    public function sllCommerzCancel(Request $request){
        $data           = $request->all();
        $token          = $data['tran_id'];
        $checkTempData  = TemporaryData::where("type",PaymentGatewayConst::SSLCOMMERZ)->where("identifier",$token)->first();
        if(!$checkTempData) return redirect()->route('user.send.remittance.index')->with(['error' => ['Transaction Failed. Record didn\'t saved properly. Please try again.']]);
        $checkTempData  = $checkTempData->toArray();
        $creator_id     = $checkTempData['data']->creator_id ?? null;
        $creator_guard  = $checkTempData['data']->creator_guard ?? null;
        $user           = Auth::guard($creator_guard)->loginUsingId($creator_id);
        if( $data['status'] != "VALID"){
            TemporaryData::destroy($checkTempData['id']);
            return redirect()->route("user.send.remittance.index")->with(['error' => ['Send Remittance Canceled']]);
        }

    }
    public function paymentConfirmation(Request $request,$trx_id){
        $page_title    = "| Payment Confirmation";
        $client_ip     = request()->ip() ?? false;
        $user_country  = geoip()->getLocation($client_ip)['country'] ?? "";
        $kyc_data      = SetupKyc::userKyc()->first();
        $user          = auth()->user();
        $notifications = UserNotification::where('user_id',$user->id)->latest()->take(10)->get();
        $transaction   = Transaction::where('trx_id',$trx_id)->first();
        return view('user.sections.payment-confirmation.index',compact(
            'page_title',
            'transaction',
            'user_country',
            'user',
            'notifications'
        ));
    }

    /**
     * razor pay payment gateway callback
     */
    public function razorCallback(){
        $request_data = request()->all();
        //if payment is successful
        if ($request_data['razorpay_payment_link_status'] ==  'paid') {
            $token = $request_data['razorpay_payment_link_reference_id'];

            $checkTempData = TemporaryData::where("type",PaymentGatewayConst::RAZORPAY)->where("identifier",$token)->first();
            if(!$checkTempData) return redirect()->route('user.send.remittance.index')->with(['error' => ['Transaction Failed. Record didn\'t saved properly. Please try again.']]);
            $checkTempData = $checkTempData->toArray();
            try{
                $transaction = PaymentGatewayHelper::init($checkTempData)->type(PaymentGatewayConst::TYPESENDREMITTANCE)->responseReceive();
            }catch(Exception $e) {
                return back()->with(['error' => [$e->getMessage()]]);
            }
            return redirect()->route("user.payment.confirmation",$transaction)->with(['success' => ['Successfully Send Remittance']]);

        }
        else{
            return redirect()->route('user.send.remittance.index')->with(['error' => ['Transaction failed']]);
        }
    }
    /**
     * Method for share link page
     * @param string $trx_id
     * @param \Illuminate\Http\Request $request
     */
    public function shareLink(Request $request,$trx_id){
        $page_title         = "| Information";
        $transaction        = Transaction::where('trx_id',$trx_id)->first();
        $sender_currency    = Currency::where('status',true)->where('sender',true)->first();
        $receiver_currency  = Currency::where('status',true)->where('receiver',true)->first();

        return view('share-link.index',compact(
            'page_title',
            'transaction',
            'sender_currency',
            'receiver_currency',
        ));   
    }

    public function downloadPdf($trx_id)
    {
        $transaction             = Transaction::where('trx_id',$trx_id)->first(); 
        $sender_currency         = Currency::where('status',true)->where('sender',true)->first();
        $receiver_currency       = Currency::where('status',true)->where('receiver',true)->first();

        $data   = [
            'transaction'        => $transaction,
            'sender_currency'    => $sender_currency,
            'receiver_currency'  => $receiver_currency,
        ];
        
        $pdf = PDF::loadView('pdf-templates.index', $data);
        
        $basic_settings = BasicSettingsProvider::get();
        
        return $pdf->download($basic_settings->site_name.'-'.$transaction->trx_id.'.pdf');
    }
   
}
