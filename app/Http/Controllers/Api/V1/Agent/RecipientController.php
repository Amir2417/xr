<?php

namespace App\Http\Controllers\Api\V1\Agent;

use Exception;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Constants\GlobalConst;
use App\Http\Helpers\Response;
use App\Models\Admin\Currency;
use App\Models\Admin\CashPickup;
use App\Models\Admin\MobileMethod;
use App\Http\Controllers\Controller;
use App\Models\Admin\RemittanceBank;
use App\Models\Agent\AgentRecipient;
use App\Models\Admin\BankMethodAutomatic;
use Illuminate\Support\Facades\Validator;

class RecipientController extends Controller
{
    /**
     * Method for all recipient
     * @return response
     */
    public function index(){
        $recipients = AgentRecipient::auth()->orderBy('id','desc')->get()->map(function($data){
            return [
                'slug'              => $data->slug,
                'full_name'         => $data->fullname,
                'email'             => $data->email,
                'phone'             => $data->phone,
                'country'           => $data->country,
                'method'            => $data->method,
                'bank_name'         => $data->bank_name,
                'iban_number'       => $data->iban_number,
                'mobile_name'       => $data->mobile_name,
                'account_number'    => $data->account_number,
                'pickup_point'      => $data->pickup_point,
            ];
        });

        return Response::success(['Recipients data fetch successfully.'],[
            'recipients'    => $recipients
        ],200);
    }
    /**
     * Method for check user
     * @param Illuminate\Http\Request $request
     */
    public function checkUser(Request $request){
        $validator          = Validator::make($request->all(),[
            'email'         => 'required|email'
        ]);
        if($validator->fails()) return Response::validation(['error' => $validator->errors()->all()]);

        $validated          = $validator->validate();
        $user_data          = User::where('email',$validated['email'])->first();
        if(!$user_data) return Response::error(['User not exists!'],[],400);
        $data               = [
            'first_name'    => $user_data->firstname,
            'last_name'     => $user_data->lastname,
            'email'         => $user_data->email,
            'phone'         => $user_data->full_mobile ?? '',
            'country'       => $user_data->address->country ?? '',
            'state'         => $user_data->address->state ?? '',
            'city'          => $user_data->address->city ?? '',
            'zip_code'      => $user_data->address->zip ?? '',
        ];

        return Response::success(['User data fetch successfully.'],[
            'user_data'     => $data
        ],200);
    }
    /**
     * Method for get bank list
     * @param Illuminate\Http\Request $request
     */
    public function getBankList(Request $request){
        $validator      = Validator::make($request->all(),[
            'country'   => 'required|string'
        ]);
        if($validator->fails()) return Response::error($validator->errors()->all());
        $validated              = $validator->validate();
        $user_country           = Currency::where('country',$request->country)->first();
        $country                = get_specific_country($user_country->country);
        $bank_method_automatic  = BankMethodAutomatic::where('status',true)->first();
        if(!$bank_method_automatic){
            $automatic_bank_list  = [];
        }else{
            $automatic_bank_list  = getFlutterwaveBanks($country['country_code']) ?? [];
        }
        $manual_bank_list      = RemittanceBank::where('country',$validated['country'])->where('status',true)->get();
        if($manual_bank_list->isNotEmpty()){
            $manual_bank_list->each(function($bank){
                $bank->name = $bank->name . "(Manual)";
            });
            $manual_bank_list_array     = $manual_bank_list->toArray();
        }else{
            $manual_bank_list_array     = [];
        }
        $bank_list      = array_merge($automatic_bank_list,$manual_bank_list_array);

        return Response::success(['Bank list data fetch successfully.'],['bank_list' => $bank_list],200);
    }
    /**
     * Method for get the pickup points
     * @param Illuminate\Http\Request $request
     */
    public function getPickupPointList(Request $request){
        $validator              = Validator::make($request->all(),[
            'country'           => 'required|string'
        ]);
        if($validator->fails()) return Response::error($validator->errors()->all());
        $validated              = $validator->validate();
        $pickup_points          = CashPickup::where('country',$validated['country'])->where('status',true)->get();
        if($pickup_points->isNotEmpty()){
            $pickup_points->each(function($pickup_point){
                $pickup_point->address = $pickup_point->address . "(Manual)";
            });
        }
        return Response::success(['Pickup point list data fetch successfully.'],['pickup_points' => $pickup_points],200);
    }
    /**
     * Method for get the mobile method list
     * @param Illuminate\Http\Request $request
     */
    public function getMobileMethodList(Request $request){
        $validator              = Validator::make($request->all(),[
            'country'           => 'required|string'
        ]);
        if($validator->fails()) return Response::error($validator->errors()->all());
        $validated              = $validator->validate();
        $mobile_methods         = MobileMethod::where('country',$validated['country'])->where('status',true)->get();
        if($mobile_methods->isNotEmpty()){
            $mobile_methods->each(function($mobile_method){
                $mobile_method->name = $mobile_method->name . "(Manual)";
            });
        }

        return Response::success(['Mobile method list data fetch successfully.'],['mobile_methods' => $mobile_methods],200);
    }
    /**
     * Method for get the basic data 
     * @return response
     */
    public function basicData(){
        $receiver_country       = Currency::where('receiver',true)->get()->map(function($data){
            return [
                'country'       => $data->country,
            ];
        });
        $method_list        = [
            'Bank Transfer',
            'Cash Pickup',
            'Mobile Money'
        ];

        return Response::success(['Basic data fetch successfully.'],['receiver_country' => $receiver_country,'method_list' => $method_list],200);
    }
    /**
     * Method for save recipient information
     * @param Illuminate\Http\Request $request
     */
    public function store(Request $request){
        $validator          = Validator::make($request->all(),[
            'method'        => 'required|string',
            'country'       => 'required',
            'email'         => 'required|string',
            'phone'         => 'required|string',
            'first_name'    => 'required|string',
            'last_name'     => 'required|string',
            'address'       => 'required|string',
            'state'         => 'required|string',
            'city'          => 'required|string',
            'zip_code'      => 'required|string',
            'bank_name'     => 'required_if:method,'.GlobalConst::TRANSACTION_TYPE_BANK,
            'iban_number'   => 'required_if:method,'.GlobalConst::TRANSACTION_TYPE_BANK,
            'mobile_name'   => 'required_if:method,'.GlobalConst::TRANSACTION_TYPE_MOBILE,
            'account_number'=> 'required_if:method,'.GlobalConst::TRANSACTION_TYPE_MOBILE,
            'pickup_point'  => 'required_if:method,'.GlobalConst::TRANSACTION_TYPE_CASHPICKUP,
        ]); 
        
        if($validator->fails())  return Response::validation(['error' => $validator->errors()->all()]);
        $validated              = $validator->validate();
        $validated['slug']      = Str::uuid();
        $exists_data            = $this->checkRecipientExistsOrNot($validated);
        if($exists_data) return Response::error($exists_data,[],400);
        $validated['agent_id']      = auth()->user()->id;
        
        try{
            $recipient = AgentRecipient::create($validated);
        }catch(Exception $e){
            return Response::error(['Something went wrong. Please try again'],[],400);  
        }
        return Response::success(['Recipient created successfully'],[
            'data'  => $recipient
        ],200);
        

    }
    /**
     * Function for check recipient user is exists or not
     * @param $method
     */
    function checkRecipientExistsOrNot($validated){
        if($validated['method'] == GlobalConst::TRANSACTION_TYPE_BANK){
            if(AgentRecipient::auth()->where('method',$validated['method'])->where('bank_name',$validated['bank_name'])->where('iban_number',$validated['iban_number'])->exists()){
                $data = 'Recipient already exists.';
                return $data;
            }
        }else if($validated['method'] == GlobalConst::TRANSACTION_TYPE_MOBILE){
            if(AgentRecipient::auth()->where('method',$validated['method'])->where('mobile_name',$validated['mobile_name'])->where('account_number',$validated['account_number'])->exists()){
                $data = 'Recipient already exists.';
                return $data;
            }
        }else{
            if(AgentRecipient::auth()->where('method',$validated['method'])->where('pickup_point',$validated['pickup_point'])->exists()){
                $data = 'Recipient already exists.';
                return $data;
            }
        }
    }
    /**
     * Function for check recipient user is exists or not
     * @param $method
     */
    function checkRecipientExistsOrNotForUpdate($validated){
        if($validated['method'] == GlobalConst::TRANSACTION_TYPE_BANK){
            if(AgentRecipient::auth()->whereNot('slug',$validated['slug'])->where(function($q) use ($validated){
                $q->where('method',$validated['method'])->where('bank_name',$validated['bank_name'])->where('iban_number',$validated['iban_number']);
            })->exists()){
                $data = 'Recipient already exists.';
                return $data;
            }
        }else if($validated['method'] == GlobalConst::TRANSACTION_TYPE_MOBILE){
            if(AgentRecipient::auth()->whereNot('slug',$validated['slug'])->where(function($q) use ($validated){
                $q->where('method',$validated['method'])->where('mobile_name',$validated['mobile_name'])->where('account_number',$validated['account_number']);
            })->exists()){
                $data = 'Recipient already exists.';
                return $data;
            }
        }else{
            if(AgentRecipient::auth()->whereNot('slug',$validated['slug'])->where(function($q) use ($validated){
                $q->where('method',$validated['method'])->where('pickup_point',$validated['pickup_point']);
            })->exists()){
                $data = 'Recipient already exists.';
                return $data;
            }
        }
    }
    /**
     * Method for update recipient information
     * @param Illuminate\Http\Request $request
     */
    public function update(Request $request){
        $validator          = Validator::make($request->all(),[
            'slug'          => 'required|string',
            'method'        => 'required|string',
            'country'       => 'required',
            'email'         => 'required|string',
            'phone'         => 'required|string',
            'first_name'    => 'required|string',
            'last_name'     => 'required|string',
            'address'       => 'required|string',
            'state'         => 'required|string',
            'city'          => 'required|string',
            'zip_code'      => 'required|string',
            'bank_name'     => 'required_if:method,'.GlobalConst::TRANSACTION_TYPE_BANK,
            'iban_number'   => 'required_if:method,'.GlobalConst::TRANSACTION_TYPE_BANK,
            'mobile_name'   => 'required_if:method,'.GlobalConst::TRANSACTION_TYPE_MOBILE,
            'account_number'=> 'required_if:method,'.GlobalConst::TRANSACTION_TYPE_MOBILE,
            'pickup_point'  => 'required_if:method,'.GlobalConst::TRANSACTION_TYPE_CASHPICKUP,
        ]); 
        
        if($validator->fails())  return Response::validation(['error' => $validator->errors()->all()]);
        $validated              = $validator->validate();
        $recipient              = AgentRecipient::auth()->where('slug',$validated['slug'])->first();
        if(!$recipient) return Response::error(['Recipient not found.'],[],400);
        $exists_data            = $this->checkRecipientExistsOrNotForUpdate($validated);
        if($exists_data) return Response::error($exists_data,[],400);
        try{
            $recipient->update($validated);
        }catch(Exception $e){
            return Response::error(['Something went wrong. Please try again!'],[],400);
        }
        return Response::success(['Recipient updated successfully'],[
            'data'  => $recipient
        ],200);
    }
    /**
     * Method for delete recipient information
     * @param Illuminate\Http\Request $request
     */
    public function delete(Request $request){
        $validator  = Validator::make($request->all(),[
            'slug'  => 'required|string',
        ]);
        if($validator->fails()) return Response::validation(['error' => $validator->errors()->all()]);
        $recipient  = AgentRecipient::auth()->where('slug',$request->slug)->first();
        if(!$recipient) return Response::error(['Recipient not found.'],[],400);
        try{
            $recipient->delete();
        }catch(Exception $e){
            return Response::error(['Something went wrong. Please try again!'],[],400);
        }
        return Response::success(['Recipient deleted successfully'],[],200);
    }
}
