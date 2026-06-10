<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrivateUserOffer extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'discounted_price',
        'status',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'discounted_price' => 'decimal:2',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
    
    public function getEffectivePriceAttribute()
    {
        return $this->discounted_price !== null ? $this->discounted_price : $this->plan->price;
    }
    
    public function getFormattedEffectivePriceAttribute()
    {
        return '₹' . number_format($this->effective_price, 0);
    }
}
