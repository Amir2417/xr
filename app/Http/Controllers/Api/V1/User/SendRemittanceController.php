<?php

namespace App\Http\Controllers\Api\V1\User;

use Exception;
use App\Models\Recipient;
use Illuminate\Support\Str;
use App\Models\Admin\Coupon;
use Illuminate\Http\Request;
use App\Models\TemporaryData;
use App\Http\Helpers\Response;
use App\Models\Admin\Currency;
use App\Models\Admin\MobileMethod;
use App\Models\Admin\SourceOfFund;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Admin\PaymentGateway;
use App\Models\Admin\RemittanceBank;
use App\Models\Admin\SendingPurpose;
use Illuminate\Support\Facades\Auth;
use App\Traits\PaymentGateway\Manual;
use App\Traits\PaymentGateway\Stripe;
use App\Constants\PaymentGatewayConst;
use App\Models\Admin\TransactionSetting;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\PaymentGatewayCurrency;
use Illuminate\Validation\ValidationException;
use KingFlamez\Rave\Facades\Rave as Flutterwave;
use App\Http\Helpers\PaymentGateway as PaymentGatewayHelper;

class SendRemittanceController extends Controller
{
    use Stripe,Manual;
    /**
     * Method for get the transaction type data
     */
    public function index(Request $request){

        $transaction_type  = TransactionSetting::where('status',true)->get();
        if($transaction_type->isEmpty()) {
            return Response::error(['Transaction Type not found!'],[],404);
        }
        $sender_currency      = Currency::where('status',true)->where('sender',true)->get()->map(function($data){
            return [
                'id'           => $data->id,
                'admin_id'     => $data->admin_id,
                'country'      => $data->country,
                'name'         => $data->name,
                'code'         => $data->code,
                'symbol'       => $data->code,
                'type'         => $data->type,
                'flag'         => $data->flag,
                'rate'         => $data->rate,
                'sender'       => $data->sender,
                'receiver'     => $data->receiver,
            ];
        });
        $receiver_currency    = Currency::where('status',true)->where('receiver',true)->get()->map(function($data){
            return [
                'id'           => $data->id,
                'admin_id'     => $data->admin_id,
                'country'      => $data->country,
                'name'         => $data->name,
                'code'         => $data->code,
                'symbol'       => $data->code,
                'type'         => $data->type,
                'flag'         => $data->flag,
                'rate'         => $data->rate,
                'sender'       => $data->sender,
                'receiver'     => $data->receiver,
            ];
        });
        $image_paths = [
            'base_url'         => url("/"),
            'path_location'    => files_asset_path_basename("currency-flag"),
            'default_image'    => files_asset_path_basename("default"),

        ];


        return Response::success(['Transaction Type find successfully.'],[
            'sender_currency'    => $sender_currency,
            'receiver_currency'  => $receiver_currency,
            'image_paths'        => $image_paths,
            'transaction_type'   => $transaction_type
        ],200);
    }
    /**
     * Method for store send remittance data in temporary datas
     */
    public function store(Request $request){
        $validator              = Validator::make($request->all(),[
            'type'              => 'required',
            'send_money'        => 'required',
            'sender_currency'   => 'required',
            'receiver_currency' => 'required',
            'coupon'            => 'nullable',
        ]);
        if($validator->fails()){
            return Response::error($validator->errors()->all(),[]);
        }
        $validated            = $validator->validate();
        $coupons = Coupon::where('status', true)->get();

        $matchingCoupon = $coupons->first(function ($coupon) use ($request) {
            return $coupon->name === $request->coupon;
        });
        $user   = auth()->user();
        $couponBonus = '';
        $couponId = 0;
        if($request->coupon){
            if($matchingCoupon){
                if($user->coupon_status == 0){
                    $couponBonus    = $matchingCoupon->price;
                    $couponId       = $matchingCoupon->id;
                }else{
                    return Response::error(['Already applied the coupon!'],[],404);
                }
            }else{
                return Response::error(['Coupon not found!'],[],404);  
            }
        }
        
        
        $transaction_type     = TransactionSetting::where('status',true)->where('title','like','%'.$request->type.'%')->first();
        $sender_currency      = Currency::where('status',true)->where('sender',true)->where('id',$request->sender_currency)->first();
        $receiver_currency    = Currency::where('status',true)->where('receiver',true)->where('id',$request->receiver_currency)->first();

        if($request->type == PaymentGatewayConst::TRANSACTION_TYPE_BANK || $request->type == PaymentGatewayConst::TRANSACTION_TYPE_MOBILE){
            $sender_currency_code   = $sender_currency->code;
            $sender_currency_rate   = $sender_currency->rate;
            $sender_rate            = $sender_currency_rate / $sender_currency_rate;
            $receiver_currency_code = $receiver_currency->code;
            $receiver_currency_rate = $receiver_currency->rate;

            $enter_amount           = floatval($request->send_money) / $sender_currency_rate;

            $find_percent_charge    = ($enter_amount) / 100;

            $fixed_charge           = $transaction_type->fixed_charge;

            $percent_charge         = $transaction_type->percent_charge;

            $total_percent_charge   = $find_percent_charge * $percent_charge;
            $total_charge           = $fixed_charge + $total_percent_charge;
            $total_charge_amount    = $total_charge * $sender_currency_rate;


            $payable_amount       = $enter_amount + $total_charge_amount;
            if ($request->send_money == 0) {
                return Response::error(['Send Money must be greater than 0.'], [], 400);
            }
            if($enter_amount == ""){
                $enter_amount = 0;
            }
            if($enter_amount != 0){
                $convert_amount = $enter_amount;
                $receive_money  = $convert_amount * $receiver_currency_rate;
                $intervals      = $transaction_type->intervals;
                foreach($intervals as $index => $item){
                    if($enter_amount >= $item->min_limit && $enter_amount <= $item->max_limit){
                        $fixed_charge         = $item->fixed;
                        $percent_charge       = $item->percent;
                        $total_percent_charge = $find_percent_charge * $percent_charge;
                        $total_charge         = $fixed_charge + $total_percent_charge;
                        $total_charge_amount  = $total_charge * $sender_currency_rate;
                        $convert_amount       = floatval($request->send_money);
                        $payable_amount       = $request->send_money + $total_charge_amount;
                        $reciver_rate         = $receiver_currency_rate / $sender_currency_rate;
                        $receive_money        = $convert_amount * $reciver_rate;
                    }
                }
            }
        }
        
        if($couponId != 0){
            $coupon_price    = $couponBonus * $reciver_rate;
            $receive_money   = $receive_money + $coupon_price;
        }else{
            $receive_money  = $receive_money;
        }
        
        $validated['identifier']    = Str::uuid();
        $data = [
            'type'                  => $validated['type'],
            'identifier'            => $validated['identifier'],
            'data'                  => [
                'sender_name'       => auth()->user()->fullname,
                'sender_email'      => auth()->user()->email,
                'send_money'        => floatval($request->send_money),
                'fees'              => $total_charge_amount,
                'convert_amount'    => $convert_amount,
                'payable_amount'    => $payable_amount,
                'receive_money'     => $receive_money,
                'sender_currency'   => $sender_currency_code,
                'receiver_currency' => $receiver_currency_code,
                'sender_ex_rate'    => $sender_rate,
                'receiver_ex_rate'  => $reciver_rate,
                'sender_base_rate'  => floatval($sender_currency_rate),
                'coupon_id'         => $couponId,
            ],

        ];
        try {
            $temporary_data = TemporaryData::create($data);
        } catch (Exception $e) {
            return Response::error(['Something went wrong! Please try again.'],[],404);
        }

        return Response::success(['Send Money'],[
            'temporary_data'      => $temporary_data
        ],200);
    }
    /**
    * Method for calculate send remittance
    */
    public function sendMoney(Request $request){
        $validator           = Validator::make($request->all(),[
            'type'           => 'required',
            'send_money'     => 'nullable',
        ]);
        if($validator->fails()){
            return Response::error($validator->errors()->all(),[]);
        }
        $validated            = $validator->validate();
        $transaction_type     = TransactionSetting::where('status',true)->where('title','like','%'.$request->type.'%')->first();
        $receiver_currency    = Currency::where('status',true)->where('receiver',true)->first();

        if($request->type == PaymentGatewayConst::TRANSACTION_TYPE_BANK || $request->type == PaymentGatewayConst::TRANSACTION_TYPE_MOBILE){
            $enter_amount         = floatval($request->send_money);
            $find_percent_charge  = $enter_amount / 100;
            $fixed_charge         = $transaction_type->fixed_charge;
            $percent_charge       = $transaction_type->percent_charge;
            $total_percent_charge = $find_percent_charge * $percent_charge;
            $total_charge         = $fixed_charge + $total_percent_charge;
            $payable_amount       = $enter_amount + $total_charge;
            if ($request->send_money == 0) {
                return Response::error(['Send Money must be greater than 0.'], [], 400);
            }
            if($enter_amount == ""){
                $enter_amount = 0;
            }
            if($enter_amount != 0){
                $convert_amount = $enter_amount;
                $receive_money  = $convert_amount * $receiver_currency->rate;
                $intervals      = $transaction_type->intervals;
                foreach($intervals as $index => $item){
                    if($enter_amount >= $item->min_limit && $enter_amount <= $item->max_limit){
                        $fixed_charge         = $item->fixed;
                        $percent_charge       = $item->percent;
                        $total_percent_charge = $find_percent_charge * $percent_charge;
                        $total_charge         = $fixed_charge + $total_percent_charge;
                        $convert_amount       = $enter_amount;
                        $payable_amount       = $enter_amount + $total_charge;
                        $receive_money        = $convert_amount * $receiver_currency->rate;
                    }
                }
            }
        }

        $validated['identifier']    = Str::uuid();
        $data = [
            'type'               => $validated['type'],
            'identifier'         => $validated['identifier'],
            'data'               => [
                'send_money'     => $enter_amount,
                'fees'           => $total_charge,
                'convert_amount' => $convert_amount,
                'payable_amount' => $payable_amount,
                'receive_money'  => $receive_money,
            ],

        ];
        try {
            $temporary_data = TemporaryData::create($data);
        } catch (Exception $e) {
            return Response::error(['Something went wrong! Please try again.'],[],404);
        }

        return Response::success(['Send Money'],[
            'temporary_data'      => $temporary_data
        ],200);

    }
    /**
     * Method for show send remittance beneficiary
     * @param $identifier
     * @param Illuminate\Http\Request $request
     */
    public function beneficiary(Request $request){
        $validator  = Validator::make($request->all(),[
            'identifier'  => 'required',
        ]);

        if($validator->fails()) return Response::error($validator->errors()->all(),[]);
        $temporary_data    = TemporaryData::where('identifier',$request->identifier)->first();
        $beneficiaries     = Recipient::where('method',$temporary_data->type)->where('user_id',auth()->user()->id)->orderBy('id')->get();

        return Response::success(['Beneficiary'],[
            'beneficiaries'       => $beneficiaries,
            'temporary_data'      => $temporary_data
        ],200);
    }
    /**
     * Method for show the bank and mobile method information
     */
    public function beneficiaryAdd(Request $request){
        $validator  = Validator::make($request->all(),[
            'identifier'      => 'nullable',
        ]);
        if($validator->fails()){
            return Response::error($validator->errors()->all(),[]);
        }

        $receiver_country     = Currency::where('receiver',true)->first();
        $banks                = RemittanceBank::where('country',$receiver_country->country)->where('status',true)->get()->map(function($data){
            return [
                'id'                 => $data->id,
                'name'               => $data->name,
                'slug'               => $data->slug,
                'country'            => $data->country,
                'status'             => $data->status,
                'created_at'         => $data->created_at ?? '',
                'updated_at'         => $data->updated_at ?? '',
            ];
        });
        $mobile_methods       = MobileMethod::where('country',$receiver_country->country)->where('status',true)->get()->map(function($data){
            return [
                'id'                 => $data->id,
                'name'               => $data->name,
                'slug'               => $data->slug,
                'country'            => $data->country,
                'status'             => $data->status,
                'created_at'         => $data->created_at ?? '',
                'updated_at'         => $data->updated_at ?? '',
            ];
        });

        return Response::success(['Bank and Mobile Data fetch successfully.'],[
            'banks'           => $banks,
            'mobile_method'   => $mobile_methods,
        ],200);
    }

