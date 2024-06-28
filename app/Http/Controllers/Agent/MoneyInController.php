<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MoneyInController extends Controller
{
    /**
     * Method for show money in index page
     * @return view
     */
    public function index(){
        $page_title     = "MoneyIn";

        return view('agent.sections.money-in.index',compact(
            'page_title'
        ));
    }
}
