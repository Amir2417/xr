<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionLogController extends Controller
{
    /**
     * Method for view transaction logs page
     * @return view
     */
    public function index(){
        $page_title     = 'Transaction Logs';

        return view('agent.sections.transaction-logs.index',compact(
            'page_title'
        ));
    }
}
