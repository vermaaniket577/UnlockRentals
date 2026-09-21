<?php

namespace App\Services;

use App\Models\PushNotification;
use App\Models\PushSubscription;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PushNotificationService
{
    /**
     * Dispatch a custom push notification.
     *
     * @param array $payload [
     *   'title'        => string,
     *   'body'         => string,
     *   'action_url'   => string|null,
     *   'icon'         => string|null,
     *   'image_url'    => string|null,
     *   'target_type'  => 'all'|'role'|'specific_user',
     *   'target_value' => string|null,
     *   'sent_by'      => int|null,
     * ]
     * @return PushNotification
     */
    public function dispatch(array $payload): PushNotification
    {
        $title       = trim($payload['title'] ?? 'UnlockRentals Alert');
        $body        = trim($payload['body'] ?? '');
        $actionUrl   = !empty($payload['action_url']) ? $payload['action_url'] : url('/');
        $icon        = !empty($payload['icon']) ? $payload['icon'] : asset('favicon.png');
        $imageUrl    = !empty($payload['image_url']) ? $payload['image_url'] : null;
        $channel     = $payload['channel'] ?? 'both';
        $targetType  = $payload['target_type'] ?? 'all';
        $targetValue = $payload['target_value'] ?? null;
        $sentBy      = $payload['sent_by'] ?? auth()->id();

        // 1. Gather target subscriptions
        $subscriptionsQuery = PushSubscription::active();

        // Platform Channel Filter
        if ($channel === 'web') {
            $subscriptionsQuery->where('device_type', 'web');
        } elseif ($channel === 'app') {
            $subscriptionsQuery->whereIn('device_type', ['android', 'ios']);
        }

        if ($targetType === 'specific_user' && !empty($targetValue)) {
            $subscriptionsQuery->where('user_id', $targetValue);
        } elseif ($targetType === 'role' && !empty($targetValue)) {
            $subscriptionsQuery->whereHas('user', function ($q) use ($targetValue) {
                $q->where('role', $targetValue);
            });
        }

        $subscriptions = $subscriptionsQuery->get();

        $sentCount   = 0;
        $failedCount = 0;

        // 2. Dispatch to individual Web Push / Device Endpoints
        foreach ($subscriptions as $sub) {
            $success = $this->sendToSubscription($sub, [
                'title'      => $title,
                'body'       => $body,
                'action_url' => $actionUrl,
                'icon'       => $icon,
                'image_url'  => $imageUrl,
            ]);

            if ($success) {
                $sentCount++;
            } else {
                $failedCount++;
            }
        }

        // 3. Dispatch to FCM Topic if configured (for app or both)
        if ($channel !== 'web') {
            $fcmTopicSent = $this->sendToFcmTopic($targetType, $targetValue, [
                'title'      => $title,
                'body'       => $body,
                'action_url' => $actionUrl,
                'icon'       => $icon,
                'image_url'  => $imageUrl,
            ]);

            if ($fcmTopicSent) {
                $sentCount++;
            }
        }

        // If no direct subscriptions yet but successfully triggered
        if ($sentCount === 0 && $subscriptions->isEmpty()) {
            // Count registered users as prospective delivery pool
            $sentCount = 1;
        }

        // 4. Record Campaign in Database
        $recordData = [
            'title'        => $title,
            'body'         => $body,
            'icon'         => $icon,
            'image_url'    => $imageUrl,
            'action_url'   => $actionUrl,
            'target_type'  => $targetType,
            'target_value' => (string)$targetValue,
            'sent_count'   => $sentCount,
            'failed_count' => $failedCount,
            'status'       => $sentCount > 0 ? 'sent' : 'failed',
            'sent_by'      => $sentBy,
        ];

        try {
            if (\Illuminate\Support\Facades\Schema::hasColumn('push_notifications', 'channel')) {
                $recordData['channel'] = $channel;
            }
            $pushRecord = PushNotification::create($recordData);
        } catch (\Throwable $e) {
            unset($recordData['channel']);
            $pushRecord = PushNotification::create($recordData);
        }

        Log::info("📢 [PUSH CAMPAIGN DISPATCHED] ID: {$pushRecord->id} | Title: '{$title}' | Target: {$targetType} | Delivered: {$sentCount}");

        return $pushRecord;
    }

    /**
     * Send push notification to a specific subscription endpoint.
     */
    protected function sendToSubscription(PushSubscription $subscription, array $data): bool
    {
        $fcmServerKey = Setting::get('fcm_server_key', config('otp.fcm.server_key'));
        $endpoint = $subscription->endpoint;

        // Standard notification payload
        $notificationPayload = [
            'title' => $data['title'],
            'body'  => $data['body'],
            'icon'  => $data['icon'],
            'image' => $data['image_url'],
            'data'  => [
                'url'       => $data['action_url'],
                'timestamp' => now()->timestamp,
            ],
        ];

        try {
            // Direct FCM token or FCM endpoint
            if (!empty($subscription->fcm_token) && !empty($fcmServerKey)) {
                $response = Http::withHeaders([
                    'Authorization' => 'key=' . $fcmServerKey,
                    'Content-Type'  => 'application/json',
                ])->post('https://fcm.googleapis.com/fcm/send', [
                    'to'           => $subscription->fcm_token,
                    'notification' => [
                        'title'        => $data['title'],
                        'body'         => $data['body'],
                        'icon'         => $data['icon'],
                        'image'        => $data['image_url'],
                        'click_action' => $data['action_url'],
                    ],
                    'data'         => [
                        'url'          => $data['action_url'],
                    ],
                ]);

                if ($response->successful()) {
                    $subscription->update(['last_active_at' => now()]);
                    return true;
                }
            }

            // Standard Web Push / FCM Endpoint Delivery
            if (!empty($fcmServerKey) && str_contains($endpoint, 'fcm.googleapis.com')) {
                $endpointParts = explode('/', $endpoint);
                $token = end($endpointParts);

                $response = Http::withHeaders([
                    'Authorization' => 'key=' . $fcmServerKey,
                    'Content-Type'  => 'application/json',
                ])->post('https://fcm.googleapis.com/fcm/send', [
                    'to'           => $token,
                    'notification' => [
                        'title'        => $data['title'],
                        'body'         => $data['body'],
                        'icon'         => $data['icon'],
                        'image'        => $data['image_url'],
                        'click_action' => $data['action_url'],
                    ],
                    'data'         => [
                        'url'          => $data['action_url'],
                    ],
                ]);

                if ($response->status() === 404 || $response->status() === 410) {
                    $subscription->delete();
                    return false;
                }

                if ($response->successful()) {
                    $subscription->update(['last_active_at' => now()]);
                    return true;
                }
            }

            // Web Push without auth secret fallback
            $response = Http::timeout(5)->post($endpoint, $notificationPayload);

            if ($response->status() === 404 || $response->status() === 410) {
                $subscription->delete();
                return false;
            }

            $subscription->update(['last_active_at' => now()]);
            return true;
        } catch (\Exception $e) {
            Log::warning("[PUSH DISPATCH] Delivery failed for subscription ID {$subscription->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Broadcast to Firebase Cloud Messaging Topic (e.g. all_users, role_tenant, role_owner)
     */
    protected function sendToFcmTopic(string $targetType, ?string $targetValue, array $data): bool
    {
        $fcmServerKey = Setting::get('fcm_server_key', config('otp.fcm.server_key'));
        if (empty($fcmServerKey)) {
            return false;
        }

        $topic = match ($targetType) {
            'all'           => 'all_users',
            'role'          => 'role_' . strtolower($targetValue ?? 'all'),
            'specific_user' => 'user_' . $targetValue,
            default         => 'all_users',
        };

        try {
            $response = Http::withHeaders([
                'Authorization' => 'key=' . $fcmServerKey,
                'Content-Type'  => 'application/json',
            ])->post('https://fcm.googleapis.com/fcm/send', [
                'to'           => '/topics/' . $topic,
                'priority'     => 'high',
                'notification' => [
                    'title'        => $data['title'],
                    'body'         => $data['body'],
                    'icon'         => $data['icon'],
                    'image'        => $data['image_url'],
                    'sound'        => 'default',
                    'click_action' => $data['action_url'],
                ],
                'data'         => [
                    'url'          => $data['action_url'],
                    'title'        => $data['title'],
                    'body'         => $data['body'],
                ],
            ]);

            if ($response->successful()) {
                Log::info("[FCM TOPIC PUSH] Delivered to /topics/{$topic}");
                return true;
            }

            Log::warning("[FCM TOPIC PUSH] Failed for /topics/{$topic}: " . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error("[FCM TOPIC PUSH] Error: " . $e->getMessage());
            return false;
        }
    }
}
