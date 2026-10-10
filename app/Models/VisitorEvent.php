<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VisitorEvent extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'visitor_id',
        'session_id',
        'user_id',
        'event_name',
        'property_id',
        'city_id',
        'locality_id',
        'page_url',
        'metadata',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'created_at' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            if (empty($event->created_at)) {
                $event->created_at = now();
            }
        });
    }

    public function visitor(): BelongsTo
    {
        return $this->belongsTo(Visitor::class);
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(VisitorSession::class, 'session_id');
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
     * Get human-friendly event label.
     */
    public function getLabelAttribute(): string
    {
        return match ($this->event_name) {
            'page_view' => 'Viewed Page',
            'property_view' => 'Viewed Property',
            'search' => 'Searched Properties',
            'filter_applied' => 'Filtered Properties',
            'whatsapp_clicked' => 'Clicked WhatsApp Chat',
            'contact_owner_clicked' => 'Clicked Contact Owner',
            'enquiry_started' => 'Started Enquiry',
            'enquiry_submitted' => 'Submitted Enquiry',
            'phone_revealed' => 'Unlocked Owner Phone',
            'visit_scheduled' => 'Booked Property Visit',
            'favorite_property' => 'Saved Property to Favorites',
            'share_property' => 'Shared Property Link',
            'exit_intent_shown' => 'Exit Intent Modal Shown',
            'exit_intent_submitted' => 'Submitted Exit Intent Form',
            'login' => 'Signed In',
            'registration' => 'Registered Account',
            default => ucwords(str_replace('_', ' ', $this->event_name)),
        };
    }

    /**
     * Get the visitor physical origin city (where user is searching from).
     */
    public function getOriginCity(): string
    {
        $city = $this->metadata['visitor_city'] ?? null;
        if (empty($city) || $city === 'Location Detected') {
            $city = $this->visitor?->city;
        }
        return !empty($city) ? ucwords(strtolower(trim($city))) : 'Detected Location';
    }

    /**
     * Get the visitor physical origin state.
     */
    public function getOriginState(): string
    {
        $state = $this->metadata['visitor_state'] ?? null;
        if (empty($state) || $state === 'India') {
            $state = $this->visitor?->state;
        }
        return !empty($state) ? ucwords(strtolower(trim($state))) : 'India';
    }

    /**
     * Get the visitor physical origin IP address.
     */
    public function getOriginIp(): ?string
    {
        return $this->metadata['visitor_ip'] ?? $this->visitor?->ip_address ?? null;
    }

    /**
     * Get the visitor device platform.
     */
    public function getOriginDevice(): string
    {
        return $this->metadata['device_type'] ?? $this->visitor?->device_type ?? 'desktop';
    }

    /**
     * Get the visitor browser.
     */
    public function getOriginBrowser(): string
    {
        return $this->metadata['browser'] ?? $this->visitor?->browser ?? 'Browser';
    }

    /**
     * Get human-readable target location searched for.
     */
    public function getTargetLocationDisplay(): string
    {
        $meta = $this->metadata ?? [];
        $parts = [];
        if (!empty($meta['locality'])) {
            $parts[] = ucwords(str_replace('-', ' ', $meta['locality']));
        }
        if (!empty($meta['district'])) {
            $parts[] = ucwords(str_replace('-', ' ', $meta['district']));
        } elseif (!empty($meta['state'])) {
            $parts[] = ucwords(str_replace('-', ' ', $meta['state']));
        }
        if (!empty($parts)) {
            return implode(', ', $parts);
        }
        if (!empty($meta['search'])) {
            return trim((string) $meta['search'], " \t\n\r\0\x0B\"'");
        }
        if (!empty($meta['near_me'])) {
            return 'Near Me (GPS)';
        }
        return 'All Listings';
    }

    /**
     * Get structured list of applied search filters.
     */
    public function getSearchCriteriaList(): array
    {
        $criteria = [];
        $meta = $this->metadata ?? [];

        if (!empty($meta['district'])) {
            $criteria[] = ['label' => 'City', 'value' => ucwords(str_replace('-', ' ', $meta['district'])), 'badge' => 'bg-blue-50 text-blue-700 border-blue-200'];
        }
        if (!empty($meta['locality'])) {
            $criteria[] = ['label' => 'Locality', 'value' => ucwords(str_replace('-', ' ', $meta['locality'])), 'badge' => 'bg-indigo-50 text-indigo-700 border-indigo-200'];
        }
        if (!empty($meta['search'])) {
            $criteria[] = ['label' => 'Query', 'value' => $meta['search'], 'badge' => 'bg-amber-50 text-amber-700 border-amber-200'];
        }
        if (!empty($meta['rooms'])) {
            $criteria[] = ['label' => 'BHK', 'value' => strtoupper($meta['rooms']), 'badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200'];
        }
        if (!empty($meta['type'])) {
            $criteria[] = ['label' => 'Type', 'value' => ucfirst($meta['type']), 'badge' => 'bg-purple-50 text-purple-700 border-purple-200'];
        }
        if (!empty($meta['price'])) {
            $criteria[] = ['label' => 'Budget', 'value' => $meta['price'], 'badge' => 'bg-rose-50 text-rose-700 border-rose-200'];
        }
        if (!empty($meta['near_me'])) {
            $criteria[] = ['label' => 'Distance', 'value' => 'Near Me (GPS)', 'badge' => 'bg-teal-50 text-teal-700 border-teal-200'];
        }

        return $criteria;
    }

    /**
     * Results found count.
     */
    public function getResultsCount(): int
    {
        return (int) ($this->metadata['results_count'] ?? 0);
    }
}
