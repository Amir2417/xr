<?php

namespace App\Http\Controllers\Api\V1\Agent;

use Illuminate\Http\Request;
use App\Models\Agent\AgentProfit;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Response;

class AgentProfitLogController extends Controller
{
    /**
     * Method for profit logs data
     */
    public function index(){
        $transactions   = AgentProfit::auth()->with(['transaction'])->orderBy('id','desc')->get()->map(function($data){
            return [
                'currency'          => get_default_currency_code(),
                'type'              => $data->transaction->type,
                'transaction_id'    => $data->transaction->trx_id,
                'total_commissions' => floatval($data->total_commissions),
                'created_date'      => $data->created_at->format('d'),
                'created_month'     => $data->created_at->format('F'),
            ];
        });
        
        return Response::success(['Agent profits data fetch successfully.'],$transactions,200);
    }
}
