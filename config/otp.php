<?php

return [

    /*
    |--------------------------------------------------------------------------
    | OTP Channel
    |--------------------------------------------------------------------------
    | The delivery channel for sending OTPs.
    | Supported: "log", "whatsapp", "sms", "notification"
    | "notification" is for Push Notification (Web & Mobile push)
    | "log" is for development — OTPs are written to storage/logs/laravel.log
    */
    'channel' => env('OTP_CHANNEL', 'log'),

    /*
    |--------------------------------------------------------------------------
    | OTP Settings
    |--------------------------------------------------------------------------
    |
    */
    'expiry_minutes' => (int) env('OTP_EXPIRY_MINUTES', 10),
    'max_attempts'   => (int) env('OTP_MAX_ATTEMPTS', 3),
    'resend_seconds' => (int) env('OTP_RESEND_SECONDS', 60),
    'max_per_hour'   => (int) env('OTP_MAX_PER_HOUR', 15),
    'otp_length'     => (int) env('OTP_LENGTH', 4),

    /*
    |--------------------------------------------------------------------------
    | WhatsApp Cloud API (Meta)
    |--------------------------------------------------------------------------
    */
    'whatsapp' => [
        'token'            => env('WHATSAPP_TOKEN', ''),
        'phone_number_id'  => env('WHATSAPP_PHONE_NUMBER_ID', ''),
        'template_name'    => env('WHATSAPP_OTP_TEMPLATE_NAME', 'otp_verification'),
        'api_version'      => 'v18.0',
    ],

    /*
    |--------------------------------------------------------------------------
    | SMS Provider
    |--------------------------------------------------------------------------
    */
    'sms' => [
        'provider' => env('SMS_PROVIDER', '2factor'),
        'api_key'  => env('SMS_API_KEY', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | Push Notification / Firebase Cloud Messaging (FCM)
    |--------------------------------------------------------------------------
    */
    'fcm' => [
        'server_key' => env('FCM_SERVER_KEY', ''),
        'project_id' => env('FCM_PROJECT_ID', ''),
    ],

];
