<?php

namespace App\Traits\PaymentGateway;

use Exception;
use App\Traits\Transaction;
use Illuminate\Http\Request;
use App\Models\AppliedCoupon;
use App\Models\TemporaryData;
use App\Http\Helpers\Response;
use App\Models\UserNotification;
use Illuminate\Support\Facades\DB;
use App\Http\Helpers\PaymentGateway;
use Illuminate\Support\Facades\Auth;
use App\Constants\PaymentGatewayConst;
use App\Notifications\sendNotification;
use Illuminate\Support\Facades\Session;
use App\Traits\ControlDynamicInputFields;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\PaymentGatewayCurrency;
use Illuminate\Support\Facades\Notification;
use App\Models\Admin\PaymentGateway as PaymentGatewayModel;

trait Manual
{
use ControlDynamicInputFields, Transaction;

    public function manualInit($output = null) {
        if(!$output) $output = $this->output;
        $gatewayAlias = $output['gateway']['alias'];
        $identifier = generate_unique_string("transactions","trx_id",16);
        $this->manualJunkInsert($identifier);
        Session::put('identifier',$identifier);
      
        Session::put('output',$output);
        return redirect()->route('user.send.remittance.manual.payment');
    }

    public function manualJunkInsert($response) {

        $output = $this->output;

        $data = [
            'gateway'   => $output['gateway']->id,
            'currency'  => $output['currency']->id,
            'amount'    => json_decode(json_encode($output['amount']),true),
            'response'  => $response,
            'user_record' => $output['request_data']['identifier'],
        ];

        return TemporaryData::create([
            'user_id'    => Auth::id(),
            'type'       => PaymentGatewayConst::MANUA_GATEWAY,
            'identifier' => $response,
            'data'       => $data,
        ]);
    }

