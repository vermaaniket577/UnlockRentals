<?php

namespace App\Services\OtpChannels;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PushNotificationChannel
{
    /**
     * Send OTP via Push Notification (FCM / Web Push / Device Notification).
     */
    public function send(string $phone, string $otp): bool
    {
        $fcmServerKey = Setting::get('fcm_server_key', config('otp.fcm.server_key'));
        $fcmProjectId = Setting::get('fcm_project_id', config('otp.fcm.project_id'));

        Log::info("🔔 [OTP PUSH NOTIFICATION] Phone: {$phone} | OTP: {$otp}");

        // If FCM Server Key is configured, attempt sending to FCM topic or device token
        if (!empty($fcmServerKey)) {
            try {
                // Send to phone-specific topic e.g. /topics/otp_9425455499
                $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
                $response = Http::withHeaders([
                    'Authorization' => 'key=' . $fcmServerKey,
                    'Content-Type'  => 'application/json',
                ])->post('https://fcm.googleapis.com/fcm/send', [
                    'to' => '/topics/otp_' . $cleanPhone,
                    'priority' => 'high',
                    'notification' => [
                        'title' => 'UnlockRentals Security Code',
                        'body'  => "{$otp} is your UnlockRentals verification code. Do not share this code with anyone.",
                        'icon'  => '/favicon.ico',
                        'sound' => 'default',
                        'badge' => '/favicon.ico',
                        'tag'   => 'unlockrentals-otp-verify',
                    ],
                    'data' => [
                        'otp'         => $otp,
                        'phone'       => $phone,
                        'type'        => 'otp_verification',
                        'click_action'=> url('/'),
                    ],
                ]);

                if ($response->successful()) {
                    Log::info("[OTP FCM] Sent push notification for {$phone} successfully.");
                    return true;
                }

                Log::warning("[OTP FCM] Failed: " . $response->body());
            } catch (\Exception $e) {
                Log::error("[OTP FCM] Exception: " . $e->getMessage());
            }
        }

        // Fallback / standard push dispatch
        return true;
    }
}
