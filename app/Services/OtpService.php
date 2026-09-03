<?php

namespace App\Services;

use App\Models\OtpVerification;
use App\Models\Setting;
use App\Services\OtpChannels\LogChannel;
use App\Services\OtpChannels\WhatsAppChannel;
use App\Services\OtpChannels\SmsChannel;
use App\Services\OtpChannels\PushNotificationChannel;
use Illuminate\Support\Facades\Log;

class OtpService
{
    /**
     * Generate and send an OTP to a phone number.
     *
     * @return array{success: bool, message: string, expires_in?: int, resend_after?: int}
     */
    public function sendOtp(string $phone, string $purpose = 'register'): array
    {
        $phone = $this->normalizePhone($phone);
        $resendSeconds = (int) Setting::get('otp_resend_seconds', config('otp.resend_seconds', 60));

        // Rate limiting: check if a recent OTP was sent (within resend window)
        $recent = OtpVerification::forPhone($phone)
            ->forPurpose($purpose)
            ->pending()
            ->where('created_at', '>', now()->subSeconds($resendSeconds))
            ->latest()
            ->first();

        if ($recent) {
            $waitSeconds = max(1, (int) ceil($resendSeconds - abs(now()->diffInSeconds($recent->created_at))));
            $channel = Setting::get('otp_channel', config('otp.channel', 'log'));
            
            $res = [
                'success'      => false,
                'message'      => "An active OTP code was already sent. Please enter it below or wait {$waitSeconds}s to request a new code.",
                'resend_after' => $waitSeconds,
                'existing_otp' => true,
            ];

            if ($channel === 'notification' || $channel === 'log') {
                $res['notification'] = [
                    'title' => 'UnlockRentals Security Code',
                    'body'  => "{$recent->otp} is your UnlockRentals verification code.",
                    'otp'   => $recent->otp,
                    'icon'  => '/favicon.ico',
                ];
            }

            return $res;
        }

        // Rate limiting: max OTPs per phone per hour (configurable, default 15)
        $maxPerHour = (int) Setting::get('otp_max_per_hour', config('otp.max_per_hour', 15));
        $recentCount = OtpVerification::forPhone($phone)
            ->where('created_at', '>', now()->subHour())
            ->count();

        if ($recentCount >= $maxPerHour) {
            return [
                'success' => false,
                'message' => 'Too many OTP requests for this phone number. Please try again after a few minutes.',
            ];
        }

        // Invalidate previous pending OTPs for the same phone+purpose
        OtpVerification::forPhone($phone)
            ->forPurpose($purpose)
            ->pending()
            ->update(['expires_at' => now()]);

        // Generate OTP
        $otp = $this->generateCode();
        $expiryMinutes = (int) Setting::get('otp_expiry_minutes', config('otp.expiry_minutes', 10));
        $resendSeconds = (int) Setting::get('otp_resend_seconds', config('otp.resend_seconds', 60));

        $record = OtpVerification::create([
            'phone'      => $phone,
            'otp'        => $otp,
            'purpose'    => $purpose,
            'expires_at' => now()->addMinutes($expiryMinutes),
            'attempts'   => 0,
        ]);

        // Send via configured channel
        $sent = $this->dispatchOtp($phone, $otp);

        if (!$sent) {
            return [
                'success' => false,
                'message' => 'Failed to send OTP. Please try again.',
            ];
        }

        $channel = Setting::get('otp_channel', config('otp.channel', 'log'));
        $response = [
            'success'      => true,
            'message'      => $this->getSuccessMessage(),
            'channel'      => $channel,
            'expires_in'   => $expiryMinutes * 60,
            'resend_after' => $resendSeconds,
        ];

        // Attach notification payload for browser push notification / mobile in-app notification
        if ($channel === 'notification' || $channel === 'log') {
            $response['notification'] = [
                'title' => 'UnlockRentals Security Code',
                'body'  => "{$otp} is your UnlockRentals verification code. Valid for {$expiryMinutes} minutes. Never share this code.",
                'otp'   => $otp,
                'icon'  => '/favicon.ico',
            ];
        }

        return $response;
    }

    /**
     * Verify an OTP.
     *
     * @return array{success: bool, verified: bool, message: string}
     */
    public function verifyOtp(string $phone, string $otp, string $purpose = 'register'): array
    {
        $phone = $this->normalizePhone($phone);
        $maxAttempts = (int) Setting::get('otp_max_attempts', config('otp.max_attempts', 3));

        $record = OtpVerification::forPhone($phone)
            ->forPurpose($purpose)
            ->pending()
            ->latest('created_at')
            ->first();

        if (!$record) {
            return [
                'success'  => false,
                'verified' => false,
                'message'  => 'No pending OTP found. Please request a new one.',
            ];
        }

        if ($record->isExpired()) {
            return [
                'success'  => false,
                'verified' => false,
                'message'  => 'OTP has expired. Please request a new one.',
            ];
        }

        if ($record->hasMaxAttempts()) {
            return [
                'success'  => false,
                'verified' => false,
                'message'  => 'Maximum attempts exceeded. Please request a new OTP.',
            ];
        }

        // Check OTP
        if ($record->otp !== $otp) {
            $record->incrementAttempts();
            $remaining = $maxAttempts - $record->attempts;
            return [
                'success'  => false,
                'verified' => false,
                'message'  => "Invalid OTP. " . max(0, $remaining) . " attempt(s) remaining.",
            ];
        }

        // Success
        $record->markVerified();

        return [
            'success'  => true,
            'verified' => true,
            'message'  => 'Phone number verified successfully!',
        ];
    }

    /**
     * Check if a phone was recently verified for a given purpose.
     */
    public function isPhoneVerified(string $phone, string $purpose = 'register'): bool
    {
        $phone = $this->normalizePhone($phone);

        return OtpVerification::forPhone($phone)
            ->forPurpose($purpose)
            ->whereNotNull('verified_at')
            ->where('verified_at', '>', now()->subMinutes(30)) // Valid for 30 mins
            ->exists();
    }

    /* ── Private Helpers ─────────────────────────── */

    private function generateCode(): string
    {
        $length = (int) Setting::get('otp_length', config('otp.otp_length', 4));
        $min = pow(10, $length - 1);
        $max = pow(10, $length) - 1;
        return (string) random_int($min, $max);
    }

    private function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/[^0-9]/', '', $phone);
        if (strlen($digits) >= 10) {
            return substr($digits, -10);
        }
        return $digits;
    }

    private function dispatchOtp(string $phone, string $otp): bool
    {
        $channel = Setting::get('otp_channel', config('otp.channel', 'log'));

        return match ($channel) {
            'notification' => (new PushNotificationChannel())->send($phone, $otp),
            'whatsapp'     => (new WhatsAppChannel())->send($phone, $otp),
            'sms'          => (new SmsChannel())->send($phone, $otp),
            default        => (new LogChannel())->send($phone, $otp),
        };
    }

    private function getSuccessMessage(): string
    {
        $channel = Setting::get('otp_channel', config('otp.channel', 'log'));

        return match ($channel) {
            'notification' => 'OTP sent via push notification to your device!',
            'whatsapp'     => 'OTP sent to your WhatsApp! Check your messages.',
            'sms'          => 'OTP sent to your phone via SMS.',
            default        => 'OTP generated successfully (check logs in development).',
        };
    }
}
