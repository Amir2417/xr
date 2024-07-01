<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StatementController extends Controller
{
    /**
     * Method for show statement index page
     * @return view
     */
    public function index(){
        $page_title     = "Statements";

        return view('agent.sections.statements.index',compact(
            'page_title'
        ));
    }
}
