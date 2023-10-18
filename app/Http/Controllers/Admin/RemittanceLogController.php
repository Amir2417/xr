<?php

namespace App\Http\Controllers\Admin;

use PDF;
use Exception;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Helpers\Response;
use App\Models\Admin\Currency;
use App\Models\UserNotification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use App\Notifications\remittanceNotification;
use App\Providers\Admin\BasicSettingsProvider;

class RemittanceLogController extends Controller
{
    /**
     * Method for show send remittance page
     * @param string
     * @return view
     */
    public function index(){
        $page_title           = "All Logs";
        $transactions         = Transaction::get();
        $sender_currency      = Currency::where('status',true)->where('sender',true)->first();
        $receiver_currency    = Currency::where('status',true)->where('receiver',true)->first();
        

        return view('admin.sections.remittance-log.all-logs',compact(
            'page_title',
            'transactions',
            'sender_currency',
            'receiver_currency',
        ));
    }
    /**
     * Method for show send remittance details page
     * @param $trx_id,
     * @param Illuminate\Http\Request $request
     */
    public function details(Request $request, $trx_id){
        $page_title        = "Log Details";
        $transaction       = Transaction::where('trx_id',$trx_id)->first();
        $sender_currency   = Currency::where('status',true)->where('sender',true)->first();
        $receiver_currency = Currency::where('status',true)->where('receiver',true)->first();

        
        return view('admin.sections.remittance-log.details',compact(
            'page_title',
            'transaction',
            'sender_currency',
            'receiver_currency',
        ));
    }
    /**
     * Method for update status 
     * @param $trx_id
     * @param Illuminate\Http\Request $request
     */
    public function statusUpdate(Request $request,$trx_id){
        
        $validator = Validator::make($request->all(),[
            'status'            => 'required|integer',
        ]);

        if($validator->fails()) {
            $errors = ['error' => $validator->errors() ];
            return Response::error($errors);
        }

        $validated = $validator->validate();
        $transaction   = Transaction::where('trx_id',$trx_id)->first();
        
        $form_data = [
            'trx_id'         => $transaction->trx_id,
            'payable_amount' => $transaction->payable,
            'get_amount'     => $transaction->will_get_amount,
            'status'         => $validated['status'],
        ];
        try{
            
            $transaction->update([
                'status' => $validated['status'],
            ]);
            Notification::route("mail",$transaction->remittance_data->sender_email)->notify(new remittanceNotification($form_data));
            if(auth()->check()){
                UserNotification::create([
                    'user_id'  => auth()->user()->id,
                    'message'  => "Your Remittance  (Payable amount: ".get_amount($transaction->payable).",
                    Get Amount: ".get_amount($transaction->will_get_amount).") Successfully Sended.", 
                ]);
            }
        }catch(Exception $e){
            
            return back()->with(['error' => ['Something went wrong! Please try again.']]);
        }
        return back()->with(['success' => ['Transaction Status updated successfully']]);
    }
    /**
     * Method for show under review page 
     * @param string
     * @return view
     */
    public function reviewPayment(){
        $page_title    = "Review Payment Logs";
        $transactions  = Transaction::where('status',global_const()::REMITTANCE_STATUS_REVIEW_PAYMENT)->get();

        return view('admin.sections.remittance-log.review-payment',compact(
            'page_title',
            'transactions',
        ));
    }
    /**
     * Method for show Pending log page 
     * @param string
     * @return view
     */
    public function pending(){
        $page_title    = "Pending Logs";
        $transactions  = Transaction::where('status',global_const()::REMITTANCE_STATUS_PENDING)->get();

        return view('admin.sections.remittance-log.pending',compact(
            'page_title',
            'transactions',
        ));
    }
    /**
     * Method for show progress log page 
     * @param string
     * @return view
     */
    public function confirmPayment(){
        $page_title    = "Confirm Payment Logs";
        $transactions  = Transaction::where('status',global_const()::REMITTANCE_STATUS_CONFIRM_PAYMENT)->get();

        return view('admin.sections.remittance-log.confirm-payment',compact(
            'page_title',
            'transactions',
        ));
    }
    /**
     * Method for show hold log page 
     * @param string
     * @return view
     */
    public function hold(){
        $page_title    = "Hold Logs";
        $transactions  = Transaction::where('status',global_const()::REMITTANCE_STATUS_HOLD)->get();

        return view('admin.sections.remittance-log.hold',compact(
            'page_title',
            'transactions',
        ));
    }
    /**
     * Method for show settle log page 
     * @param string
     * @return view
     */
    public function settled(){
        $page_title    = "Settled Logs";
        $transactions  = Transaction::where('status',global_const()::REMITTANCE_STATUS_SETTLED)->get();

        return view('admin.sections.remittance-log.settled',compact(
            'page_title',
            'transactions',
        ));
    }
    /**
     * Method for show Complete log page 
     * @param string
     * @return view
     */
    public function complete(){
        $page_title    = "Complete Logs";
        $transactions  = Transaction::where('status',global_const()::REMITTANCE_STATUS_COMPLETE)->get();

        return view('admin.sections.remittance-log.complete',compact(
            'page_title',
            'transactions',
        ));
    }
    /**
     * Method for show canceled log page 
     * @param string
     * @return view
     */
    public function canceled(){
        $page_title    = "Canceled Logs";
        $transactions  = Transaction::where('status',global_const()::REMITTANCE_STATUS_CANCEL)->get();

        return view('admin.sections.remittance-log.cancel',compact(
            'page_title',
            'transactions',
        ));
    }
    /**
     * Method for show failed log page 
     * @param string
     * @return view
     */
    public function failed(){
        $page_title    = "Failed Logs";
        $transactions  = Transaction::where('status',global_const()::REMITTANCE_STATUS_FAILED)->get();

        return view('admin.sections.remittance-log.failed',compact(
            'page_title',
            'transactions',
        ));
    }
    /**
     * Method for show refunded page 
     * @param string
     * @return view
     */
    public function refunded(){
        $page_title    = "Refunded Logs";
        $transactions  = Transaction::where('status',global_const()::REMITTANCE_STATUS_REFUND)->get();

        return view('admin.sections.remittance-log.refunded',compact(
            'page_title',
            'transactions',
        ));
    }
    /**
     * Method for show delayed log page 
     * @param string
     * @return view
     */
    public function delayed(){
        $page_title    = "Delayed Logs";
        $transactions  = Transaction::where('status',global_const()::REMITTANCE_STATUS_DELAYED)->get();

        return view('admin.sections.remittance-log.delayed',compact(
            'page_title',
            'transactions',
        ));
    }



    public function downloadPdf($trx_id)
    {
        $transaction             = Transaction::where('trx_id',$trx_id)->first(); 
        $sender_currency         = Currency::where('status',true)->where('sender',true)->first();
        $receiver_currency       = Currency::where('status',true)->where('receiver',true)->first();

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
