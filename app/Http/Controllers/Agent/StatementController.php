<?php

namespace App\Http\Controllers\Agent;

use Carbon\Carbon;
use PDF;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\Admin\Currency;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Providers\Admin\BasicSettingsProvider;

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
    /**
     * Method for filter statement
     * @param Iluminate\Http\Request $request
     */
    public function filter(Request $request){
        $page_title    = "Statements";
        

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

        if ($request->start_date || $request->end_date) {
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

        if ($startDate && $endDate && $status == global_const()::REMITTANCE_STATUS_ALL) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }elseif($startDate && $endDate && $status){
            $query->whereBetween('created_at', [$startDate, $endDate])->where('status',$status);
        }elseif($startDate && $endDate){
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }elseif ($status == global_const()::REMITTANCE_STATUS_ALL) {
            $query->get();
        }else{
            $query->where('status',$status);
        }
        
        $transactions = $query->get();

        if(isset($validated['submit_type']) && $validated['submit_type'] == 'EXPORT') {
            return $this->download($transactions);
        }
        

        return view('agent.sections.statements.index',compact(
            'page_title',
            'transactions',
            'selectedRange',
            'status'
        ));
        
    }
    /**
     * Method for download statement in pdf format
     * @param string
     */
    public function download($transactions){
        $total_transactions      = $transactions->count();
        $request                    = 0;
        $payable_amount       = 0;
        foreach($transactions as $item){
            
                $request += $item->request_amount;
                $payable_amount += $item->payable;
            
            
            
        }

        $total_send_amount       = $request;
        $total_payable_amount    = $payable_amount;

        $data   = [
            'total_transactions'   => $total_transactions,
            'total_send_amount'    => $total_send_amount,
            'total_payable_amount' => $total_payable_amount,
            'transaction'          => $transactions,
        ];

        $pdf = PDF::loadView('agent.sections.statements.pdf-file', $data);
        $basic_settings = BasicSettingsProvider::get();
        return $pdf->download($basic_settings->agent_site_name.'-'.'statement.pdf');     
    }
}
