<?php

namespace App\Http\Controllers\Agent;

use App\Constants\GlobalConst;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Constants\PaymentGatewayConst;
use App\Models\Admin\PaymentGatewayCurrency;
use App\Models\Admin\TransactionSetting;

class MoneyOutController extends Controller
{
    /**
     * Method for view money out index page
     * @return view
     */
    public function index(){
        $page_title             = "Money Out";
        $payment_gateway        = PaymentGatewayCurrency::whereHas('gateway', function ($gateway) {
            $gateway->where('slug', PaymentGatewayConst::money_out_slug());
            $gateway->where('status', 1);
        })->get();
        $transaction_settings   = TransactionSetting::where('slug',GlobalConst::MONEY_OUT)->first();

        return view('agent.sections.money-out.index',compact(
            'page_title',
            'payment_gateway',
            'transaction_settings'
        ));
    }
}
