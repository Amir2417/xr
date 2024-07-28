<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Constants\GlobalConst;
use App\Http\Helpers\Response;
use App\Models\Agent\AgentWallet;
use App\Models\Admin\BasicSettings;
use App\Http\Controllers\Controller;
use App\Constants\PaymentGatewayConst;
use App\Notifications\Admin\AgentMoneyInNotification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Admin\AgentSendRemittanceNotification;

class AgentMoneyInLogController extends Controller
{
    /**
     * Method for show send remittance page
     * @param string
     * @return view
     */
    public function index(){
        $page_title           = "All Logs";
        $transactions         = Transaction::whereNot('agent_id',null)->doesntHave('coupon_transaction')->where('type',PaymentGatewayConst::MONEYIN)->orderBy('id','desc')->paginate(10);
        
        return view('admin.sections.agent-logs.money-in.all-logs',compact(
            'page_title',
            'transactions',
        ));
    }
    /**
     * Method for show send remittance details page
     * @param $trx_id,
     * @param Illuminate\Http\Request $request
     */
    public function details(Request $request, $trx_id){
        $page_title        = "Log Details";
        $transaction       = Transaction::whereNot('agent_id',null)->where('trx_id',$trx_id)->first();
        
        
        return view('admin.sections.agent-logs.money-in.details',compact(
            'page_title',
            'transaction',
        ));
    }
    /**
     * Method for update status 
     * @param $trx_id
     * @param Illuminate\Http\Request $request
     */
    public function statusUpdate(Request $request,$trx_id){
        $basic_settings  = BasicSettings::first();
        
        $validator = Validator::make($request->all(),[
            'status'            => 'required|integer',
        ]);

        if($validator->fails()) {
            $errors = ['error' => $validator->errors() ];
            return Response::error($errors);
        }

        $validated = $validator->validate();
        $transaction   = Transaction::with(['agent'])->where('trx_id',$trx_id)->first();
        $form_data = [
            'trx_id'         => $transaction->trx_id,
            'payable_amount' => $transaction->payable,
            'request_amount'     => $transaction->request_amount,
            'status'         => $validated['status'],
            'sender_currecy' => $transaction->remittance_data->data->base_currency->currency,
            'receiver_currecy' => $transaction->remittance_data->data->payment_gateway->currency,
        ];
        try{
            
            $transaction->update([
                'status' => $validated['status'],
            ]);
           
            if($basic_settings->agent_email_notification == true){
                Notification::route("mail",$transaction->agent->email)->notify(new AgentMoneyInNotification($form_data));
            }
            if($validated['status'] == GlobalConst::REMITTANCE_STATUS_CONFIRM_PAYMENT){
                if($transaction->remark == 'Manual'){
                    $agent_wallet       = AgentWallet::where('agent_id',$transaction->agent->id)->first();
                    $agent_wallet->update([
                        'balance'   => $agent_wallet->balance + $transaction->request_amount,
                    ]);
                }
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
        $transactions  = Transaction::whereNot('agent_id',null)->doesntHave('coupon_transaction')->where('type',PaymentGatewayConst::MONEYIN)
                            ->where('status',global_const()::REMITTANCE_STATUS_REVIEW_PAYMENT)
                            ->orderBy('id','desc')->paginate(10);

        return view('admin.sections.agent-logs.money-in.review-payment',compact(
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
        $transactions  = Transaction::whereNot('agent_id',null)->doesntHave('coupon_transaction')->where('type',PaymentGatewayConst::MONEYIN)
                            ->where('status',global_const()::REMITTANCE_STATUS_PENDING)
                            ->orderBy('id','desc')->paginate(10);

        return view('admin.sections.agent-logs.money-in.pending',compact(
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
        $transactions  = Transaction::whereNot('agent_id',null)->doesntHave('coupon_transaction')->where('type',PaymentGatewayConst::MONEYIN)
                            ->where('status',global_const()::REMITTANCE_STATUS_CONFIRM_PAYMENT)
                            ->orderBy('id','desc')->paginate(10);

        return view('admin.sections.agent-logs.money-in.confirm-payment',compact(
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
        $transactions  = Transaction::whereNot('agent_id',null)->doesntHave('coupon_transaction')->where('type',PaymentGatewayConst::MONEYIN)
                            ->where('status',global_const()::REMITTANCE_STATUS_HOLD)
                            ->orderBy('id','desc')->paginate(10);

        return view('admin.sections.agent-logs.money-in.hold',compact(
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
        $transactions  = Transaction::whereNot('agent_id',null)->doesntHave('coupon_transaction')->where('type',PaymentGatewayConst::MONEYIN)
                            ->where('status',global_const()::REMITTANCE_STATUS_SETTLED)
                            ->orderBy('id','desc')->paginate(10);

        return view('admin.sections.agent-logs.money-in.settled',compact(
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
        $transactions  = Transaction::whereNot('agent_id',null)->doesntHave('coupon_transaction')->where('type',PaymentGatewayConst::MONEYIN)
                            ->where('status',global_const()::REMITTANCE_STATUS_COMPLETE)
                            ->orderBy('id','desc')->paginate(10);

        return view('admin.sections.agent-logs.money-in.complete',compact(
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
        $transactions  = Transaction::whereNot('agent_id',null)->doesntHave('coupon_transaction')->where('type',PaymentGatewayConst::MONEYIN)
                            ->where('status',global_const()::REMITTANCE_STATUS_CANCEL)
                            ->orderBy('id','desc')->paginate(10);

        return view('admin.sections.agent-logs.money-in.cancel',compact(
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
        $transactions  = Transaction::whereNot('agent_id',null)->doesntHave('coupon_transaction')->where('type',PaymentGatewayConst::MONEYIN)
                            ->where('status',global_const()::REMITTANCE_STATUS_FAILED)
                            ->orderBy('id','desc')->paginate(10);

        return view('admin.sections.agent-logs.money-in.failed',compact(
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
        $transactions  = Transaction::whereNot('agent_id',null)->doesntHave('coupon_transaction')->where('type',PaymentGatewayConst::MONEYIN)
                            ->where('status',global_const()::REMITTANCE_STATUS_REFUND)
                            ->orderBy('id','desc')->paginate(10);

        return view('admin.sections.agent-logs.money-in.refunded',compact(
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
        $transactions  = Transaction::whereNot('agent_id',null)->doesntHave('coupon_transaction')->where('type',PaymentGatewayConst::MONEYIN)
                            ->where('status',global_const()::REMITTANCE_STATUS_DELAYED)
                            ->orderBy('id','desc')->paginate(10);

        return view('admin.sections.agent-logs.money-in.delayed',compact(
            'page_title',
            'transactions',
        ));
    }
    /**
     * Method for remittance log search 
     */
   
    public function search(Request $request) {
        $validator = Validator::make($request->all(),[
            'text'  => 'required|string',
        ]);
        if($validator->fails()) {
            $error = ['error' => $validator->errors()];
            return Response::error($error,null,400);
        }

        $validated = $validator->validate();
        
        $transactions    = Transaction::search($validated['text'])->get();
       
        return view('admin.components.data-table.agent.money-in-table',compact('transactions'));
        
    }
    /**
     * Method for remittance log search 
     */
    
    public function reviewSearch(Request $request) {
        $validator = Validator::make($request->all(),[
            'text'  => 'required|string',
        ]);
        if($validator->fails()) {
            $error = ['error' => $validator->errors()];
            return Response::error($error,null,400);
        }

        $validated = $validator->validate();
        
        $transactions    = Transaction::whereNot('agent_id',null)->where('status',global_const()::REMITTANCE_STATUS_REVIEW_PAYMENT)->search($validated['text'])->get();
       
        return view('admin.components.data-table.agent.money-in-table',compact('transactions'));
        
    }
    /**
     * Method for remittance log search 
     */
    public function cancelSearch(Request $request) {
        $validator = Validator::make($request->all(),[
            'text'  => 'required|string',
        ]);
        if($validator->fails()) {
            $error = ['error' => $validator->errors()];
            return Response::error($error,null,400);
        }

        $validated = $validator->validate();
        
        $transactions    = Transaction::whereNot('agent_id',null)->where('status',global_const()::REMITTANCE_STATUS_CANCEL)->search($validated['text'])->get();
        
        

        return view('admin.components.data-table.agent.money-in-table',compact('transactions'));
        
    }
    /**
     * Method for remittance log search 
     */
    public function completeSearch(Request $request) {
        $validator = Validator::make($request->all(),[
            'text'  => 'required|string',
        ]);
        if($validator->fails()) {
            $error = ['error' => $validator->errors()];
            return Response::error($error,null,400);
        }

        $validated = $validator->validate();
        
        $transactions    = Transaction::whereNot('agent_id',null)->where('status',global_const()::REMITTANCE_STATUS_COMPLETE)->search($validated['text'])->get();
       
        return view('admin.components.data-table.agent.money-in-table',compact('transactions'));
        
    }
    /**
     * Method for remittance log search 
     */
    
    public function confirmPaymentSearch(Request $request) {
        $validator = Validator::make($request->all(),[
            'text'  => 'required|string',
        ]);
        if($validator->fails()) {
            $error = ['error' => $validator->errors()];
            return Response::error($error,null,400);
        }

        $validated = $validator->validate();
        
        $transactions    = Transaction::whereNot('agent_id',null)->where('status',global_const()::REMITTANCE_STATUS_CONFIRM_PAYMENT)->search($validated['text'])->get();
       
        return view('admin.components.data-table.agent.money-in-table',compact('transactions'));
        
    }
    /**
     * Method for remittance log search 
     */
    
    public function holdSearch(Request $request) {
        $validator = Validator::make($request->all(),[
            'text'  => 'required|string',
        ]);
        if($validator->fails()) {
            $error = ['error' => $validator->errors()];
            return Response::error($error,null,400);
        }

        $validated = $validator->validate();
        
        $transactions    = Transaction::whereNot('agent_id',null)->where('status',global_const()::REMITTANCE_STATUS_HOLD)->search($validated['text'])->get();
       
        return view('admin.components.data-table.agent.money-in-table',compact('transactions'));
        
    }
    /**
     * Method for remittance log search 
     */
    
    public function settledSearch(Request $request) {
        $validator = Validator::make($request->all(),[
            'text'  => 'required|string',
        ]);
        if($validator->fails()) {
            $error = ['error' => $validator->errors()];
            return Response::error($error,null,400);
        }

        $validated = $validator->validate();
        
        $transactions    = Transaction::whereNot('agent_id',null)->where('status',global_const()::REMITTANCE_STATUS_SETTLED)->search($validated['text'])->get();
       
        return view('admin.components.data-table.agent.money-in-table',compact('transactions'));
        
    }
    /**
     * Method for remittance log search 
     */
    
    public function pendingSearch(Request $request) {
        $validator = Validator::make($request->all(),[
            'text'  => 'required|string',
        ]);
        if($validator->fails()) {
            $error = ['error' => $validator->errors()];
            return Response::error($error,null,400);
        }

        $validated = $validator->validate();
        
        $transactions    = Transaction::whereNot('agent_id',null)->where('status',global_const()::REMITTANCE_STATUS_PENDING)->search($validated['text'])->get();
       
        return view('admin.components.data-table.agent.money-in-table',compact('transactions'));
        
    }
    /**
     * Method for remittance log search 
     */
    
    public function delayedSearch(Request $request) {
        $validator = Validator::make($request->all(),[
            'text'  => 'required|string',
        ]);
        if($validator->fails()) {
            $error = ['error' => $validator->errors()];
            return Response::error($error,null,400);
        }

        $validated = $validator->validate();
        
        $transactions    = Transaction::whereNot('agent_id',null)->where('status',global_const()::REMITTANCE_STATUS_DELAYED)->search($validated['text'])->get();
       
        return view('admin.components.data-table.agent.money-in-table',compact('transactions'));
        
    }
    /**
     * Method for remittance log search 
     */
    
    public function failedSearch(Request $request) {
        $validator = Validator::make($request->all(),[
            'text'  => 'required|string',
        ]);
        if($validator->fails()) {
            $error = ['error' => $validator->errors()];
            return Response::error($error,null,400);
        }

        $validated = $validator->validate();
        
        $transactions    = Transaction::whereNot('agent_id',null)->where('status',global_const()::REMITTANCE_STATUS_FAILED)->search($validated['text'])->get();
       
        return view('admin.components.data-table.agent.money-in-table',compact('transactions'));
        
    }
    /**
     * Method for remittance log search 
     */
    
    public function refundedSearch(Request $request) {
        $validator = Validator::make($request->all(),[
            'text'  => 'required|string',
        ]);
        if($validator->fails()) {
            $error = ['error' => $validator->errors()];
            return Response::error($error,null,400);
        }

        $validated = $validator->validate();
        
        $transactions    = Transaction::whereNot('agent_id',null)->where('status',global_const()::REMITTANCE_STATUS_REFUND)->search($validated['text'])->get();
       
        return view('admin.components.data-table.agent.money-in-table',compact('transactions'));
        
    }


    
}
