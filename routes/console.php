<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Visitor CRM Scheduled Tasks
Schedule::command('visitor:aggregate-daily')->dailyAt('00:05')->name('visitor_aggregate_daily')->withoutOverlapping();
Schedule::command('visitor:cleanup-retention')->dailyAt('02:00')->name('visitor_cleanup_retention')->withoutOverlapping();

// Fetch and sync leads from external API
Artisan::command('leads:fetch-external {--url=} {--limit=0}', function () {
    $url = $this->option('url') ?: env('EXTERNAL_LEAD_FETCH_API_URL', 'https://api.anushram.com/v1/api/general-enquiry/all');
    $limit = (int) $this->option('limit');
    $this->info("Fetching leads from external API: {$url}");

    $request = new \Illuminate\Http\Request(['source_url' => $url, 'limit' => $limit]);
    $controller = app(\App\Http\Controllers\ExternalLeadApiController::class);
    $response = $controller->fetch($request);
    $data = $response->getData(true);

    if ($data['success'] ?? false) {
        $this->info($data['message']);
        $this->table(['Total Fetched', 'New Saved', 'Existing Updated', 'Skipped'], [
            [$data['total_fetched'] ?? 0, $data['new_leads_saved'] ?? 0, $data['existing_leads_updated'] ?? 0, $data['skipped'] ?? 0]
        ]);
    } else {
        $this->error($data['message'] ?? 'Fetch failed');
    }
})->purpose('Fetch and sync leads from external API into UnlockRentals database');
