<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
}
