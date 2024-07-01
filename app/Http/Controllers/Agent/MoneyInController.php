<?php

namespace App\Http\Controllers\Agent;

use Illuminate\Http\Request;
use App\Constants\GlobalConst;
use App\Http\Controllers\Controller;
use App\Constants\PaymentGatewayConst;
use App\Models\Admin\TransactionSetting;
use App\Models\Admin\PaymentGatewayCurrency;

class MoneyInController extends Controller
{
    /**
     * Method for show money in index page
     * @return view
     */
    public function index(){
        $page_title                 = "MoneyIn";
        $payment_gateway            = PaymentGatewayCurrency::whereHas('gateway', function ($gateway) {
            $gateway->where('slug', PaymentGatewayConst::remittance_money_slug());
            $gateway->where('status', 1);
        })->get();
        $transaction_settings       = TransactionSetting::where('status',true)
                                        ->where('slug',GlobalConst::MONEY_IN)
                                        ->first();

        return view('agent.sections.money-in.index',compact(
            'page_title',
            'payment_gateway',
            'transaction_settings'
        ));
    }
}
