<?php

namespace App\Models\Agent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MySender extends Model
{
    use HasFactory;

    protected $guarded      = ['id'];

    protected $casts        = [
        'id'                => 'integer',
        'agent_id'          => 'integer',
        'slug'              => 'string',
        'first_name'        => 'string',
        'last_name'         => 'string',
        'email'             => 'string',
        'country'           => 'string',
        'city'              => 'string',
        'state'             => 'string',
        'zip_code'          => 'string',
        'phone'             => 'string',
        'address'           => 'string',
        'created_at'        => 'date:Y-m-d',
        'updated_at'        => 'date:Y-m-d',
    ];

    public function scopeAuth($q){
        $q->where('agent_id',auth()->user()->id);
    }
}
