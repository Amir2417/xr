<?php

namespace App\Http\Controllers\Agent;

use Illuminate\Http\Request;
use App\Models\Admin\Currency;
use App\Http\Controllers\Controller;
use App\Models\Agent\AgentRecipient;

class RecipientController extends Controller
{
    /**
     * Method for view recipient page
     * @return view
     */
    public function index(){
        $page_title         = "My Recipient";
        $recipients         = AgentRecipient::auth()->orderBy('id','desc')->get();

        return view('agent.sections.recipient.index',compact(
            'page_title',
            'recipients'
        ));
    }
    /**
     * Method for view add recipient page
     * @return view
     */
    public function add(){
        $page_title         = "Add New Recipient";
        $receiver_country   = Currency::where('receiver',true)->where('status',true)->get();
        
        return view('agent.sections.recipient.add',compact(
            'page_title',
            'receiver_country'
        ));
    }
}
