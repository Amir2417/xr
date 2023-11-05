<?php

namespace App\Http\Controllers\User;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\Admin\Currency;
use App\Models\UserNotification;
use App\Http\Controllers\Controller;

class TransactionController extends Controller
{
    /**
     * Method for show transaction page
     * @return view
     */
    public function transaction(){
        $page_title           = "| Transactions";
        $transactions         = Transaction::with(['currency'])->where('user_id',auth()->user()->id)->orderByDESC('id')->get();
        $sender_currency      = Currency::where('status',true)->where('sender',true)->first();
        $receiver_currency    = Currency::where('status',true)->where('receiver',true)->first();
        
        $client_ip            = request()->ip() ?? false;
        $user_country         = geoip()->getLocation($client_ip)['country'] ?? "";
        $user                 = auth()->user();
        $notifications        = UserNotification::where('user_id',$user->id)->latest()->take(10)->get();

        return view('user.sections.transaction.index',compact(
            'page_title',
            'transactions',
            'sender_currency',
            'receiver_currency',
            'user_country',
            'user',
            'notifications'
        ));
    }
    /**
     * Method for search transaction data using AJAX
     * @param Illuminate\Http\Request $request
     */
    public function search(Request $request)
    {
        

        $sender_currency      = Currency::where('status',true)->where('sender',true)->first();
        $receiver_currency    = Currency::where('status',true)->where('receiver',true)->first();
        $client_ip            = request()->ip() ?? false;
        $user_country         = geoip()->getLocation($client_ip)['country'] ?? "";
        $user                 = auth()->user();
        $notifications        = UserNotification::where('user_id',$user->id)->latest()->take(10)->get();

        
        $searchTerm = $request->input('search_term');

        if ($searchTerm) {
            $transactions = Transaction::where('user_id', auth()->user()->id)
                ->where('trx_id', $searchTerm)
                ->get();
        } else {
            $transactions = Transaction::where('user_id', auth()->user()->id)->get();
        }

        return response()->json(['transactions' => $transactions,'sender_currency'=>$sender_currency,'receiver_currency'=>$receiver_currency]);
        
        
    }
}
