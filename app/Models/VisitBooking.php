<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VisitBooking extends Model
{
    protected $fillable = [
        'user_id',
        'property_id',
        'preferred_date',
        'preferred_time',
        'name',
        'phone',
        'email',
        'message',
        'status',
        'admin_note',
    ];

    protected function casts(): array
    {
        return [
            'preferred_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Scope: only pending bookings.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Get human-readable time slot label.
     */
    public function getTimeSlotLabelAttribute(): string
    {
        return match($this->preferred_time) {
            'morning' => '9:00 AM - 12:00 PM',
            'afternoon' => '12:00 PM - 4:00 PM',
            'evening' => '4:00 PM - 7:00 PM',
            default => $this->preferred_time,
        };
    }
}
