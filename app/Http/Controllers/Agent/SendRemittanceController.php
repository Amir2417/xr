<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SendRemittanceController extends Controller
{
    /**
     * Method for show send remittance page
     * @return view
     */
    public function index(){
        $page_title         = "Send Remittance";

        return view('agent.sections.send-remittance.index',compact(
            'page_title'
        ));
    }
}
