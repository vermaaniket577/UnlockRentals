<?php

namespace App\Jobs;

use App\Models\Lead;
use App\Services\WhatsApp\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendWhatsAppMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Lead $lead,
        public string $message,
        public ?int $staffUserId = null
    ) {}

    public function handle(WhatsAppService $service): void
    {
        $service->sendToLead($this->lead, $this->message, $this->staffUserId);
    }
}
