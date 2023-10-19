<?php

namespace App\Traits\PaymentGateway;

use Exception;
use Stripe\Token;
use Stripe\Charge;
use App\Traits\Transaction;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use App\Models\TemporaryData;
use App\Http\Helpers\Response;
use Illuminate\Support\Carbon;
use App\Models\UserNotification;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\BasicSettings;
use Stripe\Stripe as StripePackage;
use Illuminate\Support\Facades\Auth;
use App\Constants\PaymentGatewayConst;
use App\Notifications\sendNotification;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\PaymentGatewayCurrency;
use Illuminate\Support\Facades\Notification;
use App\Providers\Admin\BasicSettingsProvider;
use App\Http\Helpers\PaymentGateway as PaymentGatewayHelper;

trait Stripe
{
    use Transaction;

    public function stripeInit($output = null) {

        $basic_settings = BasicSettingsProvider::get();
        if(!$output) $output = $this->output;
        $gatewayAlias = $output['gateway']['alias'];
        $credentials = $this->getStripeCredentials($output);
        $reference = generateTransactionReference();
        $amount = $output['amount']->total_amount ? number_format($output['amount']->total_amount,2,'.','') : 0;
        $currency = $output['currency']['currency_code']??"USD";

        if(auth()->guard(get_auth_guard())->check()){
            $user = auth()->guard(get_auth_guard())->user();
            $user_email = $user->email;
            $user_phone = $user->full_mobile ?? '';
            $user_name = $user->firstname.' '.$user->lastname ?? '';
        }
        $return_url = route('user.send.remittance.stripe.payment.success', $reference);
        
        

        // Enter the details of the payment
        $data = [
            'payment_options' => 'card',
            'amount'          => $amount,
            'email'           => $user_email,
            'tx_ref'          => $reference,
            'currency'        =>  $currency,
            'redirect_url'    => $return_url,
            'customer'        => [
                'email'        => $user_email,
                "phone_number" => $user_phone,
                "name"         => $user_name
            ],
            "customizations" => [
                "title"       => "Send Remittance",
                "description" => dateFormat('d M Y', Carbon::now()),
            ]
        ];
        
       //start stripe pay link
       $stripe = new \Stripe\StripeClient($credentials->secret_key);

       //create product for Product Id
       try{
            $product_id = $stripe->products->create([
                'name' => 'Send Remittance( '.$basic_settings->site_name.' )',
            ]);
       }catch(Exception $e){
            throw new Exception($e->getMessage());
       }
       
       //create price for Price Id
       try{
            $price_id =$stripe->prices->create([
                'currency' =>  $currency,
                'unit_amount' => $amount*100,
                'product' => $product_id->id??""
              ]);
       }catch(Exception $e){
            throw new Exception("Something Is Wrong, Please Contact With Owner");
       }
       //create payment live links
        try{
            $payment_link = $stripe->paymentLinks->create([
                'line_items' => [
                [
                    'price' => $price_id->id,
                    'quantity' => 1,
                ],
                ],
                'after_completion' => [
                'type' => 'redirect',
                'redirect' => ['url' => $return_url],
                ],


            ]);
        }catch(Exception $e){
            throw new Exception("Something Is Wrong, Please Contact With Owner");
        }
        $this->stripeJunkInsert($data);
        
        return redirect($payment_link->url."?prefilled_email=".@$user->email);

    }

    public function getStripeCredentials($output) {
        $gateway = $output['gateway'] ?? null;
        if(!$gateway) throw new Exception("Payment gateway not available");
        $client_id_sample = ['publishable_key','publishable key','publishable-key'];
        $client_secret_sample = ['secret id','secret-id','secret_id'];

        $client_id = '';
        $outer_break = false;
        foreach($client_id_sample as $item) {
            if($outer_break == true) {
                break;
            }
            $modify_item = $this->stripePlainText($item);
            foreach($gateway->credentials ?? [] as $gatewayInput) {
                $label = $gatewayInput->label ?? "";
                $label = $this->stripePlainText($label);

                if($label == $modify_item) {
                    $client_id = $gatewayInput->value ?? "";
                    $outer_break = true;
                    break;
                }
            }
        }


        $secret_id = '';
        $outer_break = false;
        foreach($client_secret_sample as $item) {
            if($outer_break == true) {
                break;
            }
            $modify_item = $this->stripePlainText($item);
            foreach($gateway->credentials ?? [] as $gatewayInput) {
                $label = $gatewayInput->label ?? "";
                $label = $this->stripePlainText($label);

                if($label == $modify_item) {
                    $secret_id = $gatewayInput->value ?? "";
                    $outer_break = true;
                    break;
                }
            }
        }

        return (object) [
            'publish_key'     => $client_id,
            'secret_key' => $secret_id,

        ];

    }

    public function stripePlainText($string) {
        $string = Str::lower($string);
        return preg_replace("/[^A-Za-z0-9]/","",$string);
    }

