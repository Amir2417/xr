<?php

namespace App\Http\Controllers\Agent;

use App\Constants\GlobalConst;
use App\Constants\PaymentGatewayConst;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Helpers\Response;
use App\Models\Agent\AgentWallet;
use App\Http\Controllers\Controller;
use App\Models\Agent\AgentProfit;
use App\Models\Agent\AgentRecipient;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    /**
     * Method for view agent dashboard page.
     */
    public function index(){
        $page_title             = "Dashboard";
        $agent_wallet           = AgentWallet::auth()->first();
        $profit_balance         = AgentProfit::auth()->sum('total_commissions');
        $total_send_remittance  = Transaction::agentAuth()
                                    ->where('type',PaymentGatewayConst::TYPESENDREMITTANCE)
                                    ->count();
        $total_confirm_send_remittance  = Transaction::agentAuth()
                                    ->where('type',PaymentGatewayConst::TYPESENDREMITTANCE)
                                    ->where('status',GlobalConst::REMITTANCE_STATUS_CONFIRM_PAYMENT)
                                    ->count();
        $total_canceled_transactions  = Transaction::agentAuth()->where('status',GlobalConst::REMITTANCE_STATUS_CANCEL)->count();

        $transactions                   = Transaction::agentAuth()->orderBy('id','desc')->latest()->take(3)->get();

        $current_month = now()->month;
        $categories = [];
        for ($i = 1; $i <= $current_month; $i++) {
            $categories[] = date("F", mktime(0, 0, 0, $i, 10));
        }

        $send_remittance = [];
        $money_in        = [];
        $money_out       = [];

        foreach ($categories as $index => $month) {
            $month_number = $index + 1;
            $send_remittance[] = Transaction::where('type', PaymentGatewayConst::TYPESENDREMITTANCE)
                                        ->whereMonth('created_at', $month_number)
                                        ->whereYear('created_at', now()->year)
                                        ->sum('request_amount');

            $money_in[] = Transaction::where('type', PaymentGatewayConst::MONEYIN)
                                    ->whereMonth('created_at', $month_number)
                                    ->whereYear('created_at', now()->year)
                                    ->sum('request_amount');

            $money_out[] = Transaction::where('type', PaymentGatewayConst::MONEYOUT)
                                    ->whereMonth('created_at', $month_number)
                                    ->whereYear('created_at', now()->year)
                                    ->sum('request_amount');
        }

        $data = [
            'categories' => $categories,
            'send_remittance' => $send_remittance,
            'money_in' => $money_in,
            'money_out' => $money_out,
        ];    

        return view('agent.dashboard',compact(
            'page_title',
            'agent_wallet',
            'profit_balance',
            'total_send_remittance',
            'total_confirm_send_remittance',
            'total_canceled_transactions',
            'transactions',
            'data'
        ));
    }
    /**
     * Method for logout agent
     */
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('agent.login')->with(['success' => [__('Logout Successfully!')]]);
    }
    /**
     * Method for get user data
     * @param Illuminate\Http\Request $request
     */
    public function getUserData(Request $request){
        $validator          = Validator::make($request->all(),[
            'search'        => 'required'
        ]);
        if($validator->fails()) return Response::error($validator->errors()->all());
        $validated          = $validator->validate();
        $user_data          = User::where('email',$validated['search'])->first();
        
        return Response::success(['User data get successfully.'],['user_data' => $user_data],200);
    }
    /**
     * Method for get recipient data
     * @param Illuminate\Http\Request $request
     */
    public function getRecipientData(Request $request){
        $validator          = Validator::make($request->all(),[
            'method'        => 'required'
        ]);
        if($validator->fails()) return Response::error($validator->errors()->all());
        $validated          = $validator->validate();
        $recipient_data     = AgentRecipient::where('method',$validated['method'])->get();
        
        return Response::success(['Recipient data get successfully.'],['recipient_data' => $recipient_data],200);
    }
}
