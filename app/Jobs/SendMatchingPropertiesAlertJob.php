<?php

namespace App\Jobs;

use App\Models\Lead;
use App\Services\WhatsApp\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendMatchingPropertiesAlertJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Lead $lead,
        public array $propertyIds
    ) {}

    public function handle(WhatsAppService $service): void
    {
        $service->sendPropertyAlerts($this->lead, $this->propertyIds);
    }
}
