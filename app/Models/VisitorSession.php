<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VisitorSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'visitor_id',
        'session_uuid',
        'landing_page',
        'referrer',
        'started_at',
        'ended_at',
        'last_activity_at',
        'page_views',
        'property_views',
        'device_type',
        'browser',
        'operating_system',
        'utm_source',
        'utm_medium',
        'utm_campaign',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
            'last_activity_at' => 'datetime',
            'page_views' => 'integer',
            'property_views' => 'integer',
        ];
    }

    public function visitor(): BelongsTo
    {
        return $this->belongsTo(Visitor::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(VisitorEvent::class, 'session_id');
    }
}
