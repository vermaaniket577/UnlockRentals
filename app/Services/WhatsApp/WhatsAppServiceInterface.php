<?php

namespace App\Services\WhatsApp;

interface WhatsAppServiceInterface
{
    /**
     * Send a direct text message or template message.
     *
     * @param string $to Phone number with country code (e.g. 919876543210)
     * @param string $message Text message or template parameter
     * @param array $options Additional provider options (template_name, components, etc.)
     * @return array ['success' => bool, 'message_id' => ?string, 'error' => ?string]
     */
    public function sendMessage(string $to, string $message, array $options = []): array;

    /**
     * Send an approved template message.
     */
    public function sendTemplate(string $to, string $templateName, array $parameters = [], string $language = 'en'): array;
}
