<?php

namespace App\Http\Controllers\Agent;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Helpers\Response;
use App\Models\Agent\AgentWallet;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    /**
     * Method for view agent dashboard page.
     */
    public function index(){
        $page_title     = "Dashboard";
        $agent_wallet   = AgentWallet::auth()->first();

        return view('agent.dashboard',compact(
            'page_title',
            'agent_wallet'
        ));
    }
    /**
     * Method for logout agent
     */
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('agent.login')->with(['success' => [__('Logout Successfully!')]]);
    }
    /**
     * Method for get user data
     * @param Illuminate\Http\Request $request
     */
    public function getUserData(Request $request){
        $validator          = Validator::make($request->all(),[
            'search'        => 'required'
        ]);
        if($validator->fails()) return Response::error($validator->errors()->all());
        $validated          = $validator->validate();
        $user_data          = User::where('email',$validated['search'])->first();
        
        return Response::success(['User data get successfully.'],['user_data' => $user_data],200);
    }
}
