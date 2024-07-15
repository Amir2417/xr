<?php

namespace App\Http\Controllers\Agent;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Constants\GlobalConst;
use App\Models\Admin\Currency;
use App\Models\Agent\MySender;
use App\Models\Agent\AgentWallet;
use App\Http\Controllers\Controller;
use App\Models\Agent\AgentRecipient;
use App\Constants\PaymentGatewayConst;
use App\Models\Admin\TransactionSetting;
use App\Models\TemporaryData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
        
        $validator                  = Validator::make($request->all(), [
            'amount'                =>'required',
            'receiver_currency'     =>'required',
            'sender'                =>'required',
            'recipient'             =>'required',
            'transaction_type'      =>'required',            
        ]);
        if($validator->fails()) return back()->withErrors($validator)->withInput($request->all());
        $validated                  = $validator->validate();
        $amount                     = $validated['amount'];
        $agent_wallet               = AgentWallet::auth()->first();
        if(!$agent_wallet){
            return back()->with(['error' => ['Sorry! No wallet found.']]);
        }

        if($agent_wallet->balance < $amount){
            return back()->with(['error' => ['Sorry! Insufficient balance.']]);
        }

        $receiver_currency          = Currency::where('id',$validated['receiver_currency'])->where('status',true)->first();
        $sender                     = MySender::where('id',$validated['sender'])->first();
        $recipient                  = AgentRecipient::where('id',$validated['recipient'])->first();
        $default_currency           = Currency::default();
        $transaction_settings       = TransactionSetting::where('id',$validated['transaction_type'])->where('status',true)->first();
        if(!$transaction_settings) return back()->with(['error' => ['Sorry! Transaction type not found.']]);

        $intervals = get_intervals_data($amount,$transaction_settings);
        
        if($intervals == false){
            return back()->with(['error' => ['Please follow the transaction limit.']]);
        }
        $fixed_charge               = $intervals['fixed_charge'];
        $percent_charge             = ($amount * $intervals['percent_charge']) / 100;
        $total_charge               = $fixed_charge + $percent_charge;
        $convert_amount             = $amount - $total_charge;
        $receive_amount             = $convert_amount * $receiver_currency->rate;
        
        if($transaction_settings->agent_profit == true){
            $agent_profit_status                = $transaction_settings->agent_profit;
            $agent_fixed_commissions            = $transaction_settings->agent_fixed_commissions;
            $agent_percent_commissions          = ($transaction_settings->agent_percent_commissions * $amount) / 100;
            $total_commissions                  = $agent_fixed_commissions + $agent_percent_commissions; 
        }else{
            $agent_profit_status                = false;
            $agent_fixed_commissions            = 0;
            $agent_percent_commissions          = 0;
            $total_commissions                  = 0;
        }
       
        $data                                   = [
            'base_currency'                     => [
                'code'                          => $default_currency->code,
                'rate'                          => $default_currency->rate,
            ],
            'receiver_currency'                  => [
                'code'                          => $receiver_currency->code,
                'rate'                          => $receiver_currency->rate,
            ],
            'sender'                            => [
                'slug'                          => $sender->slug,
                'fullname'                      => $sender->fullname,
                'email'                         => $sender->email,
                'country'                       => $sender->country,    
            ],
            'recipient'                         => [
                'slug'                          => $recipient->slug,
                'fullname'                      => $recipient->fullname,
                'method'                        => $recipient->method,
                'account_name'                  => $recipient->bank_name ?? ($recipient->mobile_name ?? $recipient->pickup_point),
                'account_number'                => $recipient->iban_number ?? ($recipient->account_number ?? 'N/A'),
            ],
            'agent_profit'                      => [
                'agent_profit_status'           => $agent_profit_status,
                'fixed_commission'              => floatval($agent_fixed_commissions),
                'percent_commission'            => floatval($agent_percent_commissions),
                'total_commission'              => floatval($total_commissions),
            ],
            'transaction_type'                  => [
                'id'                            => $transaction_settings->id,
                'name'                          => $transaction_settings->name,
            ],
            'amount'                            => floatval($amount),
            'fixed_charge'                      => floatval($fixed_charge),
            'percent_charge'                    => floatval($percent_charge),
            'total_charge'                      => floatval($total_charge),
            'convert_amount'                    => floatval($convert_amount),
            'receive_amount'                    => floatval($receive_amount),
            'exchange_rate'                     => floatval($receiver_currency->rate),
        ];

        
        try{
            $insert_record_id         = $this->insertRecord($data,$agent_wallet);
        }catch(Exception $e){
            return back()->with(['error' => ['Something went wrong! Please try again.']]);
        }
        return back()->with(['success' => ['Your request has been sent to admin.']]);
    }
    /**
     * function to insert record
     */
    public function insertRecord($data,$agent_wallet){
        $trx_id = generateTrxString("transactions","trx_id","SR",8);
        DB::beginTransaction();
        try{
            $id                     = DB::table('transactions')->insertGetId([
                'agent_id'          => auth()->user()->id,
                'agent_wallet_id'   => $agent_wallet->id,
                'type'              => PaymentGatewayConst::TYPESENDREMITTANCE,
                'remittance_data'   => json_encode($data),
                'trx_id'            => $trx_id,
                'request_amount'                => $data->amount,
                'exchange_rate'                 => $data->exchange_rate,
                'payable'                       => $data->amount,
                'fees'                          => $data->total_charge,
                'convert_amount'                => $data->convert_amount,
                'will_get_amount'               => $data->receive_amount,
                'remark'                        => 'Send Remittance Request send to Admin.',
                'status'                        => global_const()::REMITTANCE_STATUS_PENDING,
                'attribute'                     => PaymentGatewayConst::SEND,
                'created_at'                    => now(),
            ]);
            
            $this->updateWalletBalance($agent_wallet,$data->amount);
            $this->insertNotificationData($trx_id,$data);
        }catch(Exception $e){
            DB::rollback();
            throw new Exception($e->getMessage());
        }
    }
    /**
     * Method for update wallet balance
     */
    function updateWalletBalance($agent_wallet,$amount){
        $agent_wallet->balance = $agent_wallet->balance - $amount;
        $agent_wallet->update([
            'balance' => $agent_wallet->balance,
        ]);
    }
}
