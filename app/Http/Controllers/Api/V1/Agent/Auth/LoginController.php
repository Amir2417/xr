<?php

namespace App\Http\Controllers\Api\V1\Agent\Auth;

use Exception;
use App\Models\Agent;
use Illuminate\Http\Request;
use App\Constants\GlobalConst;
use App\Http\Helpers\Response;
use App\Models\Admin\SetupKyc;
use Illuminate\Support\Facades\DB;
use App\Traits\Agent\LoggedInUsers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Traits\Agent\RegisteredUsers;
use App\Traits\ControlDynamicInputFields;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use App\Providers\Admin\BasicSettingsProvider;

class LoginController extends Controller
{
    use LoggedInUsers,ControlDynamicInputFields,RegisteredUsers;
    protected $basic_settings;

    public function __construct()
    {
        $this->basic_settings = BasicSettingsProvider::get();
    }
    /**
     * Method for log in
     */
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:50',
            'password' => 'required|min:6',
        ]);

        if($validator->fails()){
            return Response::error($validator->errors()->all(),[]);
        }
        $user = Agent::where('email',$request->email)->first();
        if(!$user){
            return Response::error(["Agent doesn't exists."],[],404);
        }
        if (Hash::check($request->password, $user->password)) {
            if($user->status == 0){
                return Response::error(["Account Has been Suspended."],[],404);
            }
            $user->two_factor_verified = false;
            $user->save();
            $this->refreshUserWallets($user);
            $this->createLoginLog($user);
            
            $token = $user->createToken('agent_token')->accessToken;
            $data = ['token' => $token, 'agent' => $user, ];
            return Response::success(['Login Successful'],$data,200);

        } else {
            return Response::error(["Incorrect Password."],[],404);
        }

    }
    /**
     * Method for logout
     */
    public function logout(){
        Auth::user()->token()->revoke();
        return Response::success(['Logout Successful'],[],200);

    }
    /**
     * Method for register
     */
    public function register(Request $request){
        $basic_settings = $this->basic_settings;
        $passowrd_rule = "required|string|min:6|confirmed";
        if($basic_settings->agent_secure_password) {
            $passowrd_rule = ["required","confirmed",Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised()];
        }
        if( $basic_settings->agent_agree_policy){
            $agree ='required';
        }else{
            $agree ='';
        }

        $validator = Validator::make($request->all(), [
            'firstname'     => 'required|string|max:60',
            'lastname'      => 'required|string|max:60',
            'store_name'    => 'required|string|max:100',
            'email'         => 'required|string|email|max:150|unique:agents,email',
            'password'      => $passowrd_rule,
            'country'       => 'required|string|max:150',
            'city'          => 'required|string|max:150',
            'phone'         => 'required|string|max:20',
            'zip_code'      => 'required|string|max:8',
            'agree'         =>  $agree,

        ]);
        if($validator->fails()){
            return Response::error($validator->errors()->all(),[]);
        }
        if($basic_settings->agent_kyc_verification == true){
            $user_kyc_fields = SetupKyc::agentKyc()->first()->fields ?? [];
            $validation_rules = $this->generateValidationRules($user_kyc_fields);
            $validated = Validator::make($request->all(), $validation_rules);

            if ($validated->fails()) {
                return Response::error($validator->errors()->all(),[]);
            }
            $validated = $validated->validate();
            $get_values = $this->registerPlaceValueWithFields($user_kyc_fields, $validated);
        }
        $data               = $request->all();
        $mobile             = remove_speacial_char($data['phone']);
        $complete_phone     = $mobile;

        $check_agent = Agent::where('mobile',$mobile)->orWhere('full_mobile',$complete_phone)->orWhere('email',$data['email'])->first();
        if($check_agent){
            return Response::error(["Agent doesn't exists."],[],404);
        }
        $userName = make_username($data['firstname'],$data['lastname']);
        $check_user_name = Agent::where('username',$userName)->first();
        if($check_user_name){
            $userName = $userName.'-'.rand(123,456);
        }
        //Agent Create
        $user = new Agent();
        $user->firstname = isset($data['firstname']) ? $data['firstname'] : null;
        $user->lastname = isset($data['lastname']) ? $data['lastname'] : null;
        $user->store_name = isset($data['store_name']) ? $data['store_name'] : null;
        $user->email = strtolower(trim($data['email']));
        $user->mobile =  $mobile;
        $user->full_mobile =    $complete_phone;
        $user->password = Hash::make($data['password']);
        $user->username = $userName;
        $user->address = [
            'address' => isset($data['address']) ? $data['address'] : '',
            'city' => isset($data['city']) ? $data['city'] : '',
            'zip' => isset($data['zip_code']) ? $data['zip_code'] : '',
            'country' =>isset($data['country']) ? $data['country'] : '',
            'state' => isset($data['state']) ? $data['state'] : '',
        ];
        $user->status = 1;
        $user->email_verified =  true;
        $user->sms_verified =  ($basic_settings->sms_verification == true) ? true : true;
        $user->kyc_verified =  ($basic_settings->agent_kyc_verification == true) ? false : true;
        $user->save();
        if( $user && $basic_settings->agent_kyc_verification == true){
            $create = [
                'agent_id'       => $user->id,
                'data'          => json_encode($get_values),
                'created_at'    => now(),
            ];

            DB::beginTransaction();
            try{
                DB::table('agent_kyc_data')->updateOrInsert(["agent_id" => $user->id],$create);
                $user->update([
                    'kyc_verified'  => GlobalConst::PENDING,
                ]);
                DB::commit();
            }catch(Exception $e) {
                DB::rollBack();
                $user->update([
                    'kyc_verified'  => GlobalConst::DEFAULT,
                ]);
                return Response::error(["Something went wrong! Please try again."],[],404);
            }

           }
        $token = $user->createToken('agent_token')->accessToken;
        $this->createUserWallets($user);

        $data = ['token' => $token, 'agent' => $user, ];
        return Response::success(['Registration Successful'],$data,200);

    }
}
