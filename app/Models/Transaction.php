<?php

namespace App\Models;


use App\Models\Admin\PaymentGatewayCurrency;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $appends = [];

    protected $casts = [
        'id'                          => 'integer',
        'admin_id'                    => 'integer',
        'user_id'                     => 'integer',
        
        'payment_gateway_currency_id' => 'integer',
        'type'                        => "string",
        'remittance_data'             => 'object',
        'trx_id'                      => 'string',
        'request_amount'              => 'decimal:16',
        'exchange_rate'               => 'decimal:16',
        'payable'                     => 'decimal:16',
        'fees'                        => 'decimal:16',
        'convert_amount'              => 'decimal:16',
        'will_get_amount'             => 'decimal:16',
        'remark'                      => 'string',
        'details'                     => 'string',
        'reject_reason'               => 'string',
        'status'                      => 'integer',
        'attribute'                   => 'string',
        'created_at'                  => 'date:Y-m-d',
        'updated_at'                  => 'date:Y-m-d',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function user_wallet()
    {
        return $this->belongsTo(UserWallet::class, 'user_wallet_id');
    }

    public function currency()
    {
        return $this->belongsTo(PaymentGatewayCurrency::class,'payment_gateway_currency_id');
    }
    
}
