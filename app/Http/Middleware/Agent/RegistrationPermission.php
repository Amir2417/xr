<?php

namespace App\Http\Middleware\Agent;

use Closure;
use Illuminate\Http\Request;
use App\Http\Helpers\Response;
use App\Http\Helpers\Api\Helpers;
use App\Providers\Admin\BasicSettingsProvider;

class RegistrationPermission
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
        $basic_settings = BasicSettingsProvider::get();
        if($request->expectsJson()) {
            if($basic_settings->agent_registration != true){
                return Response::error(['Registration Option Currently Off.'],[],404); 
            }
            return $next($request);
        }
        if($basic_settings->agent_registration != true) return back()->withInput()->with(['warning' => [__("Registration Option Currently Off")]]);
        return $next($request);

    }
}
