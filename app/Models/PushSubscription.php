<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PushSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'endpoint',
        'endpoint_hash',
        'public_key',
        'auth_token',
        'fcm_token',
        'device_type',
        'user_agent',
        'ip_address',
        'last_active_at',
    ];

    protected $casts = [
        'last_active_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for active subscriptions.
     */
    public function scopeActive($query)
    {
        return $query->whereNotNull('endpoint');
    }

    /**
     * Helper to create or update a subscription safely.
     */
    public static function registerSubscription(array $data, ?int $userId = null)
    {
        $endpoint = $data['endpoint'] ?? '';
        if (empty($endpoint)) {
            return null;
        }

        $endpointHash = hash('sha256', $endpoint);

        return self::updateOrCreate(
            ['endpoint_hash' => $endpointHash],
            [
                'user_id'        => $userId,
                'endpoint'       => $endpoint,
                'public_key'     => $data['public_key'] ?? ($data['keys']['p256dh'] ?? null),
                'auth_token'     => $data['auth_token'] ?? ($data['keys']['auth'] ?? null),
                'fcm_token'      => $data['fcm_token'] ?? null,
                'device_type'    => $data['device_type'] ?? 'web',
                'user_agent'     => request()->userAgent(),
                'ip_address'     => request()->ip(),
                'last_active_at' => now(),
            ]
        );
    }
}
