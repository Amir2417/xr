<?php

namespace App\Traits\PaymentGateway;

use Exception;
use App\Traits\Transaction;
use Illuminate\Support\Str;
use App\Models\AppliedCoupon;
use App\Models\TemporaryData;
use Illuminate\Support\Carbon;
use App\Models\UserNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Constants\PaymentGatewayConst;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Notification;
use App\Notifications\flutterwaveNotification;
use KingFlamez\Rave\Facades\Rave as Flutterwave;

trait FlutterwaveTrait
{
    use Transaction;

    public function flutterwaveInit($output = null) {
        if(!$output) $output = $this->output;
        
        $credentials = $this->getFlutterCredentials($output);

        $this->flutterwaveSetSecreteKey($credentials);

        //This generates a payment reference
        $reference = Flutterwave::generateReference();

        $amount = $output['amount']->total_amount ? number_format($output['amount']->total_amount,2,'.','') : 0;

        if(auth()->guard(get_auth_guard())->check()){
            $user = auth()->guard(get_auth_guard())->user();
            $user_email = $user->email;
            $user_phone = $user->full_mobile ?? '';
            $user_name = $user->firstname.' '.$user->lastname ?? '';
        }
        $return_url = route('user.send.remittance.flutterwave.callback');

        // Enter the details of the payment
        $data = [
            'payment_options' => 'card,banktransfer',
            'amount'          => $amount,
            'email'           => $user_email,
            'tx_ref'          => $reference,
            'currency'        => $output['currency']['currency_code']??"NGN",
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

        $payment = Flutterwave::initializePayment($data);
        if( $payment['status'] == "error"){
            throw new Exception($payment['message']);
        };

        $this->flutterWaveJunkInsert($data);

        if ($payment['status'] !== 'success') {
            return;
        }

        return redirect($payment['data']['link']);
    }


    public function flutterWaveJunkInsert($response) {
        $output = $this->output;
        $user = auth()->guard(get_auth_guard())->user();
      
        $creator_table = auth()->guard(get_auth_guard())->user()->getTable();
        $creator_id = auth()->guard(get_auth_guard())->user()->id;
        
            $data = [
                'gateway'      => $output['gateway']->id,
                'currency'     => $output['currency']->id,
                'amount'       => json_decode(json_encode($output['amount']),true),
                'response'     => $response,
                
                'creator_table' => $creator_table,
                'creator_id'    => $creator_id,
                'creator_guard' => get_auth_guard(),
                'user_record'     => $output['request_data']['identifier'],
            ];

           
        Session::put('identifier',$response['tx_ref']);
        Session::put('output',$output);

        return TemporaryData::create([
            'user_id'    => Auth::id(),
            'type'       => PaymentGatewayConst::FLUTTER_WAVE,
            'identifier' => $response['tx_ref'],
            'data'       => $data,
        ]);
    }



    // Get Flutter wave credentials
    public function getFlutterCredentials($output) {
        
        $gateway = $output['gateway'] ?? null;
        if(!$gateway) throw new Exception("Payment gateway not available");

        $public_key_sample = ['api key','api_key','client id','primary key', 'public key'];
        $secret_key_sample = ['client_secret','client secret','secret','secret key','secret id'];
        $encryption_key_sample = ['encryption_key','encryption secret','secret hash', 'encryption id'];

        $public_key = '';
        $outer_break = false;

        foreach($public_key_sample as $item) {
            if($outer_break == true) {
                break;
            }
            $modify_item = $this->flutterwavePlainText($item);
            foreach($gateway->credentials ?? [] as $gatewayInput) {
                $label = $gatewayInput->label ?? "";
                $label = $this->flutterwavePlainText($label);
                if($label == $modify_item) {
                    $public_key = $gatewayInput->value ?? "";
                    $outer_break = true;
                    break;
                }
            }
        }

        $secret_key = '';
        $outer_break = false;
        foreach($secret_key_sample as $item) {
            if($outer_break == true) {
                break;
            }
            $modify_item = $this->flutterwavePlainText($item);
            foreach($gateway->credentials ?? [] as $gatewayInput) {
                $label = $gatewayInput->label ?? "";
                $label = $this->flutterwavePlainText($label);

                if($label == $modify_item) {
                    $secret_key = $gatewayInput->value ?? "";
                    $outer_break = true;
                    break;
                }
            }
        }

        $encryption_key = '';
        $outer_break = false;
        foreach($encryption_key_sample as $item) {
            if($outer_break == true) {
                break;
            }
            $modify_item = $this->flutterwavePlainText($item);
            foreach($gateway->credentials ?? [] as $gatewayInput) {
                $label = $gatewayInput->label ?? "";
                $label = $this->flutterwavePlainText($label);

                if($label == $modify_item) {
                    $encryption_key = $gatewayInput->value ?? "";
                    $outer_break = true;
                    break;
                }
            }
        }

        return (object) [
            'public_key'     => $public_key,
            'secret_key'     => $secret_key,
            'encryption_key' => $encryption_key,
        ];

    }

    public function flutterwavePlainText($string) {
        $string = Str::lower($string);
        return preg_replace("/[^A-Za-z0-9]/","",$string);
    }

    public function flutterwaveSetSecreteKey($credentials){
        Config::set('flutterwave.secretKey',$credentials->secret_key);
        Config::set('flutterwave.publicKey',$credentials->public_key);
        Config::set('flutterwave.secretHash',$credentials->encryption_key);
    }

    public function flutterwaveSuccess($output = null) {
        if(!$output) $output = $this->output;
        
        $token = $this->output['tempData']['identifier'] ?? "";
       
        $trx_id = generateTrxString("transactions","trx_id",'R',8);
        
        $user = auth()->user();
        
        Notification::route("mail",$user->email)->notify(new flutterwaveNotification($user,$output,$trx_id));
            
            if(auth()->check()){
                UserNotification::create([
                    'user_id'  => auth()->user()->id,
                    'message'  => "Your Remittance  (Payable amount: ".get_amount($output['amount']->total_amount).",
                    Get Amount: ".get_amount($output['amount']->will_get).") Successfully Sended.", 
                ]);
            }
            
        
        if(empty($token)) throw new Exception('Transaction faild. Record didn\'t saved properly. Please try again.');
        return $this->createTransactionFlutterwave($output);
    }

