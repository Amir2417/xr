<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponTransaction extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts        = [
        'id'                => 'integer',
        'user_id'           => 'integer',
        'coupon_id'         => 'integer',
        'new_user_bonus_id' => 'integer',
        'transaction_id'    => 'integer'
    ];

    //auth relation
    public function scopeAuth($q){
        return $q->where('user_id',auth()->user()->id);
    }
}
