<?php

namespace App\Services\OtpChannels;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsChannel
{
    /**
     * Send OTP via SMS provider (2Factor.in or MSG91).
     */
    public function send(string $phone, string $otp): bool
    {
        $provider = Setting::get('sms_provider', config('otp.sms.provider', '2factor'));
        $apiKey   = Setting::get('sms_api_key', config('otp.sms.api_key'));

        if (empty($apiKey)) {
            Log::warning("[OTP SMS] API key not configured. Falling back to log.");
            Log::info("📱 [OTP FALLBACK] Phone: {$phone} | OTP: {$otp}");
            return true;
        }

        $cleanPhone = $this->cleanPhone($phone);

        try {
            if ($provider === '2factor') {
                return $this->sendVia2Factor($cleanPhone, $otp, $apiKey);
            }

            if ($provider === 'msg91') {
                return $this->sendViaMsg91($cleanPhone, $otp, $apiKey);
            }

            Log::error("[OTP SMS] Unknown provider: {$provider}");
            return false;

        } catch (\Exception $e) {
            Log::error("[OTP SMS] Exception: " . $e->getMessage());
            return false;
        }
    }

    private function sendVia2Factor(string $phone, string $otp, string $apiKey): bool
    {
        $response = Http::get("https://2factor.in/API/V1/{$apiKey}/SMS/{$phone}/{$otp}/OTP1");

        if ($response->successful() && ($response->json('Status') === 'Success')) {
            Log::info("[OTP SMS/2Factor] Sent to {$phone}");
            return true;
        }

        Log::error("[OTP SMS/2Factor] Failed: " . $response->body());
        return false;
    }

    private function sendViaMsg91(string $phone, string $otp, string $apiKey): bool
    {
        $response = Http::withHeaders(['authkey' => $apiKey])
            ->post('https://control.msg91.com/api/v5/otp', [
                'mobile'    => '91' . $phone,
                'otp'       => $otp,
                'otp_length'=> config('otp.otp_length', 6),
            ]);

        if ($response->successful()) {
            Log::info("[OTP SMS/MSG91] Sent to {$phone}");
            return true;
        }

        Log::error("[OTP SMS/MSG91] Failed: " . $response->body());
        return false;
    }

    private function cleanPhone(string $phone): string
    {
        $digits = preg_replace('/[^0-9]/', '', $phone);
        return strlen($digits) >= 10 ? substr($digits, -10) : $digits;
    }
}
