<?php

namespace App\Services\WhatsApp;

use Illuminate\Support\Facades\Log;

class LogWhatsAppProvider implements WhatsAppServiceInterface
{
    public function sendMessage(string $to, string $message, array $options = []): array
    {
        $mockId = 'log_wamid_' . bin2hex(random_bytes(8));
        Log::info("💬 [WhatsApp Log Channel] TO: {$to} | MESSAGE: {$message} | ID: {$mockId}");

        return [
            'success' => true,
            'message_id' => $mockId,
            'error' => null,
        ];
    }

    public function sendTemplate(string $to, string $templateName, array $parameters = [], string $language = 'en'): array
    {
        $mockId = 'log_wamid_tpl_' . bin2hex(random_bytes(8));
        $paramsText = json_encode($parameters);
        Log::info("💬 [WhatsApp Log Channel - Template] TO: {$to} | TEMPLATE: {$templateName} | PARAMS: {$paramsText} | ID: {$mockId}");

        return [
            'success' => true,
            'message_id' => $mockId,
            'error' => null,
        ];
    }
}
