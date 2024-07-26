<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Agent\AgentProfit;
use Illuminate\Http\Request;

class ProfitLogController extends Controller
{
    /**
     * Method for view profit logs page
     * @return view
     */
    public function index(){
        $page_title     = 'Profit Logs';
        $transactions   = AgentProfit::auth()->with(['transaction'])->orderBy('id','desc')->paginate(10);
        
        return view('agent.sections.profit-logs.index',compact(
            'page_title',
            'transactions'
        ));
    }
}

