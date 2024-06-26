<?php

namespace App\Http\Controllers\Agent;

use App\Constants\GlobalConst;
use Illuminate\Http\Request;
use App\Http\Helpers\Response;
use App\Models\Admin\Currency;
use App\Http\Controllers\Controller;
use App\Models\Admin\RemittanceBank;
use App\Models\Agent\AgentRecipient;
use App\Models\Admin\BankMethodAutomatic;
use App\Models\Admin\CashPickup;
use App\Models\Admin\MobileMethod;
use Illuminate\Support\Facades\Validator;

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
     * Method for view add recipient page
     * @return view
     */
    public function add(){
        $page_title         = "Add New Recipient";
        $receiver_country   = Currency::where('receiver',true)->where('status',true)->get();
        
        return view('agent.sections.recipient.add',compact(
            'page_title',
            'receiver_country'
        ));
    }
    /**
     * Method for get bank list
     * @param $country
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
     * @param $country
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
     * @param $country
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
            $validator          = Validator::make($request->all(),[
                'method'        => 'required|string',
                'country'       => 'required|string',
                'email'         => 'required|string',
                'phone'         => 'required"string',
                'first_name'    => 'required|string',
                'last_name'     => 'required|string',
                'address'       => 'required|string',
                'state'         => 'required|string',
                'city'          => 'required|string',
                'zip_code'      => 'required|string',
                'bank_name'     => 'required|string'
            ]);
            if($validator->fails()) return 
        }
    }
}
