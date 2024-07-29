<?php

namespace App\Http\Controllers\Api\V1\Agent;

use Exception;
use Illuminate\Http\Request;
use App\Http\Helpers\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SecurityController extends Controller
{
    /**
     * Method for google 2FA data.
     */
    public function google2FA(){
        $user = Auth::guard(get_auth_guard())->user();
        $qr_code = generate_google_2fa_auth_qr();
        $qr_secrete = $user->two_factor_secret;
        $qr_status = $user->two_factor_status;

        $data = [
            'qr_code'       => $qr_code,
            'qr_secrete'    => $qr_secrete,
            'qr_status'     => $qr_status,
            'alert'         => __("Don't forget to add this application in your google authentication app. Otherwise you can't login in your account.",)
        ];

        return Response::success(['Data Fetch Successful'], $data,200);
    }
    /**
     * Method for google 2FA status update.
     * @param Illuminate\Http\Request $request
     */
    public function google2FAStatusUpdate(Request $request){
        $validator = Validator::make($request->all(),[
            'status'        => "required|numeric",
        ]);
        if($validator->fails()){
            return Response::validation(['error' => $validator->errors()->all()]);
        }

        $validated = $validator->validated();
        $user = Auth::guard(get_auth_guard())->user();
        try{
            $user->update([
                'two_factor_status'         => $validated['status'],
                'two_factor_verified'       => true,
            ]);
        }catch(Exception $e) {
           return Response::error(['Something went wrong! Please try again']);
        }

        return Response::success(['Google 2FA Updated Successfully!'],[],200);
    }
}
