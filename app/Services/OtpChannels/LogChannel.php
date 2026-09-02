<?php

namespace App\Services\OtpChannels;

use Illuminate\Support\Facades\Log;

class LogChannel
{
    /**
     * Send OTP by logging it (development / testing only).
     */
    public function send(string $phone, string $otp): bool
    {
        Log::channel('single')->info("📱 [OTP] Phone: {$phone} | OTP: {$otp} | Expires in " . config('otp.expiry_minutes') . " minutes");

        return true;
    }
}
