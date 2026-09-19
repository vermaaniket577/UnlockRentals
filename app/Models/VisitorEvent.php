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
}
