<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfitLogController extends Controller
{
    /**
     * Method for view profit logs page
     * @return view
     */
    public function index(){
        $page_title     = 'Profit Logs';
        
        return view('agent.sections.profit-logs.index',compact(
            'page_title'
        ));
    }
}

