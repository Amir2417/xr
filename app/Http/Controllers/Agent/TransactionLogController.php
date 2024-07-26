<?php

namespace App\Http\Controllers\Agent;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Helpers\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

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
    /**
     * Method for search transaction data using AJAX
     * @param Illuminate\Http\Request $request
     */
    public function search(Request $request){
        $validator = Validator::make($request->all(),[
            'text'  => 'required|string',
        ]);
        if($validator->fails()) {
            $error = ['error' => $validator->errors()];
            return Response::error($error,null,400);
        }

        $validated = $validator->validate();
        
        $transactions    = Transaction::agentAuth()->search($validated['text'])->get();
                                  
        return view('agent.components.transaction-logs.index',compact('transactions'));

    }
}
