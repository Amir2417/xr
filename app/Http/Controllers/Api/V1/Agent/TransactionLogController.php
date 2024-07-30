<?php

namespace App\Http\Controllers\Api\V1\Agent;

use App\Constants\GlobalConst;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Constants\PaymentGatewayConst;
use App\Http\Helpers\Response;

class TransactionLogController extends Controller
{
    /**
     * Method for get transaction log data
     * @return response
     */
    public function index(){
        $send_remittance    = Transaction::agentAuth()->where('type',PaymentGatewayConst::TYPESENDREMITTANCE)->orderBy('id','desc')->get()->map(function($data){
            return [
                'transactin_type'       => $data->remittance_data->data->transaction_type->name,
                'type'                  => $data->type,
                'transaction_id'        => $data->trx_id,
                'account_name'          => $data->remittance_data->data->recipient->account_name,
                'account_number'        => $data->remittance_data->data->recipient->account_number,
                'sender_currency'       => $data->remittance_data->data->base_currency->code,
                'sender_rate'           => 1,
                'receiver_currency'     => $data->remittance_data->data->receiver_currency->code,
                'sender_name'           => $data->remittance_data->data->sender->fullname,
                'recipient_name'        => $data->remittance_data->data->recipient->fullname,
                'request_amount'        => floatval($data->request_amount),
                'convert_amount'        => floatval($data->convert_amount),
                'total_charge'          => floatval($data->fees),
                'will_get_amount'       => floatval($data->will_get_amount),
                'exchange_rate'         => floatval($data->exchange_rate),
                'attribute'             => $data->attribute,
                'remark'                => $data->remark,
                'status'                => $this->get_status($data->status),
                'created_at'            => $data->created_at->format('Y-m-d'),
            ];
        });

        //money in
        $money_in    = Transaction::agentAuth()->where('type',PaymentGatewayConst::MONEYIN)->orderBy('id','desc')->get()->map(function($data){
            return [
                'type'                  => $data->type,
                'transaction_id'        => $data->trx_id,
                'sender_currency'       => $data->remittance_data->data->base_currency->currency,
                'sender_rate'           => 1,
                'payment_method'        => $data->remittance_data->data->payment_gateway->name,
                'gateway_currency'     => $data->remittance_data->data->payment_gateway->currency,
                
                'request_amount'        => floatval($data->request_amount),
                'total_charge'          => floatval($data->fees),
                'payable_amount'        => floatval($data->payable),
                'will_get_amount'       => floatval($data->will_get_amount),
                'exchange_rate'         => floatval($data->exchange_rate),
                'attribute'             => $data->attribute,
                'remark'                => $data->remark,
                'status'                => $this->get_status($data->status),
                'created_at'            => $data->created_at->format('Y-m-d'),
            ];
        });
        //money out
        $money_out    = Transaction::agentAuth()->where('type',PaymentGatewayConst::MONEYOUT)->orderBy('id','desc')->get()->map(function($data){
            return [
                'type'                  => $data->type,
                'transaction_id'        => $data->trx_id,
                'sender_currency'       => $data->remittance_data->data->base_currency->currency,
                'sender_rate'           => 1,
                'payment_method'        => $data->remittance_data->data->payment_gateway->name,
                'gateway_currency'     => $data->remittance_data->data->payment_gateway->currency,
                
                'request_amount'        => floatval($data->request_amount),
                'total_charge'          => floatval($data->fees),
                'payable_amount'        => floatval($data->payable),
                'exchange_rate'         => floatval($data->exchange_rate),
                'attribute'             => $data->attribute,
                'remark'                => $data->remark,
                'status'                => $this->get_status($data->status),
                'created_at'            => $data->created_at->format('Y-m-d'),
            ];
        });

        return Response::success(['Transaction data fetch successfully.'],[
            'send_remittance'   => $send_remittance, 
            'money_in'          => $money_in, 
            'money_out'         => $money_out, 
        ],200);
    }
    function get_status($status){
        switch ($status) {
            case GlobalConst::REMITTANCE_STATUS_REVIEW_PAYMENT:
                $status_data = "Review Payment";
                return $status_data;
                
            case GlobalConst::REMITTANCE_STATUS_PENDING:
                $status_data = "Pending";
                return $status_data;
            case GlobalConst::REMITTANCE_STATUS_CONFIRM_PAYMENT:
                $status_data = "Confirm Payment";
                return $status_data;
            case GlobalConst::REMITTANCE_STATUS_HOLD:
                $status_data = "Hold";
                return $status_data;
            case GlobalConst::REMITTANCE_STATUS_SETTLED:
                $status_data = "Settled";
                return $status_data;
            case GlobalConst::REMITTANCE_STATUS_COMPLETE:
                $status_data = "Complete";
                return $status_data;
            case GlobalConst::REMITTANCE_STATUS_CANCEL:
                $status_data = "Cancel";
                return $status_data;
            case GlobalConst::REMITTANCE_STATUS_FAILED:
                $status_data = "Failed";
                return $status_data;
            case GlobalConst::REMITTANCE_STATUS_REFUND:
                $status_data = "Refund";
                return $status_data;
            case GlobalConst::REMITTANCE_STATUS_DELAYED:
                $status_data = "Delayed";
                return $status_data;

            
            
        }
    }
}