    public function createTransactionFlutterwave($output) {
        $inserted_id = $this->insertRecordFlutterwave($output);
        
        $this->removeTempDataFlutterWave($output);

        if($this->requestIsApiUser()) {
            // logout user
            $api_user_login_guard = $this->output['api_login_guard'] ?? null;
            if($api_user_login_guard != null) {
                auth()->guard($api_user_login_guard)->logout();
            }
        }

        return $this->output['trx_id'] ?? "";

    }
    public function insertRecordFlutterwave($output) {
        
        $token = $this->output['tempData']['identifier'] ?? "";
        
        $user_data = TemporaryData::where('identifier',$output['tempData']['data']->user_record )->first();
       
        $this->output['user_data']  = $user_data;
        DB::beginTransaction();
        
            try{
                if(Auth::guard(get_auth_guard())->check()){
                    $user_id = auth()->guard(get_auth_guard())->user()->id;
                }
    
                    // Send Remittance
                    $trx_id = generateTrxString("transactions","trx_id",'R',8);
                    $id = DB::table("transactions")->insertGetId([
                        'user_id'                       => $user_id,
                       
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
                        'details'                       => 'COMPLETED',
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

    public function removeTempDataFlutterWave($output) {
        TemporaryData::where("identifier",$output['tempData']['identifier'])->delete();
    }


    // ********* For API **********
    public function flutterwaveInitApi($output = null) {
        if(!$output) $output = $this->output;
        $credentials = $this->getFlutterCredentials($output);
        $this->flutterwaveSetSecreteKey($credentials);

        //This generates a payment reference
        $reference = Flutterwave::generateReference();

        $amount = $output['amount']->total_amount ? number_format($output['amount']->total_amount,2,'.','') : 0;

        if(auth()->guard(get_auth_guard())->check()){
            $user = auth()->guard(get_auth_guard())->user();
            $user_email = $user->email;
            $user_phone = $user->full_mobile ?? '';
            $user_name = $user->firstname.' '.$user->lastname ?? '';
        }

        $return_url = route('api.user.send-remittance.flutterwave.callback', "r-source=".PaymentGatewayConst::APP);

        // Enter the details of the payment
        $data = [
            'payment_options' => 'card,banktransfer',
            'amount'          => $amount,
            'email'           => $user_email,
            'tx_ref'          => $reference,
            'currency'        => $output['currency']['currency_code']??"NGN",
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

        $payment = Flutterwave::initializePayment($data);
        $data['link'] = $payment['data']['link'];
        $data['trx'] = $data['tx_ref'];

        $this->flutterWaveJunkInsert($data);

        if ($payment['status'] !== 'success') {
            // notify something went wrong
            return;
        }

        return $data;
        
    }

}
