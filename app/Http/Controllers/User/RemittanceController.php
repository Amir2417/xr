<?php

namespace App\Http\Controllers\User;

use PDF;
use Exception;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\TemporaryData;
use App\Models\Admin\Currency;
use App\Models\Admin\SetupKyc;
use App\Models\UserNotification;
use App\Http\Controllers\Controller;
use App\Models\Admin\PaymentGateway;
use Illuminate\Support\Facades\Auth;
use App\Traits\PaymentGateway\Manual;
use App\Traits\PaymentGateway\Stripe;
use App\Constants\PaymentGatewayConst;
use Illuminate\Support\Facades\Session;
use App\Traits\PaymentGateway\RazorTrait;
use App\Providers\Admin\BasicSettingsProvider;
use App\Traits\PaymentGateway\SslcommerzTrait;
use App\Traits\PaymentGateway\FlutterwaveTrait;
use KingFlamez\Rave\Facades\Rave as Flutterwave;
use App\Http\Helpers\PaymentGateway as PaymentGatewayHelper;

class RemittanceController extends Controller
{
    use Stripe,Manual,FlutterwaveTrait,SslcommerzTrait,RazorTrait;

    /**
     * Method for submit money
     * @param Illuminate\Http\Request $request
     */
    public function submit(Request $request){
        try{
            $instance = PaymentGatewayHelper::init($request->all())->gateway()->render();
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
        $requestData = $request->all();
        $token = $requestData['token'] ?? "";
        $checkTempData = TemporaryData::where("type",$gateway)->where("identifier",$token)->first();
        if(!$checkTempData) return redirect()->route('user.send.remittance.index')->with(['error' => ['Transaction faild. Record didn\'t saved properly. Please try again.']]);
        $checkTempData = $checkTempData->toArray();
        try{
            
            $transaction = PaymentGatewayHelper::init($checkTempData)->type(PaymentGatewayConst::TYPESENDREMITTANCE)->responseReceive();
        }catch(Exception $e) {
            return back()->with(['error' => [$e->getMessage()]]);
        }
        
        return redirect()->route("user.payment.confirmation",$transaction)->with(['success' => ['Successfully Send Remittance']]);
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
     * This method for cancel alert of PayPal
     * @method POST
     * @param Illuminate\Http\Request $request
     * @return Illuminate\Http\Request
     */
    public function cancel(Request $request, $gateway) {
        $token = session()->get('identifier');
        if( $token){
            TemporaryData::where("identifier",$token)->delete();
        }

        return redirect()->route('user.send.remittance.index');
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
    //flutterwave callback
    public function flutterwaveCallback(){

        $status = request()->status;
        //if payment is successful
        if ($status ==  'successful') {

            $transactionID = Flutterwave::getTransactionIDFromCallback();
            $data = Flutterwave::verifyTransaction($transactionID);

            $requestData = request()->tx_ref;
            $token = $requestData;

            $checkTempData = TemporaryData::where("type",'flutterwave')->where("identifier",$token)->first();

            if(!$checkTempData) return redirect()->route('user.send.remittance.index')->with(['error' => ['Transaction faild. Record didn\'t saved properly. Please try again.']]);

            $checkTempData = $checkTempData->toArray();

            try{
                
                $transaction = PaymentGatewayHelper::init($checkTempData)->type(PaymentGatewayConst::TYPESENDREMITTANCE)->responseReceive();
            }catch(Exception $e) {
                return back()->with(['error' => [$e->getMessage()]]);
            }
            return redirect()->route("user.payment.confirmation",$transaction)->with(['success' => ['Successfully send remittance']]);

        }
        elseif ($status ==  'cancelled'){
            return redirect()->route('user.send.remittance.index')->with(['error' => ['Send Remittance cancelled']]);
        }
        else{
            return redirect()->route('user.send.remittance.index')->with(['error' => ['Transaction failed']]);
        }
    }


    /**
     * This method for manual payment
     * @method GET
     */
    public function manualPayment(){
        $tempData       = Session::get('identifier');
        $hasData        = TemporaryData::where('identifier', $tempData)->first();
        $gateway        = PaymentGateway::manual()->where('slug',PaymentGatewayConst::remittance_money_slug())->where('id',$hasData->data->gateway)->first();
        
        $page_title     = "Manual Payment".' ( '.$gateway->name.' )';
        $client_ip      = request()->ip() ?? false;
        $user_country   = geoip()->getLocation($client_ip)['country'] ?? "";
        $kyc_data       = SetupKyc::userKyc()->first();
        $user           = auth()->user();
        $notifications  = UserNotification::where('user_id',$user->id)->latest()->take(10)->get();
        if(!$hasData){
            return redirect()->route('user.send.remittance.index');
        }
        return view('user.sections.money-transfer.manual.payment_confirmation',compact(
            "page_title",
            "hasData",
            'gateway',
            'user_country',
            'user',
            'notifications'
        ));
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