    public function beneficiaryStore(Request $request){
        $validator  = Validator::make($request->all(),[
            'identifier'      => 'required',
        ]);
        $temporary_data       = TemporaryData::where('identifier',$request->identifier)->first();
        if($request->method == global_const()::BENEFICIARY_METHOD_BANK_TRANSAFER ){
            $validator      = Validator::make($request->all(),[
                'first_name'      => 'required|string',
                'middle_name'     => 'nullable|string',
                'last_name'       => 'required|string',
                'email'           => 'nullable|email',
                'country'         => 'nullable|string',
                'city'            => 'nullable|string',
                'state'           => 'nullable|string',
                'zip_code'        => 'nullable|string',
                'phone'           => 'nullable|string',
                'method'          => 'required|string',
                'bank_name'       => 'required|string',
                'iban_number'     => 'required',
                'address'         => 'nullable|string',
                'document_type'   => 'nullable|string',
                'front_image'     => 'nullable|image|mimes:png,jpg,webp,jpeg,svg',
                'back_image'      => 'nullable|image|mimes:png,jpg,webp,jpeg,svg',
            ]);
            if($validator->fails()){
                return Response::error($validator->errors()->all(),[]);
            }
            $validated   = $validator->validate();
            if($request->hasFile('front_image')){
                $image = upload_file($validated['front_image'],'junk-files');
                $upload_image = upload_files_from_path_dynamic([$image['dev_path']],'site-section');
                chmod(get_files_path('site-section') . '/' . $upload_image, 0644);
                $validated['front_image']     = $upload_image;

            }
            if($request->hasFile('back_image')){
                $back_image = upload_file($validated['back_image'],'junk-files');
                $back_upload_image = upload_files_from_path_dynamic([$back_image['dev_path']],'site-section');
                chmod(get_files_path('site-section') . '/' . $back_upload_image, 0644);
                $validated['back_image']     = $back_upload_image;
            }
            $validated['user_id'] = auth()->user()->id;
            if(Recipient::where('user_id',auth()->user()->id)->where('email',$validated['email'])->where('method',$validated['method'])->where('bank_name',$validated['bank_name'])->exists()){
                throw ValidationException::withMessages([
                    'name'  => "Recipient already exists!",
                ]);
            }

            try{
                $beneficiary  = Recipient::create($validated);
            }catch(Exception $e){
                return Response::error(['Something went wrong! Please try again.'],[],404);
            }
            return Response::success(['Beneficiary'],[
                'beneficiary'       => $beneficiary,
                'temporary_data'      => $temporary_data
            ],200);
        }if($request->method == global_const()::BENEFICIARY_METHOD_MOBILE_MONEY){
            $validator      = Validator::make($request->all(),[
                'first_name'      => 'required|string',
                'middle_name'     => 'nullable|string',
                'last_name'       => 'required|string',
                'email'           => 'nullable|email',
                'country'         => 'nullable|string',
                'city'            => 'nullable|string',
                'state'           => 'nullable|string',
                'zip_code'        => 'nullable|string',
                'phone'           => 'nullable|string',
                'method'          => 'required|string',
                'mobile_name'     => 'required|string',
                'account_number'  => 'required|string',
                'address'         => 'nullable|string',
                'document_type'   => 'nullable|string',
                'front_image'     => 'nullable|image|mimes:png,jpg,webp,jpeg,svg',
                'back_image'      => 'nullable|image|mimes:png,jpg,webp,jpeg,svg',
            ]);
            if($validator->fails()){
                return Response::error($validator->errors()->all(),[]);
            }
            $validated   = $validator->validate();
            if($request->hasFile('front_image')){
                $image = upload_file($validated['front_image'],'junk-files');
                $upload_image = upload_files_from_path_dynamic([$image['dev_path']],'site-section');
                chmod(get_files_path('site-section') . '/' . $upload_image, 0644);
                $validated['front_image']     = $upload_image;

            }
            if($request->hasFile('back_image')){
                $back_image = upload_file($validated['back_image'],'junk-files');
                $back_upload_image = upload_files_from_path_dynamic([$back_image['dev_path']],'site-section');
                chmod(get_files_path('site-section') . '/' . $back_upload_image, 0644);
                $validated['back_image']     = $back_upload_image;
            }
            $validated['user_id'] = auth()->user()->id;
            if(Recipient::where('user_id',auth()->user()->id)->where('email',$validated['email'])->where('method',$validated['method'])->where('mobile_name',$validated['mobile_name'])->exists()){
                throw ValidationException::withMessages([
                    'name'  => "Recipient already exists!",
                ]);
            }
            try{
                $beneficiary  = Recipient::create($validated);
            }catch(Exception $e){
                return Response::error(['Something went wrong! Please try again.'],[],404);
            }
            return Response::success(['Beneficiary'],[
                'beneficiary'       => $beneficiary,
                'temporary_data'      => $temporary_data
            ],200);
        }
        return Response::error(['Something went wrong! Please try again.'],[],404);
    }
    /**
     * Method for send the beneficiary to temporary data
     */
    public function beneficiarySend(Request $request){
        $validator  = Validator::make($request->all(),[
            'beneficiary_id'  => 'required',
            'identifier'      => 'required',
        ]);
        if($validator->fails()){
            return Response::error($validator->errors()->all(),[]);
        }
        $beneficiary = Recipient::where('id',$request->beneficiary_id)->first();
        if(!$beneficiary){
            return Response::error(['Recipient Not exists!'],[],404);
        }
        $temporary_data = TemporaryData::where('identifier',$request->identifier)->first();
        $data = [
            'type'                  => $temporary_data->type,
            'identifier'            => $temporary_data->identifier,
            'data'                  => [
                'sender_name'       => $temporary_data->data->sender_name,
                'sender_email'      => $temporary_data->data->sender_email,
                'sender_currency'   => $temporary_data->data->sender_currency,
                'receiver_currency' => $temporary_data->data->receiver_currency,
                'sender_ex_rate'    => $temporary_data->data->sender_ex_rate,
                'sender_base_rate'  => $temporary_data->data->sender_base_rate,
                'receiver_ex_rate'  => $temporary_data->data->receiver_ex_rate,
                'coupon_id'         => $temporary_data->data->coupon_id,
                'first_name'        => $beneficiary->first_name,
                'middle_name'       => $beneficiary->middle_name ?? '',
                'last_name'         => $beneficiary->last_name,
                'email'             => $beneficiary->email ?? '',
                'country'           => $beneficiary->country,
                'city'              => $beneficiary->city ?? '',
                'state'             => $beneficiary->state ?? '',
                'zip_code'          => $beneficiary->zip_code ?? '',
                'phone'             => $beneficiary->phone ?? '',
                'method_name'       => $beneficiary->bank_name ?? $beneficiary->mobile_name,
                'account_number'    => $beneficiary->iban_number ?? $beneficiary->account_number,
                'address'           => $beneficiary->address ?? '',
                'document_type'     => $beneficiary->document_type ?? '',
                'front_image'       => $beneficiary->front_image ?? '',
                'back_image'        => $beneficiary->back_image ?? '',
                'send_money'        => $temporary_data->data->send_money,
                'fees'              => $temporary_data->data->fees,
                'convert_amount'    => $temporary_data->data->convert_amount,
                'payable_amount'    => $temporary_data->data->payable_amount,
                'receive_money'     => $temporary_data->data->receive_money,
            ],
        ];
        try{
            $temporary_data->update($data);
        }catch(Exception $e){
            return Response::error(['Something went wrong! Please try again.'],[],404);
        }
        return Response::success(['Beneficiary'],[
            'beneficiaries'       => $beneficiary,
            'temporary_data'      => $temporary_data
        ],200);
    }
    /**
     * Method for store receipt payment information
     */
    public function receiptPayment(Request $request){
        $validator = Validator::make($request->all(),[
            'identifier'  => 'nullable',
        ]);
        if($validator->fails()){
            return Response::error($validator->errors()->all(),[]);
        }
        $transaction_type  = TransactionSetting::where('status',true)->get();
        if($transaction_type->isEmpty()) {
            return Response::error(['Transaction Type not found!'],[],404);
        }
        $sending_purposes  = SendingPurpose::where('status',true)->get();
        $source_of_funds   = SourceOfFund::where('status',true)->get();
        $payment_gateway   = PaymentGatewayCurrency::whereHas('gateway', function ($gateway) {
            $gateway->where('slug', PaymentGatewayConst::remittance_money_slug());
            $gateway->where('status', 1);
        })->get();

        return Response::success(['Sending Purpose and Source of Fund Data Fetch Successfully.'],[
            'sending_purposes'       => $sending_purposes,
            'source_of_funds'        => $source_of_funds,
            'payment_gateway'        => $payment_gateway,
            'transaction_type'       => $transaction_type,
        ],200);
    }
    /**
     * Method for store receipt payment information
     */
    public function receiptPaymentStore(Request $request){
        $validator = Validator::make($request->all(),[
            'identifier'         => 'required',
            'sending_purpose'    => 'required|integer',
            'source'             => 'required|integer',
            'remark'             => 'nullable|string',
            'payment_gateway'    => 'required|integer',
        ]);
        if($validator->fails()){
            return Response::error($validator->errors()->all(),[]);
        }
        $temporary_data    = TemporaryData::where('identifier',$request->identifier)->first();
        $validated   = $validator->validate();
        $currency = PaymentGatewayCurrency::where('id',$validated['payment_gateway'])->first();
        $source_of_fund = SourceOfFund::where('id',$validated['source'])->first();
        $sending_purpose  = SendingPurpose::where('id',$validated['sending_purpose'])->first();
        $sender_currency      = Currency::where('status',true)->where('sender',true)->first();

        $receiver_currency    = Currency::where('status',true)->where('receiver',true)->first();
        $rate                  = $currency->rate / $temporary_data->data->sender_base_rate;
        
        $data = [
            'type'                    => $temporary_data->type,
            'identifier'              => $temporary_data->identifier,
            'data'                    => [
                'sender_name'         => auth()->user()->fullname,
                'sender_email'        => auth()->user()->email,
                'sender_currency'     => $temporary_data->data->sender_currency,
                'receiver_currency'   => $temporary_data->data->receiver_currency,
                'sender_ex_rate'      => $temporary_data->data->sender_ex_rate,
                'sender_base_rate'    => $temporary_data->data->sender_base_rate,
                'receiver_ex_rate'    => $temporary_data->data->receiver_ex_rate,
                'coupon_id'           => $temporary_data->data->coupon_id,
                'first_name'          => $temporary_data->data->first_name,
                'middle_name'         => $temporary_data->data->middle_name,
                'last_name'           => $temporary_data->data->last_name,
                'email'               => $temporary_data->data->email,
                'country'             => $temporary_data->data->country,
                'city'                => $temporary_data->data->city,
                'state'               => $temporary_data->data->state,
                'zip_code'            => $temporary_data->data->zip_code,
                'phone'               => $temporary_data->data->phone,
                'method_name'         => $temporary_data->data->method_name,
                'account_number'      => $temporary_data->data->account_number,
                'address'             => $temporary_data->data->address,
                'document_type'       => $temporary_data->data->document_type,
                'sending_purpose'     => [
                    'id'              => $sending_purpose->id,
                    'name'            => $sending_purpose->name,
                ],
                'source'              => [
                    'id'              => $source_of_fund->id,
                    'name'            => $source_of_fund->name,
                ],
                'remark'              => $validated['remark'],
                'currency'            => [
                    'id'              => $currency->id,
                    'name'            => $currency->name,
                    'code'            => $currency->currency_code,
                    'alias'           => $currency->alias,
                    'rate'            => $currency->rate,
                ],
                'payment_gateway'     => $validated['payment_gateway'],
                'front_image'         => $temporary_data->data->front_image,
                'back_image'          => $temporary_data->data->back_image,
                'send_money'          => $temporary_data->data->send_money,
                'fees'                => $temporary_data->data->fees,
                'convert_amount'      => $temporary_data->data->convert_amount,
                'payable_amount'      => $temporary_data->data->payable_amount * $rate,
                'receive_money'       => $temporary_data->data->receive_money,
            ],

        ];
        try{
            $temporary_data->update($data);
        }catch(Exception $e){
            return Response::error(['Something went wrong! Please try again.'],[],404);
        }
        return Response::success(['Receipt Payment Data Stored'],[
            'temporary_data'         => $temporary_data
        ],200);
    }

