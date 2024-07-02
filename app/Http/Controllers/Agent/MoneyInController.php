<?php

namespace App\Http\Controllers\Agent;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TemporaryData;
use App\Constants\GlobalConst;
use App\Models\Agent\AgentWallet;
use App\Http\Controllers\Controller;
use App\Constants\PaymentGatewayConst;
use App\Models\Admin\TransactionSetting;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\PaymentGatewayCurrency;

class MoneyInController extends Controller
{
    /**
     * Method for show money in index page
     * @return view
     */
    public function index(){
        $page_title                 = "MoneyIn";
        $payment_gateway            = PaymentGatewayCurrency::whereHas('gateway', function ($gateway) {
            $gateway->where('slug', PaymentGatewayConst::remittance_money_slug());
            $gateway->where('status', 1);
        })->get();
        $transaction_settings       = TransactionSetting::where('status',true)
                                        ->where('slug',GlobalConst::MONEY_IN)
                                        ->first();

        return view('agent.sections.money-in.index',compact(
            'page_title',
            'payment_gateway',
            'transaction_settings'
        ));
    }
    /**
     * Method for submit money in request
     * @param Illuminate\Http\Request $request
     */
    public function submit(Request $request){
        $validator                  = Validator::make($request->all(),[
            'amount'                =>'required|numeric',
            'payment_gateway'       => 'required'
        ]);
        if($validator->fails()){
            return back()->withErrors($validator)->withInput($request->all());
        }
        $validated                  = $validator->validate();
        $amount                     = $validated['amount'];
        $transaction_settings       = TransactionSetting::where('slug',GlobalConst::MONEY_IN)->where('status',true)->first();
        if(!$transaction_settings){
            return back()->with(['error' => ['Transaction settings not found']]);
        }
        if($amount < $transaction_settings->min_limit || $amount > $transaction_settings->max_limit){
            return back()->with(['error' => ['Please follow the transaction limit. ']]);
        }
        //
        $payment_gateway_currency   = PaymentGatewayCurrency::where('id',$validated['payment_gateway'])->first();
        if(!$payment_gateway_currency){
            return back()->with(['error' => ['Payment gateway not found']]);
        }

        $payment_gateway_rate       = $payment_gateway_currency->rate;
        $payment_gateway_code       = $payment_gateway_currency->currency_code;
        $fixed_charge               = $transaction_settings->fixed_charge;
        $percent_charge             = ($transaction_settings->percent_charge * $amount) / 100;
        $total_charge               = $fixed_charge + $percent_charge;
        $payable_amount             = ($amount + $total_charge) * $payment_gateway_rate;
        $receive_amount             = ($amount * $payment_gateway_rate);

        $data                       = [
            'type'                  => PaymentGatewayConst::MONEYIN,
            'identifier'            => Str::uuid(),
            'data'                  => [
                'base_currency'     => [
                    'currency'      => get_default_currency_code(),
                    'rate'          => get_default_currency_rate()
                ],
                'payment_gateway'   => [
                    'id'            => $payment_gateway_currency->id,
                    'alias'         => $payment_gateway_currency->alias,
                    'rate'          => $payment_gateway_rate,
                    'currency'      => $payment_gateway_code,
                    'name'          => $payment_gateway_currency->name
                ],
                'amount'            => floatval($amount),
                'fixed_charge'      => floatval($fixed_charge),
                'percent_charge'    => floatval($percent_charge),
                'total_charge'      => floatval($total_charge),
                'payable_amount'    => floatval($payable_amount),
                'receive_amount'    => floatval($receive_amount),
                'exchange_rate'     => floatval($payment_gateway_rate)
            ]                 
        ];
        try{
           $temporary_data = TemporaryData::create($data);
        }catch(Exception $e){
            return back()->with(['error' => ['Something went wrong! Please try again.']]);
        }
        return redirect()->route('agent.moneyin.preview',$temporary_data->identifier)->with(['success' => ['Money in information saved successfully.']]);     
                
    }
    /**
     * Method for view money in preview page
     * @param $identifier
     */
    public function preview($identifier){
        dd($identifier);
    }
}
