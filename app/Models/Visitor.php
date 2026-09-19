<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Visitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'visitor_uuid',
        'user_id',
        'first_seen_at',
        'last_seen_at',
        'first_landing_url',
        'last_url',
        'referrer',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content',
        'device_type',
        'browser',
        'operating_system',
        'country',
        'state',
        'city',
        'total_sessions',
        'total_page_views',
        'total_property_views',
        'first_property_id',
        'last_property_id',
        'engagement_score',
        'engagement_tier',
        'has_converted_lead',
    ];

    protected function casts(): array
    {
        return [
            'first_seen_at' => 'datetime',
            'last_seen_at' => 'datetime',
            'has_converted_lead' => 'boolean',
            'total_sessions' => 'integer',
            'total_page_views' => 'integer',
            'total_property_views' => 'integer',
            'engagement_score' => 'integer',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($visitor) {
            if (empty($visitor->visitor_uuid)) {
                $visitor->visitor_uuid = (string) Str::uuid();
            }
            if (empty($visitor->first_seen_at)) {
                $visitor->first_seen_at = now();
            }
            if (empty($visitor->last_seen_at)) {
                $visitor->last_seen_at = now();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(VisitorSession::class)->latest('started_at');
    }

    public function events(): HasMany
    {
        return $this->hasMany(VisitorEvent::class)->latest('created_at');
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class)->latest();
    }

    public function lead(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Lead::class)->latestOfMany();
    }

    public function latestSession(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(VisitorSession::class)->latestOfMany('started_at');
    }

    public function firstProperty(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'first_property_id');
    }

    public function lastProperty(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'last_property_id');
    }

    public function consentRecords(): HasMany
    {
        return $this->hasMany(ConsentRecord::class);
    }

    /**
     * Scope: high engagement visitors.
     */
    public function scopeHighEngagement($query)
    {
        return $query->where('engagement_tier', 'high');
    }

    /**
     * Scope: visitors active in given timeframe.
     */
    public function scopeActiveSince($query, $date)
    {
        return $query->where('last_seen_at', '>=', $date);
    }

    /**
     * Increment engagement score and update tier dynamically.
     */
    public function addEngagementScore(int $points): void
    {
        $newScore = $this->engagement_score + $points;
        $tier = match (true) {
            $newScore >= 25 => 'high',
            $newScore >= 10 => 'medium',
            default => 'low',
        };

        $this->update([
            'engagement_score' => $newScore,
            'engagement_tier' => $tier,
            'last_seen_at' => now(),
        ]);
    }
}
