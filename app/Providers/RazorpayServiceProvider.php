<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Fixes cURL SSL connection timeouts when calling Razorpay API on Windows/XAMPP.
 *
 * Root cause: The Razorpay PHP SDK forces TLS 1.1 (CURL_SSLVERSION_TLSv1_1)
 * and the rmccue/requests library can attempt IPv6 connections that time out
 * on systems without proper IPv6 support. This provider patches both issues
 * by hooking into the WpOrg\Requests lifecycle.
 */
class RazorpayServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Register a global filter on WpOrg\Requests to fix curl options
        // before every outgoing request made by the Razorpay SDK.
        \WpOrg\Requests\Requests::set_defaults([
            'timeout' => 30,
        ]);
    }

    public function register(): void
    {
        //
    }
}
