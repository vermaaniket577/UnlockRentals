<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PushNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'icon',
        'image_url',
        'action_url',
        'channel',
        'target_type',
        'target_value',
        'sent_count',
        'failed_count',
        'status',
        'sent_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sent_by');
    }

    /**
     * Target delivery channel human-readable label.
     */
    public function getChannelLabelAttribute(): string
    {
        return match ($this->channel ?? 'both') {
            'web'   => 'Web Push',
            'app'   => 'Mobile App',
            default => 'Web & App',
        };
    }

    /**
     * Target audience human-readable label.
     */
    public function getAudienceLabelAttribute(): string
    {
        $type = (string) ($this->target_type ?? 'all');
        $val = (string) ($this->target_value ?? '');

        return match ($type) {
            'all'           => 'All Subscribers',
            'role'          => 'Role: ' . ucfirst($val ?: 'All'),
            'specific_user' => 'User ID: ' . ($val ?: '-'),
            'topic'         => 'Topic: ' . ($val ?: '-'),
            default         => ucfirst($type ?: 'All'),
        };
    }
}
