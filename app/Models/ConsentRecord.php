<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsentRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'visitor_id',
        'user_id',
        'lead_id',
        'consent_type',
        'is_granted',
        'consent_text',
        'form_source',
        'ip_address',
        'granted_at',
        'withdrawn_at',
    ];

    protected function casts(): array
    {
        return [
            'is_granted' => 'boolean',
            'granted_at' => 'datetime',
            'withdrawn_at' => 'datetime',
        ];
    }

    public function getConsentGivenAttribute()
    {
        return $this->is_granted;
    }

    public function setConsentGivenAttribute($value)
    {
        $this->attributes['is_granted'] = $value;
    }

    public function visitor(): BelongsTo
    {
        return $this->belongsTo(Visitor::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    /**
     * Withdraw this consent record.
     */
    public function withdraw(): void
    {
        $this->update([
            'is_granted' => false,
            'withdrawn_at' => now(),
        ]);
    }
}
