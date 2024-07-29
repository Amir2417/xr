<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Illuminate\Http\Request;
use App\Http\Helpers\Response;
use App\Http\Helpers\Api\Helpers;


class AppModeGuardApi
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
        if(in_array($request->method(),['POST','PUT','DELETE'])) {
            $ignore_routes = ['logout'];

            $request_path = $request->path();
            $request_path = explode('?',$request_path);
            $request_path = array_shift($request_path);
            $request_path = explode("/",$request_path);
            $request_path = array_pop($request_path);

            if(!in_array($request_path,$ignore_routes)) {
                if(env("APP_MODE") != 'live') {
                    $message = ['error'=>[__()]];
                    return Response::unauthorized(["Can't change anything for demo application."],[],401);
                }
            }

        }
        return $next($request);
    }
}
