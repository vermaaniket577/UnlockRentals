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

// Fetch and sync leads from external API via CLI or Scheduler
Artisan::command('leads:fetch-external {--url=} {--token=}', function () {
    $url = $this->option('url') ?: env('EXTERNAL_FETCH_LEAD_API_URL', 'https://api.anushram.com/v1/api/general-enquiry');
    $token = $this->option('token');
    $this->info("Fetching leads from external API: {$url}");

    $request = new \Illuminate\Http\Request(['source_url' => $url, 'auth_token' => $token]);
    $controller = app(\App\Http\Controllers\ExternalLeadApiController::class);
    $response = $controller->fetchAndStore($request);
    $data = $response->getData(true);

    if ($data['success'] ?? false) {
        $this->info($data['message']);
        $this->table(['Total Received', 'Newly Imported', 'Existing Updated', 'Skipped'], [
            [$data['total_received'] ?? 0, $data['imported_count'] ?? 0, $data['updated_count'] ?? 0, $data['skipped_count'] ?? 0]
        ]);
    } else {
        $this->error($data['message'] ?? 'Fetch failed');
    }
})->purpose('Fetch and store leads from external API into UnlockRentals database');
