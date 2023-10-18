<?php

namespace App\Http\Controllers\Admin;

use PDF;
use Carbon\Carbon;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\Currency;
use App\Providers\Admin\BasicSettingsProvider;
use Illuminate\Support\Facades\Validator;

class StatementController extends Controller
{
    /**
     * Method for show statement page
     * @return view
     */
    public function index(){
        $page_title    = "Statements";

        return view('admin.sections.statements.index',compact(
            'page_title',
        ));
    }
    /**
     * Method for filter statement
     * @param Iluminate\Http\Request $request
     */
    public function statementFilter(Request $request){
        $page_title    = "Statements";
        $sender_currency      = Currency::where('status',true)->where('sender',true)->first();
        $receiver_currency    = Currency::where('status',true)->where('receiver',true)->first();

        $validator   = Validator::make($request->all(),[
            'time_period'       => 'nullable',
            'start_date'        => 'nullable',
            'end_date'          => 'nullable',
            'status'            => 'nullable',
            'submit_type'       => 'sometimes|required|in:EXPORT',
        ]);
        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }
        $validated = $validator->validate();
        $selectedRange = $request->input('time_period');
        $startDate = null;
        $endDate = null;

        if ($selectedRange == global_const()::SPECIFIC_DATES) {
            $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
        } else {
            switch ($selectedRange) {
                case global_const()::LAST_ONE_WEEKS:
                    $startDate = Carbon::now()->subWeek()->startOfWeek();
                    break;
                case global_const()::LAST_TWO_WEEKS:
                    $startDate = Carbon::now()->subWeeks(2)->startOfWeek();
                    break;
                case global_const()::LAST_ONE_MONTHS:
                    $startDate = Carbon::now()->subMonth()->startOfMonth();
                    break;
                case global_const()::LAST_TWO_MONTHS:
                    $startDate = Carbon::now()->subMonths(2)->startOfMonth();
                    break;
                case global_const()::LAST_THREE_MONTHS:
                    $startDate = Carbon::now()->subMonths(3)->startOfMonth();
                    break;
                case global_const()::LAST_SIX_MONTHS:
                    $startDate = Carbon::now()->subMonths(6)->startOfMonth();
                    break;
                case global_const()::LAST_ONE_YEARS:
                    $startDate = Carbon::now()->subYear()->startOfYear();
                    break;
            }
            $endDate = Carbon::now()->endOfWeek();
        }

        $status = $request->input('status');

        $query = Transaction::query();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        if ($status !== global_const()::REMITTANCE_STATUS_ALL) {
            $query->where('status', $status);
        }

        $transactions = $query->get();

        if(isset($validated['submit_type']) && $validated['submit_type'] == 'EXPORT') {
            return $this->download($transactions);
        }
        

        return view('admin.sections.statements.index',compact(
            'page_title',
            'transactions',
            'sender_currency',
            'receiver_currency',
            'selectedRange',
            'status'
        ));
        
    }

    /**
     * Method for download statement in pdf format
     * @param string
     * 
     */
    public function download($transactions){
        $sender_currency         = Currency::where('status',true)->where('sender',true)->first();
        $receiver_currency       = Currency::where('status',true)->where('receiver',true)->first();
        $total_transactions      = $transactions->count();
        $total_send_amount       = $transactions->sum('request_amount');
        $total_receive_amount    = $transactions->sum('will_get_amount');

        $data   = [
            'total_transactions'   => $total_transactions,
            'total_send_amount'    => $total_send_amount,
            'total_receive_amount' => $total_receive_amount,
            'transaction'          => $transactions,
            'sender_currency'      => $sender_currency,
            'receiver_currency'    => $receiver_currency,
        ];

        $pdf = PDF::loadView('pdf-templates.statement', $data);
        $basic_settings = BasicSettingsProvider::get();
        return $pdf->download($basic_settings->site_name.'-'.'statement.pdf');     
    }

    
    
    
    


}
