<?php

namespace App\Http\Controllers\Agent;

use App\Constants\GlobalConst;
use App\Http\Controllers\Controller;
use App\Models\Admin\TransactionSetting;
use App\Models\Agent\AgentRecipient;
use App\Models\Agent\MySender;
use Illuminate\Http\Request;

class SendRemittanceController extends Controller
{
    /**
     * Method for show send remittance page
     * @return view
     */
    public function index(){
        $page_title             = "Send Remittance";
        $transaction_settings   = TransactionSetting::where('status',true)
                                    ->whereIn('slug',[GlobalConst::BANK_TRANSFER,GlobalConst::MOBILE_MONEY,
                                        GlobalConst::CASH_PICKUP
                                    ])->get();
        $senders                = MySender::auth()->orderBy('id','desc')->get();
        $recipients             = AgentRecipient::auth()->orderBy('id','desc')->get();

        return view('agent.sections.send-remittance.index',compact(
            'page_title',
            'senders',
            'recipients',
        ));
    }
}
