<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class OtpVerification extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'phone',
        'otp',
        'purpose',
        'expires_at',
        'verified_at',
        'attempts',
    ];

    protected function casts(): array
    {
        return [
            'expires_at'  => 'datetime',
            'verified_at' => 'datetime',
            'created_at'  => 'datetime',
        ];
    }

    /* ── Scopes ─────────────────────────────────── */

    public function scopeForPhone($query, string $phone)
    {
        return $query->where('phone', $phone);
    }

    public function scopeForPurpose($query, string $purpose)
    {
        return $query->where('purpose', $purpose);
    }

    public function scopePending($query)
    {
        return $query->whereNull('verified_at')
                     ->where('expires_at', '>', now())
                     ->where('attempts', '<', config('otp.max_attempts', 3));
    }

    /* ── Helpers ─────────────────────────────────── */

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function hasMaxAttempts(): bool
    {
        return $this->attempts >= config('otp.max_attempts', 3);
    }

    public function markVerified(): void
    {
        $this->update(['verified_at' => now()]);
    }

    public function incrementAttempts(): void
    {
        $this->increment('attempts');
    }
}
