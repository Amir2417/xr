<?php

namespace App\Http\Controllers\Agent;

use App\Constants\GlobalConst;
use App\Http\Controllers\Controller;
use App\Models\Admin\SetupKyc;
use App\Models\AgentAuthorization;
use App\Notifications\User\Auth\SendAuthorizationCode;
use App\Providers\Admin\BasicSettingsProvider;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\ControlDynamicInputFields;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AuthorizationController extends Controller
{
    use ControlDynamicInputFields;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showMailFrom($token)
    {
        $page_title = __("Mail Authorization");
        return view('agent.auth.authorize.verify-mail',compact("page_title","token"));
    }
    /**
     * Method for show email verify page
     */
    public function showEmailFromRegister($token){
        $data = AgentAuthorization::where('token',$token)->first();
        $page_title = __('email Verification');
        return view('agent.auth.authorize.verify-email',compact("page_title","token","data"));
    }
    /**
     * Verify authorizaation code.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function mailVerify(Request $request,$token)
    {

        $request->merge(['token' => $token]);
        $request->validate([
            'token'     => "required|string|exists:agent_authorizations,token",
            'code'      => "required|array",
            'code.*'    => "required|numeric",
        ]);
        $code = $request->code;
        $code = implode("",$code);
        $otp_exp_sec = BasicSettingsProvider::get()->agent_otp_exp_seconds ?? GlobalConst::DEFAULT_TOKEN_EXP_SEC;
        $auth_column = AgentAuthorization::where("token",$request->token)->where("code",$code)->first();
        if(!$auth_column){
            return back()->with(['error' => [__('The verification code does not match')]]);
        }
        if($auth_column->created_at->addSeconds($otp_exp_sec) < now()) {
            $this->authLogout($request);
            return redirect()->route('agent.login')->with(['error' => [__('Session expired. Please try again')]]);
        }
        try{
            $auth_column->agent->update([
                'email_verified'    => true,
            ]);
            $auth_column->delete();
        }catch(Exception $e) {
            $this->authLogout($request);
            return redirect()->route('agent.login')->with(['error' => [__("Something went wrong! Please try again.")]]);
        }
        return redirect()->route("agent.dashboard")->with(['success' => [__('Account successfully verified')]]);
    }
    public function resendCode()
    {
        $user = auth()->user();
        $resend = AgentAuthorization::where("agent_id",$user->id)->first();
        if( $resend){
            if(Carbon::now() <= $resend->created_at->addMinutes(GlobalConst::USER_VERIFY_RESEND_TIME_MINUTE)) {
                throw ValidationException::withMessages([
                    'code'      => __("You can resend the verification code after").' '.Carbon::now()->diffInSeconds($resend->created_at->addMinutes(GlobalConst::USER_VERIFY_RESEND_TIME_MINUTE)). ' '.__('seconds'),
                ]);
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
            AgentAuthorization::where("agent_id",$user->id)->delete();
            DB::table("agent_authorizations")->insert($data);
            $user->notify(new SendAuthorizationCode((object) $data));
            DB::commit();
        }catch(Exception $e) {
            DB::rollBack();
            return back()->with(['error' => [__("Something went wrong! Please try again.")]]);
        }

        return redirect()->route('agent.authorize.mail',$data['token'])->with(['success' => [__('Verification code resend success')]]);

    }

    public function authLogout(Request $request) {
        auth()->guard("web")->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