    public function stripeJunkInsert($response) {
        $output = $this->output;
        $user = auth()->guard(get_auth_guard())->user();
        $creator_table = $creator_id = $wallet_table = $wallet_id = null;

        $creator_table = auth()->guard(get_auth_guard())->user()->getTable();
        $creator_id = auth()->guard(get_auth_guard())->user()->id;

        $data = [
            'gateway'   => $output['gateway']->id,
            'currency'  => $output['currency']->id,
            'amount'    => json_decode(json_encode($output['amount']),true),
            'response'  => $response,
            'user_record' => $output['request_data']['identifier'],
            'creator_table' => $creator_table,
            'creator_id'    => $creator_id,
            'creator_guard' => get_auth_guard(),
        ];
        
        return TemporaryData::create([
            'user_id'       => Auth::user()->id,
            'type'          => PaymentGatewayConst::STRIPE,
            'identifier'    => $response['tx_ref'],
            'data'          => $data,

        ]);
    }
    public function stripeSuccess($output = null) {
        if(!$output) $output = $this->output;
        
        $token = $this->output['tempData']['identifier'] ?? "";
        if(empty($token)) throw new Exception('Transaction failed. Record didn\'t saved properly. Please try again.');
        
        return $this->createTransactionStripe($output);
        
    }

