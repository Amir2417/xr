<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CouponTransaction;
use Illuminate\Http\Request;

class CouponTransactionController extends Controller
{
    /**
     * Method for view for the coupon log index page
     * @return view
     */
    public function index(){
        $page_title         = "Coupon Logs";
        $transactions       = CouponTransaction::with(['user_coupon','coupon','transaction'])
                                ->orderBy('id','desc')->get();
    
        return view('admin.sections.coupon-logs.index',compact(
            'page_title',
            'transactions'
        ));
    }
    /**
     * Method for coupon log details page
     * @param $trx_id
     * @return view
     */
    public function details($trx_id){
        $page_title     = "Coupon Log Details";
        $data   = CouponTransaction::with(['coupon','user_coupon','transaction'])->whereHas('transaction',function($q) use($trx_id){
            $q->where('trx_id',$trx_id);
        })->first();
        if(!$data) return back()->with(['error' => ['Sorry! Data not found.']]);
        
        return view('admin.sections.coupon-logs.details',compact(
            'page_title',
            'data'
        ));

    }
    /**
     * Method for share link page
     * @param string $trx_id
     * @param \Illuminate\Http\Request $request
     */
    public function shareLink(Request $request,$trx_id){
        $page_title         = "| Information";
        $data        = CouponTransaction::with(['coupon','user_coupon','transaction'])->whereHas('transaction',function($q) use ($trx_id){
            $q->where('trx_id',$trx_id);
        })->first();
        
        return view('share-link.coupon-log',compact(
            'page_title',
            'data',
        ));   
    }

    public function downloadPdf($trx_id){
        $transaction             = CouponTransaction::with(['coupon','user_coupon','transaction'])->whereHas()->first(); 

        $data   = [
            'transaction'        => $transaction,
            'sender_currency'    => $sender_currency,
            'receiver_currency'  => $receiver_currency,
        ];
        
        $pdf = PDF::loadView('pdf-templates.index', $data);
        
        $basic_settings = BasicSettingsProvider::get();
        
        return $pdf->download($basic_settings->site_name.'-'.$transaction->trx_id.'.pdf');
    }
}
