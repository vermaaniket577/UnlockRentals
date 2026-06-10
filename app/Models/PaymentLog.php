<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentLog extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'method',
        'status',
        'transaction_id',
        'details',
    ];

    protected $casts = [
        'details' => 'array',
    ];
}
