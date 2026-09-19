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
