<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MoneyOutController extends Controller
{
    /**
     * Method for view money out index page
     * @return view
     */
    public function index(){
        $page_title        = "Money Out";

        return view('agent.sections.money-out.index',compact(
            'page_title'
        ));
    }
}
