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
        return match ($this->target_type) {
            'all'           => 'All Subscribers',
            'role'          => 'Role: ' . ucfirst($this->target_value ?? 'All'),
            'specific_user' => 'User ID: ' . $this->target_value,
            'topic'         => 'Topic: ' . $this->target_value,
            default         => ucfirst($this->target_type),
        };
    }
}
