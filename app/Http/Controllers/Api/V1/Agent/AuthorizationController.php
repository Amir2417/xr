<?php

namespace App\Http\Controllers\Api\V1\Agent;

use Exception;
use Carbon\Carbon;
use App\Models\Agent;
use Illuminate\Http\Request;
use App\Constants\GlobalConst;
use App\Http\Helpers\Response;
use App\Models\AgentAuthorization;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use App\Providers\Admin\BasicSettingsProvider;
use App\Notifications\Agent\Auth\SendVerifyCode;
use App\Notifications\Agent\Auth\SendAuthorizationCode;

class AuthorizationController extends Controller
{
    protected $basic_settings;

    public function __construct()
    {
        $this->basic_settings = BasicSettingsProvider::get();
    }
    /*********************** Before Register ********************* */
    public function checkExist(Request $request){
        $validator = Validator::make($request->all(), [
            'email'     => 'required|email',
        ]);
        if($validator->fails()){
            return Response::error($validator->errors()->all(),[]);
        }
        $column = "email";
        if(check_email($request->email)) $column = "email";
        $user = Agent::where($column,$request->email)->first();
        if($user){
            return Response::error(['Agent already exist, please select another email address'],[],404);
        }
        return Response::success(['Now,You can register'],[],200);

    }
    public function sendEmailOtp(Request $request){
        $basic_settings = $this->basic_settings;
        if($basic_settings->agree_policy){
            $agree = 'required';
        }else{
            $agree = '';
        }
        if( $request->agree != 1){
            return Response::error(['Terms Of Use & Privacy Policy Field Is Required!'],[],404);
        }
        $validator = Validator::make($request->all(), [
            'email'         => 'required|email',
            'agree'         =>  $agree,
        ]);
        if($validator->fails()){
            return Response::error($validator->errors()->all(),[]);
        }
        $validated = $validator->validate();

        $field_name = "username";
        if(check_email($validated['email'])) {
            $field_name = "email";
        }
        $exist = Agent::where($field_name,$validated['email'])->active()->first();
        if( $exist){
            return Response::error(['Agent already exist, please select another email address'],[],404);
        }

        $code = generate_random_code();
        $data = [
            'agent_id'       =>  0,
            'email'         => $validated['email'],
            'code'          => $code,
            'token'         => generate_unique_string("agent_authorizations","token",200),
            'created_at'    => now(),
        ];
        DB::beginTransaction();
        try{
            $oldToken = AgentAuthorization::where("email",$validated['email'])->get();
            if($oldToken){
                foreach($oldToken as $token){
                    $token->delete();
                }
            }
            DB::table("agent_authorizations")->insert($data);
            if($basic_settings->agent_email_notification == true && $basic_settings->agent_email_verification == true){
                Notification::route("mail",$validated['email'])->notify(new SendVerifyCode($validated['email'], $code));
            }
            DB::commit();
        }catch(Exception $e) {
            DB::rollBack();
            return Response::error(['Something went wrong! Please try again.'],[],404);
        };
        return Response::success(['Verification code sended to your email address.'],[],200);
    }
    public function verifyEmailOtp(Request $request){
        $validator = Validator::make($request->all(), [
            'email'     => "required|email",
            'code'    => "required|max:6",
        ]);
        if($validator->fails()){
            return Response::error($validator->errors()->all(),[]);
        }
        $code = $request->code;
        $otp_exp_sec = BasicSettingsProvider::get()->agent_otp_exp_seconds ?? GlobalConst::DEFAULT_TOKEN_EXP_SEC;
        $auth_column = AgentAuthorization::where("email",$request->email)->first();
        if(!$auth_column){
            return Response::error(['Invalid request.'],[],404);
        }
        if( $auth_column->code != $code){
            return Response::error(['The verification code does not match.'],[],404);
        }
        if($auth_column->created_at->addSeconds($otp_exp_sec) < now()) {
            $auth_column->delete();
            return Response::error(['Verification code is expired.'],[],404);
        }
        try{
            $auth_column->delete();
        }catch(Exception $e) {
            return Response::error(['Something went wrong! Please try again.'],[],404);
        }
        return Response::success(['Otp successfully verified.'],[],200);
    }
    public function resendEmailOtp(Request $request){
        $validator = Validator::make($request->all(), [
            'email'     => "required|email",
        ]);
        if($validator->fails()){
            return Response::error($validator->errors()->all(),[]);
        }
        $resend = AgentAuthorization::where("email",$request->email)->first();
        if($resend){
            if(Carbon::now() <= $resend->created_at->addMinutes(GlobalConst::USER_VERIFY_RESEND_TIME_MINUTE)) {
                $error = ['You can resend verification code after '.Carbon::now()->diffInSeconds($resend->created_at->addMinutes(GlobalConst::USER_PASS_RESEND_TIME_MINUTE)). ' seconds'];
                return Response::error($error,[],400);
            }
        }
        $code = generate_random_code();
        $data = [
            'agent_id'       =>  0,
            'email'         => $request->email,
            'code'          => $code,
            'token'         => generate_unique_string("agent_authorizations","token",200),
            'created_at'    => now(),
        ];
        DB::beginTransaction();
        try{
            $oldToken = AgentAuthorization::where("email",$request->email)->get();
            if($oldToken){
                foreach($oldToken as $token){
                    $token->delete();
                }
            }
            DB::table("agent_authorizations")->insert($data);
            Notification::route("mail",$request->email)->notify(new SendVerifyCode($request->email, $code));
            DB::commit();
        }catch(Exception $e) {
            DB::rollBack();
            return Response::error(['Something went wrong! Please try again.'],[],404);
        }
        return Response::success(['Verification code resend success.'],[],200);

    }

