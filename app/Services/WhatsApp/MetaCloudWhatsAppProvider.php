<?php

namespace App\Services\WhatsApp;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MetaCloudWhatsAppProvider implements WhatsAppServiceInterface
{
    protected string $token;
    protected string $phoneNumberId;
    protected string $apiVersion;

    public function __construct()
    {
        $this->token = (string) Setting::get('whatsapp_token', config('otp.whatsapp.token', env('WHATSAPP_ACCESS_TOKEN', '')));
        $this->phoneNumberId = (string) Setting::get('whatsapp_phone_number_id', config('otp.whatsapp.phone_number_id', env('WHATSAPP_PHONE_NUMBER_ID', '')));
        $this->apiVersion = config('otp.whatsapp.api_version', 'v18.0');
    }

    public function sendMessage(string $to, string $message, array $options = []): array
    {
        if (empty($this->token) || empty($this->phoneNumberId)) {
            Log::warning("[WhatsApp Meta] Credentials missing. Logging message: To {$to} -> {$message}");
            return ['success' => true, 'message_id' => 'mock_' . uniqid(), 'error' => null];
        }

        $formattedPhone = $this->formatPhone($to);

        try {
            $response = Http::withToken($this->token)
                ->post("https://graph.facebook.com/{$this->apiVersion}/{$this->phoneNumberId}/messages", [
                    'messaging_product' => 'whatsapp',
                    'to' => $formattedPhone,
                    'type' => 'text',
                    'text' => [
                        'preview_url' => true,
                        'body' => $message,
                    ],
                ]);

            if ($response->successful()) {
                $json = $response->json();
                $messageId = $json['messages'][0]['id'] ?? null;
                return ['success' => true, 'message_id' => $messageId, 'error' => null];
            }

            Log::error("[WhatsApp Meta Error] " . $response->body());
            return ['success' => false, 'message_id' => null, 'error' => $response->body()];
        } catch (\Throwable $e) {
            Log::error("[WhatsApp Meta Exception] " . $e->getMessage());
            return ['success' => false, 'message_id' => null, 'error' => $e->getMessage()];
        }
    }

    public function sendTemplate(string $to, string $templateName, array $parameters = [], string $language = 'en'): array
    {
        if (empty($this->token) || empty($this->phoneNumberId)) {
            Log::warning("[WhatsApp Meta] Credentials missing for template. Logging: To {$to}, Template: {$templateName}");
            return ['success' => true, 'message_id' => 'mock_' . uniqid(), 'error' => null];
        }

        $formattedPhone = $this->formatPhone($to);
        $components = [];

        if (!empty($parameters)) {
            $paramFormatted = [];
            foreach ($parameters as $param) {
                $paramFormatted[] = ['type' => 'text', 'text' => (string) $param];
            }
            $components[] = [
                'type' => 'body',
                'parameters' => $paramFormatted,
            ];
        }

        try {
            $response = Http::withToken($this->token)
                ->post("https://graph.facebook.com/{$this->apiVersion}/{$this->phoneNumberId}/messages", [
                    'messaging_product' => 'whatsapp',
                    'to' => $formattedPhone,
                    'type' => 'template',
                    'template' => [
                        'name' => $templateName,
                        'language' => ['code' => $language],
                        'components' => $components,
                    ],
                ]);

            if ($response->successful()) {
                $json = $response->json();
                $messageId = $json['messages'][0]['id'] ?? null;
                return ['success' => true, 'message_id' => $messageId, 'error' => null];
            }

            Log::error("[WhatsApp Meta Template Error] " . $response->body());
            return ['success' => false, 'message_id' => null, 'error' => $response->body()];
        } catch (\Throwable $e) {
            Log::error("[WhatsApp Meta Template Exception] " . $e->getMessage());
            return ['success' => false, 'message_id' => null, 'error' => $e->getMessage()];
        }
    }

    protected function formatPhone(string $phone): string
    {
        $digits = preg_replace('/[^0-9]/', '', $phone);
        if (strlen($digits) === 10) {
            return '91' . $digits;
        }
        return $digits;
    }
}
