<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\WhatsApp\WhatsAppService;
use Illuminate\Http\Request;

class CrmSettingsController extends Controller
{
    /**
     * Display CRM, Tracking, and WhatsApp settings.
     */
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();

        return view('admin.settings.visitor-tracking', compact('settings'));
    }

    /**
     * Save settings to database.
     */
    public function update(Request $request)
    {
        $keys = [
            // WhatsApp Configuration
            'whatsapp_provider',
            'whatsapp_business_phone',
            'whatsapp_meta_phone_id',
            'whatsapp_meta_access_token',
            'whatsapp_meta_business_account_id',
            'whatsapp_webhook_verify_token',
            'whatsapp_twilio_sid',
            'whatsapp_twilio_token',
            'whatsapp_twilio_from',

            // Tracking & Retention Settings
            'tracking_retention_anonymous_days',
            'tracking_retention_leads_days',
            'tracking_anonymize_ip',
            'tracking_filter_bots',
            'tracking_cookie_lifetime_days',
        ];

        foreach ($keys as $key) {
            if ($request->has($key)) {
                $val = $request->input($key);
                Setting::updateOrCreate(['key' => $key], ['value' => $val]);
            }
        }

        // Handle boolean toggles if missing in form post
        $booleanKeys = ['tracking_anonymize_ip', 'tracking_filter_bots'];
        foreach ($booleanKeys as $bKey) {
            Setting::updateOrCreate(
                ['key' => $bKey],
                ['value' => $request->boolean($bKey) ? '1' : '0']
            );
        }

        // Test WhatsApp dispatch if requested
        if ($request->filled('test_phone')) {
            $testPhone = trim($request->test_phone);
            $whatsAppService = app(WhatsAppService::class);
            $testResult = $whatsAppService->sendMessage(
                $testPhone,
                "✅ UnlockRentals WhatsApp Gateway test message sent at " . now()->format('d M Y, h:i A') . ". Your CRM configuration is active!"
            );

            if ($testResult['success']) {
                return back()->with('success', "Settings saved successfully! Test message dispatched via {$testResult['provider']}.");
            } else {
                return back()->with('warning', "Settings saved, but test WhatsApp failed: " . ($testResult['error'] ?? 'Unknown error'));
            }
        }

        return back()->with('success', 'CRM and Tracking settings updated successfully.');
    }
}