      /**
     * Add Money Form Submit
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function submitData(Request $request) {

        $validator = Validator::make($request->all(), [
           'identifier'  => "required",
       ]);

       if($validator->fails()){
           $error =  ['error'=>$validator->errors()->all()];
           return Response::validation($error);
       }

       $temporary_data  = TemporaryData::where('identifier',$request->identifier)->first();

       $alias = $temporary_data->data->currency->alias;
       $amount = $temporary_data->data->payable_amount * $temporary_data->data->currency->rate;

       $payment_gateways_currencies = PaymentGatewayCurrency::where('alias',$alias)->whereHas('gateway', function ($gateway) {
           $gateway->where('slug', PaymentGatewayConst::remittance_money_slug());
           $gateway->where('status', 1);
        })->first();

       if(!$payment_gateways_currencies){
            $error = ['error'=>['Gateway Information is not available. Please provide payment gateway currency alias']];
            return Response::error($error);
       }
       $user = auth()->user();
       try{
           $instance = PaymentGatewayHelper::init($request->all())->gateway()->api()->get();
           $trx = $instance['response']['id']??$instance['response']['trx']??$instance['response']['reference_id']??$instance['response'];;
           $temData = TemporaryData::where('identifier',$trx)->first();

           if(!$temData){
               $error = ['error'=>["Invalid Request"]];
               return Response::error($error);
           }
           $payment_gateway_currency = PaymentGatewayCurrency::where('id', $temData->data->currency)->first();
           $payment_gateway = PaymentGateway::where('id', $temData->data->gateway)->first();
           if($payment_gateway->type == "AUTOMATIC") {
               if($temData->type == PaymentGatewayConst::STRIPE) {
                   $payment_informations =[
                       'trx' =>  $temData->identifier,
                       'gateway_currency_name' =>  $payment_gateway_currency->name,
                       'request_amount' => get_amount($temData->data->amount->requested_amount).' '.$temData->data->amount->default_currency,
                       'exchange_rate' => "1".' '.$temData->data->amount->default_currency.' = '.get_amount($temData->data->amount->sender_cur_rate).' '.$temData->data->amount->sender_cur_code,
                       'total_charge' => get_amount($temData->data->amount->total_charge).' '.$temData->data->amount->sender_cur_code,
                       'will_get' => get_amount($temData->data->amount->will_get).' '.$temData->data->amount->default_currency,
                       'payable_amount' =>  get_amount($temData->data->amount->total_amount).' '.$temData->data->amount->sender_cur_code,
                   ];
                   $data =[
                        'gategay_type'          => $payment_gateway->type,
                        'gateway_currency_name' => $payment_gateway_currency->name,
                        'alias'                 => $payment_gateway_currency->alias,
                        'identify'              => $temData->type,
                        'payment_informations'  => $payment_informations,
                        'url'                   => @$temData->data->response->link."?prefilled_email=".@$user->email,
                        'method'                => "get",
                   ];

                   return Response::success(['Send Remittance Inserted Successfully'], $data);
               }elseif($temData->type == PaymentGatewayConst::RAZORPAY) {
                $payment_informations =[
                    'trx' =>  $temData->identifier,
                    'gateway_currency_name' =>  $payment_gateway_currency->name,
                    'request_amount' => get_amount($temData->data->amount->requested_amount).' '.$temData->data->amount->default_currency,
                    'exchange_rate' => "1".' '.$temData->data->amount->default_currency.' = '.get_amount($temData->data->amount->sender_cur_rate).' '.$temData->data->amount->sender_cur_code,
                    'total_charge' => get_amount($temData->data->amount->total_charge).' '.$temData->data->amount->sender_cur_code,
                    'will_get' => get_amount($temData->data->amount->will_get).' '.$temData->data->amount->default_currency,
                    'payable_amount' =>  get_amount($temData->data->amount->total_amount).' '.$temData->data->amount->sender_cur_code,
                ];
                $data =[
                     'gategay_type'          => $payment_gateway->type,
                     'gateway_currency_name' => $payment_gateway_currency->name,
                     'alias'                 => $payment_gateway_currency->alias,
                     'identify'              => $temData->type,
                     'payment_informations'  => $payment_informations,
                     'url' => @$instance['response']['short_url'],
                     'method' => "get",
                ];

                return Response::success(['Send Remittance Inserted Successfully'], $data);
            }else if($temData->type == PaymentGatewayConst::PAYPAL) {
                
                   $payment_informations = [
                        'trx'                   => $temData->identifier,
                        'gateway_currency_name' => $payment_gateway_currency->name,
                        'request_amount'        => get_amount($temData->data->amount->requested_amount).' '.$temData->data->amount->default_currency,
                        'exchange_rate'         => "1".' '.$temData->data->amount->default_currency.' = '.get_amount($temData->data->amount->sender_cur_rate).' '.$temData->data->amount->sender_cur_code,
                        'total_charge'          => get_amount($temData->data->amount->total_charge).' '.$temData->data->amount->sender_cur_code,
                        'will_get'              => get_amount($temData->data->amount->will_get).' '.$temData->data->amount->default_currency,
                        'payable_amount'        => get_amount($temData->data->amount->total_amount).' '.$temData->data->amount->sender_cur_code,
                   ];
                   $data =[
                        'gategay_type'          => $payment_gateway->type,
                        'gateway_currency_name' => $payment_gateway_currency->name,
                        'alias'                 => $payment_gateway_currency->alias,
                        'identify'              => $temData->type,
                        'payment_informations'  => $payment_informations,
                        'url'                   => @$temData->data->response->links,
                        'method'                => "get",
                   ];
                   return Response::success(['Send Remittance Inserted Successfully'], $data);

               }else if($temData->type == PaymentGatewayConst::FLUTTER_WAVE) {
                   $payment_informations =[
                       'trx'                   => $temData->identifier,
                       'gateway_currency_name' => $payment_gateway_currency->name,
                       'request_amount'        => get_amount($temData->data->amount->requested_amount).' '.$temData->data->amount->default_currency,
                       'exchange_rate'         => "1".' '.$temData->data->amount->default_currency.' = '.get_amount($temData->data->amount->sender_cur_rate).' '.$temData->data->amount->sender_cur_code,
                       'total_charge'          => get_amount($temData->data->amount->total_charge).' '.$temData->data->amount->sender_cur_code,
                       'will_get'              => get_amount($temData->data->amount->will_get).' '.$temData->data->amount->default_currency,
                       'payable_amount'        => get_amount($temData->data->amount->total_amount).' '.$temData->data->amount->sender_cur_code,
                   ];
                   $data =[
                       'gateway_type'          => $payment_gateway->type,
                       'gateway_currency_name' => $payment_gateway_currency->name,
                       'alias'                 => $payment_gateway_currency->alias,
                       'identify'              => $temData->type,
                       'payment_informations'  => $payment_informations,
                       'url'                   => @$temData->data->response->link,
                       'method'                => "get",
                   ];

                   return Response::success(['Send Remittance Inserted Successfully'], $data);
                }elseif($temData->type == PaymentGatewayConst::SSLCOMMERZ) {

                    $payment_informations =[
                        'trx'                   =>  $temData->identifier,
                        'gateway_currency_name' =>  $payment_gateway_currency->name,
                        'request_amount'        => get_amount($temData->data->amount->requested_amount).' '.$temData->data->amount->default_currency,
                        'exchange_rate'         => "1".' '.$temData->data->amount->default_currency.' = '.get_amount($temData->data->amount->sender_cur_rate).' '.$temData->data->amount->sender_cur_code,
                        'total_charge'          => get_amount($temData->data->amount->total_charge).' '.$temData->data->amount->sender_cur_code,
                        'will_get'              => get_amount($temData->data->amount->will_get).' '.$temData->data->amount->default_currency,
                        'payable_amount'        =>  get_amount($temData->data->amount->total_amount).' '.$temData->data->amount->sender_cur_code,
                    ];
                    $data =[
                        'gateway_type' => $payment_gateway->type,
                        'gateway_currency_name' => $payment_gateway_currency->name,
                        'alias' => $payment_gateway_currency->alias,
                        'identify' => $temData->type,
                        'payment_informations' => $payment_informations,
                        'url' => $instance['response']['link'],
                        'method' => "get",
                    ];

                    return Response::success(['Send Remittance Inserted Successfully'],$data);
                }
           }elseif($payment_gateway->type == "MANUAL"){

                $payment_informations =[
                    'trx'                   => $temData->identifier,

                    'gateway_currency_name' => $payment_gateway_currency->name,
                    'request_amount'        => get_amount($temData->data->amount->requested_amount).' '.$temData->data->amount->default_currency,
                    'exchange_rate'         => "1".' '.$temData->data->amount->default_currency.' = '.get_amount($temData->data->amount->sender_cur_rate).' '.$temData->data->amount->sender_cur_code,
                    'total_charge'          => get_amount($temData->data->amount->total_charge).' '.$temData->data->amount->sender_cur_code,
                    'will_get'              => get_amount($temData->data->amount->will_get).' '.$temData->data->amount->default_currency,
                    'payable_amount'        => get_amount($temData->data->amount->total_amount).' '.$temData->data->amount->sender_cur_code,
                ];
                $data =[
                    'gategay_type'          => $payment_gateway->type,
                    'gateway_currency_name' => $payment_gateway_currency->name,
                    'alias'                 => $payment_gateway_currency->alias,
                    'identify'              => $temData->type,
                    'details'               => $payment_gateway->desc??null,
                    'input_fields'          => $payment_gateway->input_fields??null,
                    'payment_informations'  => $payment_informations,
                    'url'                   => route('api.user.manual.payment.confirmed'),
                    'method'                => "post",
                ];
                return Response::success(['Send Remittance Inserted Successfully'], $data);
           }else{
               $error = ['error'=>["Something is wrong"]];
               return Response::error($error);
           }

       }catch(Exception $e) {
           $error = ['error'=>[$e->getMessage()]];
           return Response::error($error);
       }
   }

   public function success(Request $request, $gateway){
       $requestData = $request->all();
       $token = $requestData['token'] ?? "";
       $checkTempData = TemporaryData::where("type", $gateway)->where("identifier", $token)->first();
       if (!$checkTempData){
           $message = ['error' => ["Transaction failed. Record didn\'t saved properly. Please try again."]];
           return Response::error($message);
       }

       $checkTempData = $checkTempData->toArray();

       try {

          $data = PaymentGatewayHelper::init($checkTempData)->type(PaymentGatewayConst::TYPESENDREMITTANCE)->responseReceiveApi();
       } catch (Exception $e) {

           $message = ['error' => [$e->getMessage()]];
           return Response::error($message);
       }
       $share_link   = route('share.link',$data);
       $download_link   = route('download.pdf',$data);
       return Response::success(["Payment successful, please go back your app"],[
        'share-link'   => $share_link,
        'download_link' => $download_link,
       ],200);
   }

   public function cancel(Request $request, $gateway)
   {
       $message = ['error' => ["Something is worng"]];
       return Response::error($message);
   }
   public function flutterwaveCallback(){

    $status = request()->status;

    if ($status ==  'successful'){

        $transactionID = Flutterwave::getTransactionIDFromCallback();
        $data = Flutterwave::verifyTransaction($transactionID);

        $requestData = request()->tx_ref;

        $token = $requestData;

        $checkTempData = TemporaryData::where("type",'flutterwave')->where("identifier",$token)->first();

        $message = ['error' => ['Transaction faild. Record didn\'t saved properly. Please try again.']];

        if(!$checkTempData) return Response::error($message);

        $checkTempData = $checkTempData->toArray();
        try{
           $data = PaymentGatewayHelper::init($checkTempData)->type(PaymentGatewayConst::TYPESENDREMITTANCE)->responseReceive();

        }catch(Exception $e) {
            $message = ['error' => [$e->getMessage()]];
            Response::error($message);
        }
        $share_link   = route('share.link',$data);
       $download_link   = route('download.pdf',$data);
       return Response::success(["Payment successful, please go back your app"],[
        'share-link'   => $share_link,
        'download_link' => $download_link,
       ],200);
    }
    elseif ($status ==  'cancelled'){
        Response::error(['Payment Cancelled']);
    }
    else{
        Response::error(['Payment Failed']);
    }
   }
     //stripe success
    public function stripePaymentSuccess($trx){
        $token = $trx;
        $checkTempData = TemporaryData::where("type",PaymentGatewayConst::STRIPE)->where("identifier",$token)->first();
        $message = ['error' => ['Transaction Failed. Record didn\'t saved properly. Please try again.']];

        if(!$checkTempData) return Response::error($message);
        $checkTempData = $checkTempData->toArray();

        try{

            $data = PaymentGatewayHelper::init($checkTempData)->type(PaymentGatewayConst::TYPESENDREMITTANCE)->responseReceive();
        }catch(Exception $e) {
            $message = ['error' => ["Something Is Wrong..."]];
            return Response::error($message);
        }
        $share_link   = route('share.link',$data);
       $download_link   = route('download.pdf',$data);
       return Response::success(["Payment successful, please go back your app"],[
        'share-link'   => $share_link,
        'download_link' => $download_link,
       ],200);

    }

    //sslcommerz success
    public function sllCommerzSuccess(Request $request){

        $data = $request->all();
        $token = $data['tran_id'];
        $checkTempData = TemporaryData::where("type",PaymentGatewayConst::SSLCOMMERZ)->where("identifier",$token)->first();
        $message = ['error' => ['Transaction Failed. Record didn\'t saved properly. Please try again.']];
        if(!$checkTempData) return Response::error($message);
        $checkTempData = $checkTempData->toArray();

        $creator_table = $checkTempData['data']->creator_table ?? null;
        $creator_id = $checkTempData['data']->creator_id ?? null;
        $creator_guard = $checkTempData['data']->creator_guard ?? null;
        $api_authenticated_guards = PaymentGatewayConst::apiAuthenticateGuard();
        if($creator_table != null && $creator_id != null && $creator_guard != null) {
            if(!array_key_exists($creator_guard,$api_authenticated_guards)) throw new Exception('Request user doesn\'t save properly. Please try again');
            $creator = DB::table($creator_table)->where("id",$creator_id)->first();
            if(!$creator) throw new Exception("Request user doesn\'t save properly. Please try again");
            $api_user_login_guard = $api_authenticated_guards[$creator_guard];
            Auth::guard($api_user_login_guard)->loginUsingId($creator->id);
        }
        if( $data['status'] != "VALID"){
            $message = ['error' => ["Added Money Failed"]];
            return Response::error($message);
        }
        try{
           $data = PaymentGatewayHelper::init($checkTempData)->type(PaymentGatewayConst::TYPESENDREMITTANCE)->responseReceive();
        }catch(Exception $e) {
            $message = ['error' => ["Something Is Wrong..."]];
            return Response::error($message);
        }
        $share_link   = route('share.link',$data);
        $download_link   = route('download.pdf',$data);
        return Response::success(["Payment successful, please go back your app"],[
            'share-link'   => $share_link,
            'download_link' => $download_link,
        ],200);
    }
    //sslCommerz fails
    public function sllCommerzFails(Request $request){
        $data = $request->all();

        $token = $data['tran_id'];
        $checkTempData = TemporaryData::where("type",PaymentGatewayConst::SSLCOMMERZ)->where("identifier",$token)->first();
        $message = ['error' => ['Transaction Failed. Record didn\'t saved properly. Please try again.']];
        if(!$checkTempData) return Response::error($message);
        $checkTempData = $checkTempData->toArray();

        $creator_table = $checkTempData['data']->creator_table ?? null;
        $creator_id = $checkTempData['data']->creator_id ?? null;
        $creator_guard = $checkTempData['data']->creator_guard ?? null;

        $api_authenticated_guards = PaymentGatewayConst::apiAuthenticateGuard();
        if($creator_table != null && $creator_id != null && $creator_guard != null) {
            if(!array_key_exists($creator_guard,$api_authenticated_guards)) throw new Exception('Request user doesn\'t save properly. Please try again');
            $creator = DB::table($creator_table)->where("id",$creator_id)->first();
            if(!$creator) throw new Exception("Request user doesn\'t save properly. Please try again");
            $api_user_login_guard = $api_authenticated_guards[$creator_guard];
            Auth::guard($api_user_login_guard)->loginUsingId($creator->id);
        }
        if( $data['status'] == "FAILED"){
            TemporaryData::destroy($checkTempData['id']);
            $message = ['error' => ["Send Remittance Failed"]];
            return Response::error($message);
        }

    }
    //sslCommerz canceled
    public function sllCommerzCancel(Request $request){
        $data = $request->all();
        $token = $data['tran_id'];
        $checkTempData = TemporaryData::where("type",PaymentGatewayConst::SSLCOMMERZ)->where("identifier",$token)->first();
        $message = ['error' => ['Transaction Failed. Record didn\'t saved properly. Please try again.']];
        if(!$checkTempData) return Response::error($message);
        $checkTempData = $checkTempData->toArray();


        $creator_table = $checkTempData['data']->creator_table ?? null;
        $creator_id = $checkTempData['data']->creator_id ?? null;
        $creator_guard = $checkTempData['data']->creator_guard ?? null;

        $api_authenticated_guards = PaymentGatewayConst::apiAuthenticateGuard();
        if($creator_table != null && $creator_id != null && $creator_guard != null) {
            if(!array_key_exists($creator_guard,$api_authenticated_guards)) throw new Exception('Request user doesn\'t save properly. Please try again');
            $creator = DB::table($creator_table)->where("id",$creator_id)->first();
            if(!$creator) throw new Exception("Request user doesn\'t save properly. Please try again");
            $api_user_login_guard = $api_authenticated_guards[$creator_guard];
            Auth::guard($api_user_login_guard)->loginUsingId($creator->id);
        }
        if( $data['status'] != "VALID"){
            TemporaryData::destroy($checkTempData['id']);
            $message = ['error' => ["Send Remittance Canceled"]];
            return Response::error($message);
        }
    }
    /**
     * razor pay callback
     */
    public function razorCallback(){
        $request_data = request()->all();
        //if payment is successful
        if ($request_data['razorpay_payment_link_status'] ==  'paid') {
            $token = $request_data['razorpay_payment_link_reference_id'];

            $checkTempData = TemporaryData::where("type",PaymentGatewayConst::RAZORPAY)->where("identifier",$token)->first();
            if(!$checkTempData) {

                return Response::error(['Transaction Failed. Record didn\'t saved properly. Please try again.'],404);
            }
            $checkTempData = $checkTempData->toArray();
            try{
                $data = PaymentGatewayHelper::init($checkTempData)->type(PaymentGatewayConst::TYPESENDREMITTANCE)->responseReceive();
            }catch(Exception $e) {
                $message = ['error' => [$e->getMessage()]];
                return Response::error($message);
            }
            $share_link   = route('share.link',$data);
            $download_link   = route('download.pdf',$data);
            return Response::success(["Payment successful, please go back your app"],[
                'share-link'   => $share_link,
                'download_link' => $download_link,
            ],200);

        }
        else{
            $message = ['error' => ['Payment Failed']];
            return Response::error($message);
        }
    }

}