    /************************ Authorize Email ************* */
    /**
     * Method for send email otp code.
     */
    public function sendMailCode()
    {
        $user = auth()->user();
        $resend = AgentAuthorization::where("agent_id",$user->id)->first();
        if( $resend){
            if(Carbon::now() <= $resend->created_at->addMinutes(GlobalConst::USER_VERIFY_RESEND_TIME_MINUTE)) {
                $error = ['You can resend verification code after '.Carbon::now()->diffInSeconds($resend->created_at->addMinutes(GlobalConst::USER_PASS_RESEND_TIME_MINUTE)). ' seconds'];
                return Response::error($error,[],400);
            }
        }
        $data = [
            'agent_id'       =>  $user->id,
            'code'          => generate_random_code(),
            'token'         => generate_unique_string("agent_authorizations","token",200),
            'created_at'    => now(),
        ];
        DB::beginTransaction();
        try{
            if($resend) {
                AgentAuthorization::where("agent_id", $user->id)->delete();
            }
            DB::table("agent_authorizations")->insert($data);
            $user->notify(new SendAuthorizationCode((object) $data));
            DB::commit();
            return Response::success(['Verification code sended to your email address.'],[],200);
        }catch(Exception $e) {
            DB::rollBack();
            return Response::error(['Something went wrong! Please try again.'],[],404);
        }
    }
    /**
     * Method for verifying email.
     * @param Illuminate\Http\Request $request
     */
    public function mailVerify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|numeric',
        ]);
        if($validator->fails()){
            return Response::error($validator->errors()->all(),[]);
        }
        $user = auth()->user();
        $code = $request->code;
        $otp_exp_sec = BasicSettingsProvider::get()->agent_otp_exp_seconds ?? GlobalConst::DEFAULT_TOKEN_EXP_SEC;
        $auth_column = AgentAuthorization::where("agent_id",$user->id)->first();

        if(!$auth_column){
            return Response::error(['Verification code already used.'],[],404);
        }
        if($auth_column->code !=  $code){
            return Response::error(['Verification is invalid.'],[],404);
        }
        if($auth_column->created_at->addSeconds($otp_exp_sec) < now()) {
            return Response::error(['Time expired. Please try again.'],[],404);
        }
        try{
            $auth_column->agent->update([
                'email_verified'    => true,
            ]);
            $auth_column->delete();
        }catch(Exception $e) {
            return Response::error(['Something went wrong! Please try again.'],[],404);
        }
        return Response::success(['Account successfully verified.'],[],200);
    }
    /**
     * Method for verifying 2FA code.
     * @param Illuminate\Http\Request $request
     */
    public function verify2FACode(Request $request) {
        $validator = Validator::make($request->all(), [
            'otp' => 'required',
        ]);

        if($validator->fails()){
            return Response::error($validator->errors()->all(),[]);
        }

        $code = $request->otp;
        $user = authGuardApi()['user'];

        if(!$user->two_factor_secret) {
            return Response::error(['Your secret key is not stored properly. Please contact with system administrator.'],[],404);
        }

        if(google_2fa_verify_api($user->two_factor_secret,$code)) {
            $user->update([
                'two_factor_verified'   => true,
            ]);
            return Response::success(['Two factor verified successfully.'],[],200);
        }
        return Response::error(['Failed to login. Please try again.'],[],404);
    }
}
