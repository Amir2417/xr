<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Admin\Currency;
use App\Models\Admin\CashPickup;
use App\Http\Controllers\Controller;

class CashPickupMethodController extends Controller
{
    /**
     * Method for view cash pickup index page
     * @return view
     */
    public function index(){
        $page_title         = "Cash Pickups";
        $cash_pickups       = CashPickup::orderBy('id','desc')->get();
        $receiver_currency  = Currency::where('status',true)->where('receiver',true)->get();

        return view('admin.sections.cash-pickup.index',compact(
            'page_title',
            'cash_pickups',
            'receiver_currency'
        ));
    }
}
