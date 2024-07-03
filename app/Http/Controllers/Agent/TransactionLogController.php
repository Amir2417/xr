<?php

namespace App\Http\Controllers\Agent;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransactionLogController extends Controller
{
    /**
     * Method for view transaction logs page
     * @return view
     */
    public function index(){
        $page_title     = 'Transaction Logs';
        $transactions   = Transaction::agentAuth()->orderBy('id','desc')->paginate(15);

        return view('agent.sections.transaction-logs.index',compact(
            'page_title',
            'transactions'
        ));
    }
}
