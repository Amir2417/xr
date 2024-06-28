<?php

namespace App\Models\Agent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentWallet extends Model
{
    use HasFactory;

    protected $guarded  = ['id'];

    protected $casts    = [
        'id'            => 'integer',
        'agent_id'      => 'integer',
        'currency_id'   => 'integer',
        'balance'       => 'decimal:8',
        'status'        => 'integer',
        'created_at'    => 'date:Y-m-d',
        'updated_at'    => 'date:Y-m-d',
    ];
}