    public function manualPaymentConfirmed(Request $request){
        $output = session()->get('output');
        
        $tempData = Session::get('identifier');
        
        $hasData = TemporaryData::where('identifier', $tempData)->first();
        
        $gateway = PaymentGatewayModel::manual()->where('slug',PaymentGatewayConst::remittance_money_slug())->where('id',$hasData->data->gateway)->first();
        
        $payment_fields = $gateway->input_fields ?? [];

        $validation_rules = $this->generateValidationRules($payment_fields);
        $payment_field_validate = Validator::make($request->all(),$validation_rules)->validate();
        $get_values = $this->placeValueWithFields($payment_fields,$payment_field_validate);

        try{
            $trx_id = generateTrxString("transactions","trx_id",'R',8);
            $user = auth()->user();
            Notification::route("mail",$user->email)->notify(new sendNotification($user,$output,$trx_id));
            
            if(auth()->check()){
                UserNotification::create([
                    'user_id'  => auth()->user()->id,
                    'message'  => "Your Remittance  (Payable amount: ".get_amount($output['amount']->total_amount).",
                    Get Amount: ".get_amount($output['amount']->will_get).") Successfully Sended.", 
                ]);
            }
            
            $inserted_id = $this->insertRecordManual($output,$get_values,$trx_id);
            
            $this->removeTempDataManual($output);
            
            return redirect()->route("user.payment.confirmation",$trx_id)->with(['success' => ['Successfully send remittance']]);
        }catch(Exception $e) {
            
            return back()->with(['error' => [$e->getMessage()]]);
        }
    }

    public function insertRecordManual($output,$get_values,$trx_id) {
        $trx_id = $trx_id;
        
        $token = $this->output['tempData']['identifier'] ?? "";
        $user_data = TemporaryData::where('identifier',$output['request_data']['identifier'] )->first();
        $this->output['user_data']  = $user_data;
       
        DB::beginTransaction();
        
            try{
                $id = DB::table("transactions")->insertGetId([
                    'user_id'                       => auth()->user()->id,
                    
                    'payment_gateway_currency_id'   => $output['currency']->id,
                    'type'                          => PaymentGatewayConst::REMITTANCE_MONEY,
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
                        'address'                   => $this->output['user_data']->data->address,
                        'zip_code'                  => $this->output['user_data']->data->zip_code,
                        'phone'                     => $this->output['user_data']->data->phone,
                        'method_name'               => $this->output['user_data']->data->method_name,
                        'account_number'            => $this->output['user_data']->data->account_number,
                        'document_type'             => $this->output['user_data']->data->document_type,
                        'sending_purpose'           => $this->output['user_data']->data->sending_purpose->name,
                        'source'                    => $this->output['user_data']->data->source->name,
                        'currency'                  => [
                            'name'                  => $this->output['user_data']->data->currency->name,
                            'code'                  => $this->output['user_data']->data->currency->code,
                        ],
                        'send_money'                => $this->output['user_data']->data->send_money,
                        'convert_amount'            => $this->output['user_data']->data->convert_amount,
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
                    
                    'status'                        => global_const()::REMITTANCE_STATUS_REVIEW_PAYMENT,
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

    public function removeTempDataManual($output) {
        $token = session()->get('identifier');
        TemporaryData::where("identifier",$token)->delete();
    }

     //for api
     public function manualInitApi($output = null) {
        if(!$output) $output = $this->output;
        $gatewayAlias = $output['gateway']['alias'];
        $identifier = generate_unique_string("transactions","trx_id",16);
        $this->manualJunkInsert($identifier);
        $response=[
            'trx' => $identifier,
        ];
        return $response;
    }

    public function manualPaymentConfirmedApi(Request $request){
        $validator = Validator::make($request->all(), [
            'track' => 'required',
        ]);
        if($validator->fails()){
            $error =  ['error'=>$validator->errors()->all()];
            return Response::validation($error);
        }
        $track = $request->track;
        $hasData = TemporaryData::where('identifier', $track)->first();
        if(!$hasData){
            $error = ['error'=>["Sorry, your payment information is invalid"]];
            return Response::error($error);
        }
        $gateway = PaymentGatewayModel::manual()->where('slug',PaymentGatewayConst::remittance_money_slug())->where('id',$hasData->data->gateway)->first();
        $payment_fields = $gateway->input_fields ?? [];
        
        $validation_rules = $this->generateValidationRules($payment_fields);
        $validator2 = Validator::make($request->all(), $validation_rules);
        
        if ($validator2->fails()) {
            $message =  ['error' => $validator2->errors()->all()];
            return Response::error($message);
        }
        $validated = $validator2->validate();
        $get_values = $this->placeValueWithFields($payment_fields, $validated);
        $payment_gateway_currency = PaymentGatewayCurrency::where('id', $hasData->data->currency)->first();
        $convert_amount = $hasData->data->amount->convert_amount;
        $total_charge   = $hasData->data->amount->total_charge;
       
        $gateway_request = ['currency' => $payment_gateway_currency->alias, 'amount'  => $hasData->data->amount->requested_amount,'fees' => $total_charge,'convert_amount' => $convert_amount, 'receive_money' => $hasData->data->amount->will_get,'identifier' => $hasData->data->user_record];
        
        $output = PaymentGateway::init($gateway_request)->gateway()->get();

        try{
            $trx_id = generateTrxString("transactions","trx_id",'R',8);
            $user = auth()->user();
            Notification::route("mail",$user->email)->notify(new sendNotification($user,$output,$trx_id));
            
            if(auth()->check()){
                UserNotification::create([
                    'user_id'  => auth()->user()->id,
                    'message'  => "Your Remittance  (Payable amount: ".get_amount($output['amount']->total_amount + $output['amount']->total_charge).",
                    Get Amount: ".get_amount($output['amount']->will_get).") Successfully Sended.", 
                ]);
            }
            $inserted_id = $this->insertRecordManual($output,$get_values,$trx_id);
            
            $share_link   = route('share.link',$trx_id);
            $download_link   = route('download.pdf',$trx_id);

            $hasData->delete();
            return Response::success( ['Send Remittance successfull'],[
                'share-link'   => $share_link,
                'download_link' => $download_link,
            ],200);
        }catch(Exception $e) {
                $error = ['error'=>[$e->getMessage()]];
                return Response::error($error);
        }
    }

}