    public function createTransactionStripe($output) {
        $basic_setting = BasicSettings::first();
        $user = auth()->user();
        $trx_id = 'R'.getTrxNum();
        $inserted_id = $this->insertRecordStripe($output,$trx_id);
        $this->removeTempDataStripe($output);

        if($this->requestIsApiUser()) {
            // logout user
            $api_user_login_guard = $this->output['api_login_guard'] ?? null;
            if($api_user_login_guard != null) {
                auth()->guard($api_user_login_guard)->logout();
            }
        }
        if(auth()->check()){
            UserNotification::create([
                'user_id'  => auth()->user()->id,
                'message'  => "Your Remittance  (Payable amount: ".get_amount($output['amount']->total_amount + $output['amount']->total_charge).",
                Get Amount: ".get_amount($output['amount']->will_get).") Successfully Sended.", 
            ]);
        }
        if( $basic_setting->email_notification == true){
            Notification::route("mail",$user->email)->notify(new sendNotification($user,$output,$trx_id));
        }
        return $this->output['trx_id'] ?? "";
    }

    public function insertRecordStripe($output, $trx_id) {

        $trx_id = $trx_id;
        $token = $this->output['tempData']['identifier'] ?? "";
        
        $user_data = TemporaryData::where('identifier',$output['request_data']['identifier'] )->first();
        $this->output['user_data']  = $user_data;

 
        DB::beginTransaction();
        
            try{
                $id = DB::table("transactions")->insertGetId([
                    'user_id'                       => auth()->user()->id,
                    'payment_gateway_currency_id'   => $output['currency']->id,
                    'type'                          => PaymentGatewayConst::TYPESENDREMITTANCE,
                    'remittance_data'               => json_encode([
                        'type'                      => $this->output['user_data']->type,
                        'sender_name'               => $this->output['user_data']->data->sender_name,
                        'sender_email'              => $this->output['user_data']->data->sender_email,
                        'sender_currency'           => $this->output['user_data']->data->sender_currency,
                        'receiver_currency'         => $this->output['user_data']->data->receiver_currency,
                        'sender_ex_rate'            => $this->output['user_data']->data->sender_ex_rate,
                        'sender_base_rate'          => $this->output['user_data']->data->sender_base_rate,
                        'receiver_ex_rate'          => $this->output['user_data']->data->receiver_ex_rate,
                        'first_name'                => $this->output['user_data']->data->first_name,
                        'middle_name'               => $this->output['user_data']->data->middle_name,
                        'last_name'                 => $this->output['user_data']->data->last_name,
                        'email'                     => $this->output['user_data']->data->email,
                        'country'                   => $this->output['user_data']->data->country,
                        'city'                      => $this->output['user_data']->data->city,
                        'state'                     => $this->output['user_data']->data->state,
                        'zip_code'                  => $this->output['user_data']->data->zip_code,
                        'phone'                     => $this->output['user_data']->data->phone,
                        'method_name'               => $this->output['user_data']->data->method_name,
                        'account_number'            => $this->output['user_data']->data->account_number,
                        'address'                   => $this->output['user_data']->data->address,
                        'document_type'             => $this->output['user_data']->data->document_type,
                        'front_image'               => $this->output['user_data']->data->front_image,
                        'back_image'                => $this->output['user_data']->data->back_image,
                        'sending_purpose'           => $this->output['user_data']->data->sending_purpose->name,
                        'source'                    => $this->output['user_data']->data->source->name,
                        'currency'                  => [
                            'name'                  => $this->output['user_data']->data->currency->name,
                            'code'                  => $this->output['user_data']->data->currency->code,
                            'rate'                  => $this->output['user_data']->data->currency->rate,
                        ],
                        'send_money'                => $this->output['user_data']->data->send_money,
                        'fees'                      => $this->output['user_data']->data->fees,
                        'convert_amount'            => $this->output['user_data']->data->convert_amount,
                        'payable_amount'            => $this->output['user_data']->data->payable_amount,
                        'remark'                    => $this->output['user_data']->data->remark,
                    ]),
                    'trx_id'                        => $trx_id,
                    'request_amount'                => $this->output['user_data']->data->send_money,
                    'exchange_rate'                 => $output['amount']->sender_cur_rate,
                    'payable'                       => ($this->output['user_data']->data->send_money + $output['amount']->total_charge ) * $this->output['user_data']->data->currency->rate,
                    'fees'                          => $output['amount']->total_charge,
                    'convert_amount'                => $output['amount']->convert_amount,
                    'will_get_amount'               => $output['amount']->will_get,
                    
                    'remark'                        => $output['gateway']->name,
                    'details'                       => "COMPLETED",
                    'status'                        => global_const()::REMITTANCE_STATUS_PENDING,
                    'attribute'                     => PaymentGatewayConst::SEND,
                    'created_at'                    => now(),
                ]);
    
               
                DB::commit();
            }catch(Exception $e) {
                DB::rollBack();
                throw new Exception($e->getMessage());
            }
        
        $this->output['trx_id'] = $trx_id;
        return $id;
    }

    public function removeTempDataStripe($output) {
        $token = session()->get('identifier');
        TemporaryData::where("identifier",$token)->delete();
    }
    // for api
    public function stripeInitApi($output = null) {
        if(!$output) $output = $this->output;
        $gatewayAlias = $output['gateway']['alias'];
        $identifier = generate_unique_string("transactions","trx_id",16);
        $this->stripeJunkInsert($identifier);
        $response=[
            'trx' => $identifier,
        ];
        return $response;
    }

    public function paymentConfirmedApi(Request $request){

         $validator = Validator::make($request->all(), [
            'track'      => 'required',
            'name'       => 'required',
            'cardNumber' => 'required',
            'cardExpiry' => 'required',
            'cardCVC'    => 'required',
        ]);
        if($validator->fails()){
            $error =  ['error'=>$validator->errors()->all()];
            return Response::validation($error);
        }
        $track = $request->track;
       
        $data = TemporaryData::where('identifier',$track)->first();
        
        if(!$data){
            
            return Response::error(["Sorry, your payment information is invalid"],[]);
        }
        $payment_gateway_currency = PaymentGatewayCurrency::where('id', $data->data->currency)->first();
        $convert_amount = $data->data->amount->convert_amount;
        $total_charge   = $data->data->amount->total_charge;
       
        $gateway_request = ['currency' => $payment_gateway_currency->alias, 'amount'    => $data->data->amount->requested_amount,'fees' => $total_charge,'convert_amount' => $convert_amount, 'receive_money' => $data->data->amount->will_get,'identifier' => $data->data->user_data];
        
        $output = PaymentGatewayHelper::init($gateway_request)->gateway()->get();
        
        $credentials = $this->getStripeCredetials($output);
        $cc = $request->cardNumber;
        $exp = $request->cardExpiry;
        $cvc = $request->cardCVC;

        $exp = explode("/", $request->cardExpiry);
        $emo = trim($exp[0]);
        $eyr = trim($exp[1]);
        $cnts = round($data->data->amount->total_amount, 2) * 100;

        StripePackage::setApiKey(@$credentials->secret_key);
        StripePackage::setApiVersion("2020-03-02");

        try {
            
            $token = Token::create(array(
                    "card" => array(
                    "number" => "$cc",
                    "exp_month" => $emo,
                    "exp_year" => $eyr,
                    "cvc" => "$cvc"
                )
            ));
            try {
                
                $charge = Charge::create(array(
                    'card' => $token['id'],
                    'currency' => $data->data->amount->sender_cur_code,
                    'amount' => $cnts,
                    'description' => 'item',
                ));
                
                if ($charge['status'] == 'succeeded') {
                    
                    $trx_id = $trx_id = generateTrxString('transactions', 'trx_id', 'R', 8);
                    $this->createTransactionStripe($output,$trx_id);
                    $user = auth()->user();
                    Notification::route("mail",$user->email)->notify(new sendNotification($user,$output,$trx_id));
                
                if(auth()->check()){
                    UserNotification::create([
                        'user_id'  => auth()->user()->id,
                        'message'  => "Your Remittance  (Payable amount: ".get_amount($output['amount']->total_amount + $output['amount']->total_charge).",
                        Get Amount: ".get_amount($output['amount']->will_get).") Successfully Sended.", 
                    ]);
                }
                $share_link   = route('share.link',$trx_id);
                $download_link   = route('download.pdf',$trx_id);
                $data->delete();
                return Response::success(['Send Money Successfull'],[
                    'share-link'   => $share_link,
                    'download_link' => $download_link,
                ],200);
                }
            } catch (\Exception $e) {
            $error = ['error'=>[$e->getMessage()]];
            return Response::error($error);

            }
        } catch (\Exception $e) {
        $error = ['error'=>[$e->getMessage()]];
        return Response::error($error);
        }


    }

}
