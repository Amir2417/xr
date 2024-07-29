<?php

namespace App\Http\Middleware\Agent;

use Closure;
use Illuminate\Http\Request;
use App\Http\Helpers\Response;
use App\Http\Helpers\Api\Helpers;

class GoogleTwoFactorApi
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
        if($user->two_factor_status && $user->two_factor_verified == false) {
            return Response::error(['2fa verification is required.'],[],400);
        }
        return $next($request);
    }
}
