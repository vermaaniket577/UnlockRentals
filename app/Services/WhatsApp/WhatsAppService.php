<?php

namespace App\Services\WhatsApp;

use App\Models\CommunicationLog;
use App\Models\Lead;
use App\Models\Property;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected WhatsAppServiceInterface $provider;

    public function __construct()
    {
        $driver = Setting::get('whatsapp_provider', env('WHATSAPP_PROVIDER', 'meta'));

        $this->provider = match (strtolower($driver)) {
            'twilio' => new TwilioWhatsAppProvider(),
            'log' => new LogWhatsAppProvider(),
            default => new MetaCloudWhatsAppProvider(),
        };
    }

    /**
     * Send a direct message to a Lead (checks consent & records communication log).
     */
    public function sendToLead(Lead $lead, string $message, ?int $staffUserId = null): array
    {
        // Strictly check consent: Never send if user did not opt in
        if (!$lead->whatsapp_opt_in) {
            Log::warning("[WhatsApp CRM] Blocked message to Lead #{$lead->id} ({$lead->mobile}) - No WhatsApp opt-in consent.");
            return ['success' => false, 'error' => 'User has not opted in for WhatsApp communication.'];
        }

        $phone = $lead->whatsapp_phone;
        $result = $this->provider->sendMessage($phone, $message);

        // Record in communication log
        CommunicationLog::create([
            'lead_id' => $lead->id,
            'user_id' => $staffUserId,
            'type' => 'outgoing',
            'channel' => 'whatsapp',
            'message' => $message,
            'status' => $result['success'] ? 'delivered' : 'failed',
            'provider_message_id' => $result['message_id'] ?? null,
            'metadata' => ['error' => $result['error'] ?? null],
            'sent_at' => now(),
            'delivered_at' => $result['success'] ? now() : null,
        ]);

        return $result;
    }

    /**
     * Send curated matching property recommendations via WhatsApp.
     */
    public function sendPropertyAlerts(Lead $lead, array $propertyIds): array
    {
        if (!$lead->whatsapp_opt_in) {
            return ['success' => false, 'error' => 'User has not opted in.'];
        }

        $properties = Property::approved()->whereIn('id', $propertyIds)->take(3)->get();
        if ($properties->isEmpty()) {
            return ['success' => false, 'error' => 'No properties found.'];
        }

        $message = "🏠 *New Verified Matches on UnlockRentals* for {$lead->name} ({$lead->preferred_city}):\n\n";

        foreach ($properties as $idx => $prop) {
            $num = $idx + 1;
            $price = number_format($prop->price, 0);
            $url = route('properties.show', $prop);
            $message .= "{$num}. *{$prop->title}*\n";
            $message .= "   📍 {$prop->locality}, {$prop->location}\n";
            $message .= "   💰 ₹{$price} / {$prop->price_period}\n";
            $message .= "   🔗 View: {$url}\n\n";
        }

        $message .= "Reply *STOP* at any time to opt out of WhatsApp alerts.";

        return $this->sendToLead($lead, $message);
    }
}
