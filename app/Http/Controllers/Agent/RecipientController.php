<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RecipientController extends Controller
{
    /**
     * Method for view recipient page
     * @return view
     */
    public function index(){
        $page_title         = "My Recipient";

        return view('agent.sections.recipient.index',compact(
            'page_title'
        ));
    }
}
