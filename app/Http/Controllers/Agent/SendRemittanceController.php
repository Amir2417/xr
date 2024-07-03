<?php

namespace App\Http\Controllers\Agent;

use Illuminate\Http\Request;
use App\Constants\GlobalConst;
use App\Models\Admin\Currency;
use App\Models\Agent\MySender;
use App\Http\Controllers\Controller;
use App\Models\Agent\AgentRecipient;
use App\Models\Admin\TransactionSetting;

class SendRemittanceController extends Controller
{
    /**
     * Method for show send remittance page
     * @return view
     */
    public function index(){
        $page_title                 = "Send Remittance";
        $transaction_settings       = TransactionSetting::where('status',true)
                                    ->whereIn('slug',[GlobalConst::BANK_TRANSFER,GlobalConst::MOBILE_MONEY,
                                        GlobalConst::CASH_PICKUP
                                    ])->get();
        $receiver_currency          = Currency::where('status',true)->where('receiver',true)->get();
        $receiver_currency_first    = Currency::where('status',true)->where('receiver',true)->first();
        $senders                    = MySender::auth()->orderBy('id','desc')->get();
        $recipients                 = AgentRecipient::auth()->orderBy('id','desc')->get();

        return view('agent.sections.send-remittance.index',compact(
            'page_title',
            'transaction_settings',
            'senders',
            'recipients',
            'receiver_currency',
            'receiver_currency_first'
        ));
    }
    /**
     * Method for send remittance submit information
     * @param Illuminate\Http\Request $request 
     */
    public function submit(Request $request){
        return back()->with(['error' => ['Under mentainance mood']]);
    }
}
