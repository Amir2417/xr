<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MySenderController extends Controller
{
    /**
     * Method for view my sender page
     * @return view`
     */
    public function index(){
        $page_title     = 'My Sender';

        return view('agent.sections.my-sender.index',compact(
            'page_title'
        ));
    }
}
