<?php

namespace App\Models\Agent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentProfit extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts            = [
        'id'                    => 'integer',
        'agent_id'              => 'integer',
        'transaction_id'        => 'integer',
        'fixed_commissions'     => 'decimal:8',
        'percent_commissions'   => 'decimal:8',
        'total_commissions'     => 'decimal:8',
        'created_at'        => 'date:Y-m-d',
        'updated_at'        => 'date:Y-m-d',
    ];
}
