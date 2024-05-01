<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCoupon extends Model
{
    use HasFactory;

    protected $guarded  = ['id'];

    protected $casts    = [
        'id'            => 'integer',
        'user_id'       => 'integer',
        'coupon_name'   => 'string',
        'amount'        => 'decimal:8',
        'status'        => 'integer'
    ];
    
}
