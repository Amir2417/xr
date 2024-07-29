<?php

namespace App\Http\Middleware\Agent;

use Closure;
use Illuminate\Http\Request;
use App\Http\Helpers\Response;
use App\Http\Helpers\Api\Helpers;
use App\Http\Helpers\ResponseHelper;
use App\Models\Admin\BasicSettings;
use Illuminate\Support\Facades\Auth;

class CheckStatusApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        if((Auth::user()->email_verified == 1 ) &&
         (Auth::user()->status == 1)
         ){
            return $next($request);
        }else{
            if(Auth::user()->status == 0){
                return Response::error(['Failed to login. Please try again.'],[],400);
            }
            $basic_settings = BasicSettings::first();
            if($basic_settings->agent_email_verification == true){
                if($user->email_verified == 0){
                    return agentMailVerificationTemplateApi($user);
                }
            }
            
        }
    }
}
