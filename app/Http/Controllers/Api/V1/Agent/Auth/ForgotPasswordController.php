<?php

namespace App\Http\Controllers\Api\V1\Agent\Auth;

use Exception;
use Carbon\Carbon;
use App\Models\Agent;
use Illuminate\Http\Request;
use App\Http\Helpers\Response;
use App\Models\AgentPasswordReset;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use App\Providers\Admin\BasicSettingsProvider;
use App\Notifications\Agent\Auth\PasswordResetEmail;

class ForgotPasswordController extends Controller
{
    /**
     * Method for send otp code
     * @param Illuminate\Http\Request $request
     */
    public function sendCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'   => "required|email|max:100",
        ]);
        if($validator->fails()){
            return Response::error($validator->errors()->all(),[]);
        }
        $column = "email";
        if(check_email($request->email)) $column = "email";
        $user = Agent::where($column,$request->email)->first();
        if(!$user) {
            return Response::error(["Agent doesn't exists."],[],400);
        }
        $token = generate_unique_string("agent_password_resets","token",80);
        $code = generate_random_code();

        try{
            AgentPasswordReset::where("agent_id",$user->id)->delete();
            $password_reset = AgentPasswordReset::create([
                'agent_id'      => $user->id,
                'email'         => $request->email,
                'token'         => $token,
                'code'          => $code,
            ]);
            $user->notify(new PasswordResetEmail($user,$password_reset));
        }catch(Exception $e) {
            return Response::error(["Something went wrong! Please try again."],[],400);
        }

        return Response::success(['Verification code sended to your email address'],[],200);
    }
    /**
     * Method for verify otp code
     * @param Illuminate\Http\Request $request
     */
    public function verifyCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'code' => 'required|numeric',
        ]);
        if($validator->fails()){
            return Response::error($validator->errors()->all(),[]);
        }
        $code = $request->code;
        $basic_settings = BasicSettingsProvider::get();
        $otp_exp_seconds = $basic_settings->agent_otp_exp_seconds ?? 0;
        $password_reset = AgentPasswordReset::where("code", $code)->where('email',$request->email)->first();
        if(!$password_reset) {
            return Response::error(["Verification Otp is Invalid."],[],400);
        }
        if(Carbon::now() >= $password_reset->created_at->addSeconds($otp_exp_seconds)) {
            foreach(AgentPasswordReset::get() as $item) {
                if(Carbon::now() >= $item->created_at->addSeconds($otp_exp_seconds)) {
                    $item->delete();
                }
            }
            return Response::error(["Time expired. Please try again."],[],400);
        }
        return Response::success(['Your Verification is successful, Now you can recover your password'],[],200);
    }
    /**
     * Method for reset password
     * @param Illuminate\Http\Request $request
     */
    public function resetPassword(Request $request) {
        $basic_settings = BasicSettingsProvider::get();
        $passowrd_rule = "required|string|min:6|confirmed";
        if($basic_settings->agent_secure_password) {
            $passowrd_rule = ["required",Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised(),"confirmed"];
        }
        $validator = Validator::make($request->all(),[
            'code'  => 'required|numeric',
            'email' => 'required|email',
            'password'      => $passowrd_rule,
        ]);
        if($validator->fails()){
            return Response::error($validator->errors()->all(),[]);
        }
        $code = $request->code;
        $password_reset = AgentPasswordReset::where("code",$code)->where('email',$request->email)->first();
        if(!$password_reset) {
            return Response::error(["Invalid request."],[],400);
        }
        try{
            $password_reset->agent->update([
                'password'      => Hash::make($request->password),
            ]);
            $password_reset->delete();
        }catch(Exception $e) {
            return Response::error(["Something went wrong! Please try again."],[],400);
        }
        
        return Response::success(['Password reset success. Please login with new password.'],[],200);
    }
}
