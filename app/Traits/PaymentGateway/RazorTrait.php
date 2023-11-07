<?php

namespace App\Traits\PaymentGateway;

use Exception;
use App\Models\AppliedCoupon;
use App\Models\TemporaryData;
use App\Http\Helpers\Response;
use App\Models\UserNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Constants\PaymentGatewayConst;
use App\Notifications\sendNotification;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Notification;
use App\Providers\Admin\BasicSettingsProvider;

trait RazorTrait
{
    public function razorInit($output = null) {
        if(!$output) $output = $this->output;
        $credentials = $this->getCredentials($output);
        $api_key = $credentials->public_key;
        $api_secret = $credentials->secret_key;
        $amount = $output['amount']->total_amount ? number_format($output['amount']->total_amount,2,'.','') : 0;
        if(auth()->guard(get_auth_guard())->check()){
            $user = auth()->guard(get_auth_guard())->user();
            $user_email = $user->email;
            $user_phone = $user->full_mobile ?? '';
            $user_name = $user->firstname.' '.$user->lastname ?? '';
        }
        $return_url = route('user.send.remittance.razor.callback');
        $payment_link = "https://api.razorpay.com/v1/payment_links";

        // Enter the details of the payment
        $data = array(
            "amount" => $amount * 100,
            "currency" => $output['amount']->sender_cur_code,
            "accept_partial" => false,
            "first_min_partial_amount" => 100,
            "reference_id" =>getTrxNum(),
            "description" => "Payment For XRemit  Add Balance",
            "customer" => array(
                "name" => $user_name ,
                "contact" => $user_phone,
                "email" =>  $user_email
            ),
            "notify" => array(
                "sms" => true,
                "email" => true
            ),
            "reminder_enable" => true,
            "notes" => array(
                "policy_name"=> "XRemit "
            ),
            "callback_url"=> $return_url,
            "callback_method"=> "get"
        );

        $payment_data_string = json_encode($data);
        $payment_ch = curl_init($payment_link);
        curl_setopt($payment_ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($payment_ch, CURLOPT_POSTFIELDS, $payment_data_string);
        curl_setopt($payment_ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($payment_ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode($api_key . ':' . $api_secret)
        ));

        $payment_response = curl_exec($payment_ch);
        $payment_data = json_decode($payment_response, true);
        if(isset($payment_data['error'])){
            throw new Exception($payment_data['error']['description']);
        }
        $this->razorJunkInsert($data);
        $payment_url = $payment_data['short_url'];
        return redirect($payment_url);
    }
     // Get Flutter wave credentials
     public function getCredentials($output) {
        $gateway = $output['gateway'] ?? null;
        if(!$gateway) throw new Exception("Payment gateway not available");

        $public_key_sample = ['api key','api_key','client id','primary key', 'public key'];
        $secret_key_sample = ['client_secret','client secret','secret','secret key','secret id'];

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

        return (object) [
            'public_key'     => $public_key,
            'secret_key'     => $secret_key,
        ];

    }
    public function razorJunkInsert($response) {
        $output = $this->output;
        $user = auth()->guard(get_auth_guard())->user();
        $creator_table = $creator_id = $wallet_table = $wallet_id = null;

        $creator_table = auth()->guard(get_auth_guard())->user()->getTable();
        $creator_id = auth()->guard(get_auth_guard())->user()->id;

        $data = [
            'gateway'       => $output['gateway']->id,
            'currency'      => $output['currency']->id,
            'amount'        => json_decode(json_encode($output['amount']),true),
            'response'      => $response,
            'user_record'   => $output['request_data']['identifier'],
            'creator_table' => $creator_table,
            'creator_id'    => $creator_id,
            'creator_guard' => get_auth_guard(),
        ];

        Session::put('identifier',$response['reference_id']);
        Session::put('output',$output);

        return TemporaryData::create([
            'user_id'       => Auth::user()->id,
            'type'          => PaymentGatewayConst::RAZORPAY,
            'identifier'    => $response['reference_id'],
            'data'          => $data,
        ]);
    }
    public function razorpaySuccess($output = null) {
        if(!$output) $output = $this->output;
        $token = $this->output['tempData']['identifier'] ?? "";
        if(empty($token)) throw new Exception('Transaction Failed. Record didn\'t saved properly. Please try again.');
        return $this->createTransactionRazor($output);
    }
    public function createTransactionRazor($output) {
        $basic_setting = BasicSettingsProvider::get();
        $user = auth()->user();
        $trx_id = 'R'.getTrxNum();
        $inserted_id = $this->insertRecordRazor($output,$trx_id);

        $this->removeTempDataRazor($output);
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
                'message'  => "Your Remittance  (Payable amount: ".get_amount($output['amount']->total_amount).",
                Get Amount: ".get_amount($output['amount']->will_get).") Successfully Sended.",
            ]);
        }

        if( $basic_setting->email_notification == true){
            Notification::route("mail",$user->email)->notify(new sendNotification($user,$output,$trx_id));
        }
        return $this->output['trx_id'] ?? "";
    }

    public function insertRecordRazor($output,$trx_id) {
        $trx_id = $trx_id;
        $token = $this->output['tempData']['identifier'] ?? "";
        $user_data = TemporaryData::where('identifier',$output['request_data']['identifier'] )->first();
        $this->output['user_data']  = $user_data;
        DB::beginTransaction();
        try{
            if(Auth::guard(get_auth_guard())->check()){
                $user_id = auth()->guard(get_auth_guard())->user()->id;
            }
                // send remittance

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


    public function removeTempDataRazor($output) {
        TemporaryData::where("identifier",$output['tempData']['identifier'])->delete();
    }


    // ********* For API **********
    public function razorInitApi($output = null) {

        if(!$output) $output = $this->output;

        $credentials = $this->getCredentials($output);
        $api_key = $credentials->public_key;
        $api_secret = $credentials->secret_key;
        $amount = $output['amount']->total_amount ? number_format($output['amount']->total_amount,2,'.','') : 0;
        if(auth()->guard(get_auth_guard())->check()){
            $user = auth()->guard(get_auth_guard())->user();
            $user_email = $user->email;
            $user_phone = $user->full_mobile ?? '';
            $user_name = $user->firstname.' '.$user->lastname ?? '';
        }

        $return_url = route('api.user.send-remittance.razor.callback', "r-source=".PaymentGatewayConst::APP);


        $payment_link = "https://api.razorpay.com/v1/payment_links";

        // Enter the details of the payment
        // Convert the decimal amount to paise (multiply by 100)
        // $amountInDecimal =$amount;  // Your original amount
        // $amountInPaise = (int) ($amountInDecimal * 100);
        $data = array(
            "amount" => $amount * 100,
            "currency" => $output['amount']->sender_cur_code,
            "accept_partial" => false,
            "first_min_partial_amount" => 100,
            "reference_id" =>getTrxNum(),
            "description" => "Payment For XRemit  Add Balance",
            "customer" => array(
                "name" => $user_name ,
                "contact" => $user_phone,
                "email" =>  $user_email
            ),
            "notify" => array(
                "sms" => true,
                "email" => true
            ),
            "reminder_enable" => true,
            "notes" => array(
                "policy_name"=> "XRemit "
            ),
            "callback_url"=> $return_url,
            "callback_method"=> "get"
        );

        $payment_data_string = json_encode($data);
        $payment_ch = curl_init($payment_link);

        curl_setopt($payment_ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($payment_ch, CURLOPT_POSTFIELDS, $payment_data_string);
        curl_setopt($payment_ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($payment_ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode($api_key . ':' . $api_secret)
        ));

        $payment_response = curl_exec($payment_ch);
        $payment_data = json_decode($payment_response, true);
        if(isset($payment_data['error'])){
            $message = ['error' => [$payment_data['error']['description']]];
            Response::error($message);
        }

        $this->razorJunkInsert($payment_data);
        $data['short_url'] = $payment_data['short_url'];

        return $data;
    }
}
