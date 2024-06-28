<?php

namespace App\Http\Controllers\Agent;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Agent\AgentWallet;
use Illuminate\Support\Facades\Auth;

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
}
