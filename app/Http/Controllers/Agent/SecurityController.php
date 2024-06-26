<?php

namespace App\Http\Controllers\Agent;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SecurityController extends Controller
{
    /**
     * Method for show google 2FA page
     * @return view
     */
    public function google2FA(){
        $page_title     = "Google 2FA";
        $qr_code        = generate_google_2fa_auth_qr();

        return view('agent.sections.security.google-2fa',compact(
            'page_title',
            'qr_code'
        ));
    }
    /**
     * Method for update google 2fa status update
     */
    public function google2FAStatusUpdate(Request $request){
        $validated = Validator::make($request->all(),[
            'target'        => "required|numeric",
        ])->validate();

        $agent = auth()->user();
        try{
            $agent->update([
                'two_factor_status'         => $agent->two_factor_status ? 0 : 1,
                'two_factor_verified'       => true,
            ]);
        }catch(Exception $e) {
            return back()->with(['error' => ['Something went wrong! Please try again']]);
        }
        return back()->with(['success' => ['Security Setting Updated Successfully!']]);
    }
}
