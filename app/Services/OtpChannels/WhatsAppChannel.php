<?php

namespace App\Services\OtpChannels;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppChannel
{
    /**
     * Send OTP via Meta WhatsApp Cloud API.
     */
    public function send(string $phone, string $otp): bool
    {
        $token         = Setting::get('whatsapp_token', config('otp.whatsapp.token'));
        $phoneNumberId = Setting::get('whatsapp_phone_number_id', config('otp.whatsapp.phone_number_id'));
        $templateName  = Setting::get('whatsapp_otp_template_name', config('otp.whatsapp.template_name', 'otp_verification'));
        $apiVersion    = config('otp.whatsapp.api_version', 'v18.0');

        if (empty($token) || empty($phoneNumberId)) {
            Log::warning("[OTP WhatsApp] API credentials not configured. Falling back to log.");
            Log::info("📱 [OTP FALLBACK] Phone: {$phone} | OTP: {$otp}");
            return true;
        }

        // Ensure phone has country code (default India +91)
        $formattedPhone = $this->formatPhone($phone);

        try {
            $response = Http::withToken($token)
                ->post("https://graph.facebook.com/{$apiVersion}/{$phoneNumberId}/messages", [
                    'messaging_product' => 'whatsapp',
                    'to'                => $formattedPhone,
                    'type'              => 'template',
                    'template'          => [
                        'name'     => $templateName,
                        'language' => ['code' => 'en'],
                        'components' => [
                            [
                                'type'       => 'body',
                                'parameters' => [
                                    [
                                        'type' => 'text',
                                        'text' => $otp,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ]);

            if ($response->successful()) {
                Log::info("[OTP WhatsApp] Sent to {$formattedPhone} successfully.");
                return true;
            }

            Log::error("[OTP WhatsApp] Failed: " . $response->body());
            return false;

        } catch (\Exception $e) {
            Log::error("[OTP WhatsApp] Exception: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Format phone to international format (91XXXXXXXXXX).
     */
    private function formatPhone(string $phone): string
    {
        $digits = preg_replace('/[^0-9]/', '', $phone);

        // Already has country code (91 + 10 digits)
        if (strlen($digits) === 12 && str_starts_with($digits, '91')) {
            return $digits;
        }

        // 10-digit Indian number
        if (strlen($digits) === 10) {
            return '91' . $digits;
        }

        return $digits;
    }
}
