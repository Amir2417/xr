<?php

namespace App\Traits\PaymentGateway;

use Exception;
use App\Traits\Transaction;
use Illuminate\Support\Str;
use App\Models\TemporaryData;
use App\Models\UserNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Constants\PaymentGatewayConst;
use App\Models\AppliedCoupon;
use App\Notifications\paypalNotification;
use Illuminate\Support\Facades\Notification;
use App\Providers\Admin\BasicSettingsProvider;
use Srmklive\PayPal\Services\PayPal as PayPalClient;


trait Paypal
{
    use Transaction;
    public function paypalInit($output = null) {
        if(!$output) $output = $this->output;
        $credentials = $this->getPaypalCredetials($output);
        $config = $this->paypalConfig($credentials,$output['amount']);
        $paypalProvider = new PayPalClient;
        $paypalProvider->setApiCredentials($config);
        $paypalProvider->getAccessToken();
        
        $response = $paypalProvider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('user.send.remittance.payment.success',PaymentGatewayConst::PAYPAL),
                "cancel_url" => route('user.send.remittance.payment.cancel',PaymentGatewayConst::PAYPAL),
            ],
            "purchase_units" => [
                0 => [
                    "amount" => [
                        "currency_code" => $output['amount']->sender_cur_code ?? '',
                        "value" => $output['amount']->total_amount ? number_format($output['amount']->total_amount,2,'.','') : 0,
                    ]
                ]
            ]
        ]);

        if(isset($response['id']) && $response['id'] != "" && isset($response['status']) && $response['status'] == "CREATED" && isset($response['links']) && is_array($response['links'])) {
            foreach($response['links'] as $item) {
                if($item['rel'] == "approve") {
                    $this->paypalJunkInsert($response);
                    return redirect()->away($item['href']);
                    break;
                }
            }
        }

        if(isset($response['error']) && is_array($response['error'])) {
            throw new Exception($response['error']['message']);
        }

        throw new Exception("Something went worng! Please try again.");
    }
    // PayPal API Init
    public function paypalInitApi($output = null) {
        if(!$output) $output = $this->output;
        $credentials = $this->getPaypalCredetials($output);
        
        $config = $this->paypalConfig($credentials,$output['amount']);
        $paypalProvider = new PayPalClient;
        $paypalProvider->setApiCredentials($config);
        $paypalProvider->getAccessToken();

        $response = $paypalProvider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" =>route('api.user.send-remittance.payment.success',PaymentGatewayConst::PAYPAL."?r-source=".PaymentGatewayConst::APP),
                "cancel_url" =>route('api.user.send-remittance.payment.cancel',PaymentGatewayConst::PAYPAL."?r-source=".PaymentGatewayConst::APP),
            ],
            "purchase_units" => [
                0 => [
                    "amount" => [
                        "currency_code" => $output['amount']->sender_cur_code ?? '',
                        "value" => $output['amount']->total_amount ? number_format($output['amount']->total_amount,2,'.','') : 0,
                    ]
                ]
            ]
        ]);

        
        if(isset($response['id']) && $response['id'] != "" && isset($response['status']) && $response['status'] == "CREATED" && isset($response['links']) && is_array($response['links'])) {
            foreach($response['links'] as $item) {
                if($item['rel'] == "approve") {
                    $this->paypalJunkInsert($response);
                    return $response;
                    break;
                }
            }
        }

        if(isset($response['error']) && is_array($response['error'])) {
            throw new Exception($response['error']['message']);
        }

        throw new Exception("Something went worng! Please try again.");
    }

    // Paypal Credential
    public function getPaypalCredetials($output) {
        $gateway = $output['gateway'] ?? null;
        if(!$gateway) throw new Exception("Payment gateway not available");
        $client_id_sample = ['api key','api_key','client id','primary key'];
        $client_secret_sample = ['client_secret','client secret','secret','secret key','secret id'];

        $client_id = '';
        $outer_break = false;
        foreach($client_id_sample as $item) {
            if($outer_break == true) {
                break;
            }
            $modify_item = $this->paypalPlainText($item);
            foreach($gateway->credentials ?? [] as $gatewayInput) {
                $label = $gatewayInput->label ?? "";
                $label = $this->paypalPlainText($label);

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
            $modify_item = $this->paypalPlainText($item);
            foreach($gateway->credentials ?? [] as $gatewayInput) {
                $label = $gatewayInput->label ?? "";
                $label = $this->paypalPlainText($label);

                if($label == $modify_item) {
                    $secret_id = $gatewayInput->value ?? "";
                    $outer_break = true;
                    break;
                }
            }
        }

        return (object) [
            'client_id'     => $client_id,
            'client_secret' => $secret_id,
            'mode'          => "sandbox",
        ];

    }

    public function paypalPlainText($string) {
        $string = Str::lower($string);
        return preg_replace("/[^A-Za-z0-9]/","",$string);
    }


    public static function paypalConfig($credentials, $amount_info)
    {
        $config = [
            'mode'    => $credentials->mode ?? 'sandbox',
            'sandbox' => [
                'client_id'         => $credentials->client_id ?? "",
                'client_secret'     => $credentials->client_secret ?? "",
                'app_id'            => "APP-80W284485P519543T",
            ],
            'live' => [
                'client_id'         => $credentials->client_id ?? "",
                'client_secret'     => $credentials->client_secret ?? "",
                'app_id'            => "",
            ],
            'payment_action' => 'Sale', // Can only be 'Sale', 'Authorization' or 'Order'
            'currency'       => $amount_info->sender_cur_code ?? "",
            'notify_url'     => "", // Change this accordingly for your application.
            'locale'         => 'en_US', // force gateway language  i.e. it_IT, es_ES, en_US ... (for express checkout only)
            'validate_ssl'   => true, // Validate SSL when creating api client.
        ];
        return $config;
    }

    public function paypalJunkInsert($response) {
   
        $output = $this->output;

        $data = [
        
            'gateway'   => $output['gateway']->id,
            'currency'  => $output['currency']->id,
            'amount'    => json_decode(json_encode($output['amount']),true),
            'response'  => $response,
            'creator_table' => auth()->guard(get_auth_guard())->user()->getTable(),
            'creator_id'    => auth()->guard(get_auth_guard())->user()->id,
            'creator_guard' => get_auth_guard(),
            'user_record'     => $output['request_data']['identifier'],
        ];

        
        return TemporaryData::create([
            'user_id'       => Auth::id(),
            'type'          => PaymentGatewayConst::PAYPAL,
            'identifier'    => $response['id'],
            'data'          => $data,
        ]);
    }

    public function paypalSuccess($output = null) {
        if(!$output) $output = $this->output;
        $token = $this->output['tempData']['identifier'] ?? "";
        $user_data = TemporaryData::where('identifier',$output['tempData']['data']->user_record ?? "")->first();
        $this->output['user_data']  = $user_data;

        $credentials = $this->getPaypalCredetials($output);
        $config = $this->paypalConfig($credentials,$output['amount']);
        $paypalProvider = new PayPalClient;
        $paypalProvider->setApiCredentials($config);
        $paypalProvider->getAccessToken();
        $response = $paypalProvider->capturePaymentOrder($token);

        if(isset($response['status']) && $response['status'] == 'COMPLETED') {
            return $this->paypalPaymentCaptured($response,$output);
        }else {
            throw new Exception('Transaction faild. Payment captured faild.');
        }

        if(empty($token)) throw new Exception('Transaction faild. Record didn\'t saved properly. Please try again.');
    }

    public function paypalPaymentCaptured($response,$output) {
        // payment successfully captured record saved to database
        $output['capture'] = $response;
        try{
            $trx_id = generateTrxString('transactions', 'trx_id', 'R', 8);
            $basic_settings = BasicSettingsProvider::get();
            $user = auth()->user();
            
            Notification::route("mail",$user->email)->notify(new paypalNotification($user,$output,$trx_id));
            
            if(auth()->check()){
                UserNotification::create([
                    'user_id'  => auth()->user()->id,
                    'message'  => "Your Remittance  (Payable amount: ".get_amount($output['amount']->total_amount).",
                    Get Amount: ".get_amount($output['amount']->will_get).") Successfully Sended.", 
                ]);
            }
            
            $transaction_response = $this->createTransaction($output, $trx_id);

        }catch(Exception $e) {
            
            throw new Exception($e->getMessage());
        }

        return $transaction_response;
    }

    public function createTransaction($output, $trx_id) {
        
        $trx_id =  $trx_id;
        $inserted_id = $this->insertRecord($output, $trx_id);
        
        $this->removeTempData($output);
        if($this->requestIsApiUser()) {
            // logout user
            $api_user_login_guard = $this->output['api_login_guard'] ?? null;
            if($api_user_login_guard != null) {
                auth()->guard($api_user_login_guard)->logout();
            }
        }

        return $this->output['trx_id'] ?? "";

    }

    public function insertRecord($output, $trx_id) {
        $trx_id =  $trx_id;
       
        DB::beginTransaction();
       
        try{
            $id = DB::table("transactions")->insertGetId([
                'user_id'                       => auth()->user()->id,
                'payment_gateway_currency_id'   => $output['currency']->id,
                'type'                          => $output['type'],
                'remittance_data'               => json_encode([
                    'type'                      => $this->output['user_data']->type,
                    'sender_name'               => $this->output['user_data']->data->sender_name,
                    'sender_email'              => $this->output['user_data']->data->sender_email,
                    'sender_currency'           => $this->output['user_data']->data->sender_currency,
                    'receiver_currency'         => $this->output['user_data']->data->receiver_currency,
                    'sender_ex_rate'            => $this->output['user_data']->data->sender_ex_rate,
                    'sender_base_rate'          => $this->output['user_data']->data->sender_base_rate,
                    'receiver_ex_rate'          => $this->output['user_data']->data->receiver_ex_rate,
                    'coupon_id'                 => $this->output['user_data']->data->coupon_id,
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
                'payable'                       => $output['amount']->total_amount,
                'fees'                          => $output['amount']->total_charge,
                'convert_amount'                => $output['amount']->convert_amount,
                'will_get_amount'               => $output['amount']->will_get,
                'remark'                        => $output['gateway']->name,
                'details'                       => "COMPLETED",
                'status'                        => global_const()::REMITTANCE_STATUS_PENDING,
                'attribute'                     => PaymentGatewayConst::SEND,
                'created_at'                    => now(),
            ]);
            if($this->output['user_data']->data->coupon_id != 0){
                $user   = auth()->user();
                $user->update([
                    'coupon_status'     => 1,
                ]);
                
                AppliedCoupon::create([
                    'user_id'   => $user->id,
                    'coupon_id'   => $this->output['user_data']->data->coupon_id,
                    'transaction_id'   => $id,
                ]);
            }
            
            DB::commit();
            
        }catch(Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
        

        $this->output['trx_id'] = $trx_id;
        
        return $id;
    }

    public function removeTempData($output) {
        $token = $output['capture']['id'];
        TemporaryData::where("identifier",$token)->delete();
    }
}
