<?php

namespace App\Http\Controllers\Agent;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Method for view agent dashboard page.
     */
    public function index(){
        $page_title     = "Dashboard";

        return view('agent.dashboard',compact(
            'page_title'
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
