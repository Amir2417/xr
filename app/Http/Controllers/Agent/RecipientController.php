<?php

namespace App\Http\Controllers\Agent;

use Exception;
use App\Models\User;
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
use Illuminate\Validation\ValidationException;

class RecipientController extends Controller
{
    /**
     * Method for view recipient page
     * @return view
     */
    public function index(){
        $page_title         = "My Recipient";
        $recipients         = AgentRecipient::auth()->orderBy('id','desc')->get();

        return view('agent.sections.recipient.index',compact(
            'page_title',
            'recipients'
        ));
    }
    /**
     * Method for view create recipient page
     * @return view
     */
    public function create(){
        $page_title         = "Add New Recipient";
        $receiver_country   = Currency::active()->receiver()->get();
        
        return view('agent.sections.recipient.create',compact(
            'page_title',
            'receiver_country'
        ));
    }
    /**
     * Method for get user data
     * @param Illuminate\Http\Request $request
     */
    public function getUserData(Request $request){
        $validator          = Validator::make($request->all(),[
            'search'        => 'required'
        ]);
        if($validator->fails()) return Response::error($validator->errors()->all());
        $validated          = $validator->validate();
        $user_data          = User::where('email',$validated['search'])->first();
        
        return Response::success(['User data get successfully.'],['user_data' => $user_data],200);
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
     * Method for store agent recipient information
     * @param Illuminate\Http\Request $request
     */
    public function store(Request $request){
        if($request->method == GlobalConst::RECIPIENT_METHOD_BANK){
            if(isset($request->register_user)){
                $country        = "nullable";
                $country_name   = "required";

            }else{
                $country        = "required";
                $country_name   = "nullable";
            }
            $validator          = Validator::make($request->all(),[
                'method'        => 'required|string',
                'country'       => $country,
                'country_name'  => $country_name,
                'email'         => 'required|string',
                'phone'         => 'required|string',
                'first_name'    => 'required|string',
                'last_name'     => 'required|string',
                'address'       => 'required|string',
                'state'         => 'required|string',
                'city'          => 'required|string',
                'zip_code'      => 'required|string',
                'bank_name'     => 'required|string',
                'iban_number'   => 'required|string'
            ]);
            if($validator->fails()) return back()->withErrors($validator)->withInput($request->all());
            $validated          = $validator->validate();

            $validated['method']    = GlobalConst::TRANSACTION_TYPE_BANK;
            if(AgentRecipient::auth()->where('method',$validated['method'])->where('bank_name',$validated['bank_name'])->where('iban_number',$validated['iban_number'])->exists()){
                throw ValidationException::withMessages([
                    'name'  => __("Recipient already exists")
                ]);
            }
            if(isset($request->register_user)){
                $validated['country']   = $validated['country_name'];
            }else{
                $validated['country']   = $validated['country'];
            }
            $validated['agent_id']      = auth()->user()->id;
            $validated['method']        = GlobalConst::TRANSACTION_TYPE_BANK;
            try{
                AgentRecipient::create($validated);
            }catch(Exception $e){
                return back()->with(['error' => ['Something went wrong! Please try again.']]);
            }
            return redirect()->route('agent.recipient.index')->with(['success' => ['Recipient created successfully.']]);
        }elseif($request->method == GlobalConst::RECIPIENT_METHOD_MOBILE){
            if(isset($request->register_user)){
                $country        = "nullable";
                $country_name   = "required";

            }else{
                $country        = "required";
                $country_name   = "nullable";
            }
            $validator          = Validator::make($request->all(),[
                'method'        => 'required|string',
                'country'       => $country,
                'country_name'  => $country_name,
                'email'         => 'required|string',
                'phone'         => 'required|string',
                'first_name'    => 'required|string',
                'last_name'     => 'required|string',
                'address'       => 'required|string',
                'state'         => 'required|string',
                'city'          => 'required|string',
                'zip_code'      => 'required|string',
                'mobile_name'   => 'required|string',
                'account_number'=> 'required|string'
            ]);
            if($validator->fails()) return back()->withErrors($validator)->withInput($request->all());
            $validated          = $validator->validate();

            $validated['method']    = GlobalConst::TRANSACTION_TYPE_MOBILE;
            if(AgentRecipient::auth()->where('method',$validated['method'])->where('mobile_name',$validated['mobile_name'])->where('account_number',$validated['account_number'])->exists()){
                throw ValidationException::withMessages([
                    'name'  => __("Recipient already exists")
                ]);
            }
            if(isset($request->register_user)){
                $validated['country']   = $validated['country_name'];
            }else{
                $validated['country']   = $validated['country'];
            }
            $validated['agent_id']      = auth()->user()->id;
            $validated['method']        = GlobalConst::TRANSACTION_TYPE_MOBILE;
            try{
                AgentRecipient::create($validated);
            }catch(Exception $e){
                return back()->with(['error' => ['Something went wrong! Please try again.']]);
            }
            return redirect()->route('agent.recipient.index')->with(['success' => ['Recipient created successfully.']]);
        }else{
            if(isset($request->register_user)){
                $country        = "nullable";
                $country_name   = "required";

            }else{
                $country        = "required";
                $country_name   = "nullable";
            }
            $validator          = Validator::make($request->all(),[
                'method'        => 'required|string',
                'country'       => $country,
                'country_name'  => $country_name,
                'email'         => 'required|string',
                'phone'         => 'required|string',
                'first_name'    => 'required|string',
                'last_name'     => 'required|string',
                'address'       => 'required|string',
                'state'         => 'required|string',
                'city'          => 'required|string',
                'zip_code'      => 'required|string',
                'pickup_point'  => 'required|string',
            ]);
            if($validator->fails()) return back()->withErrors($validator)->withInput($request->all());
            $validated          = $validator->validate();

            $validated['method']    = GlobalConst::TRANSACTION_TYPE_CASHPICKUP;
            if(AgentRecipient::auth()->where('method',$validated['method'])->where('pickup_point',$validated['pickup_point'])->exists()){
                throw ValidationException::withMessages([
                    'name'  => __("Recipient already exists")
                ]);
            }
            if(isset($request->register_user)){
                $validated['country']   = $validated['country_name'];
            }else{
                $validated['country']   = $validated['country'];
            }
            $validated['agent_id']      = auth()->user()->id;
            $validated['method']        = GlobalConst::TRANSACTION_TYPE_CASHPICKUP;
            try{
                AgentRecipient::create($validated);
            }catch(Exception $e){
                return back()->with(['error' => ['Something went wrong! Please try again.']]);
            }
            return redirect()->route('agent.recipient.index')->with(['success' => ['Recipient created successfully.']]);
        }
    }
}
