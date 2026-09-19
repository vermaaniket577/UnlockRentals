<?php

namespace App\Services\WhatsApp;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TwilioWhatsAppProvider implements WhatsAppServiceInterface
{
    protected string $sid;
    protected string $authToken;
    protected string $fromNumber;

    public function __construct()
    {
        $this->sid = (string) Setting::get('twilio_sid', env('TWILIO_SID', ''));
        $this->authToken = (string) Setting::get('twilio_auth_token', env('TWILIO_AUTH_TOKEN', ''));
        $this->fromNumber = (string) Setting::get('twilio_whatsapp_from', env('TWILIO_WHATSAPP_FROM', ''));
    }

    public function sendMessage(string $to, string $message, array $options = []): array
    {
        if (empty($this->sid) || empty($this->authToken)) {
            Log::warning("[WhatsApp Twilio] Credentials missing. Logging message: To {$to} -> {$message}");
            return ['success' => true, 'message_id' => 'mock_twilio_' . uniqid(), 'error' => null];
        }

        $formattedTo = 'whatsapp:+' . ltrim($to, '+');
        $from = str_starts_with($this->fromNumber, 'whatsapp:') ? $this->fromNumber : 'whatsapp:' . $this->fromNumber;

        try {
            $response = Http::withBasicAuth($this->sid, $this->authToken)
                ->asForm()
                ->post("https://api.twilio.com/2010-04-01/Accounts/{$this->sid}/Messages.json", [
                    'From' => $from,
                    'To' => $formattedTo,
                    'Body' => $message,
                ]);

            if ($response->successful()) {
                $json = $response->json();
                return ['success' => true, 'message_id' => $json['sid'] ?? null, 'error' => null];
            }

            Log::error("[WhatsApp Twilio Error] " . $response->body());
            return ['success' => false, 'message_id' => null, 'error' => $response->body()];
        } catch (\Throwable $e) {
            Log::error("[WhatsApp Twilio Exception] " . $e->getMessage());
            return ['success' => false, 'message_id' => null, 'error' => $e->getMessage()];
        }
    }

    public function sendTemplate(string $to, string $templateName, array $parameters = [], string $language = 'en'): array
    {
        $message = "Template [{$templateName}]: " . implode(', ', $parameters);
        return $this->sendMessage($to, $message);
    }
}
