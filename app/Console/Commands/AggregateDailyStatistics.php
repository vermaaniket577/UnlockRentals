<?php

namespace App\Console\Commands;

use App\Models\Lead;
use App\Models\Visitor;
use App\Models\VisitorDailyStatistic;
use App\Models\VisitorEvent;
use App\Models\VisitorSession;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AggregateDailyStatistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'visitor:aggregate-daily {--date= : Specific date to aggregate in YYYY-MM-DD format}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Aggregate visitor sessions, events, and conversions into daily statistics for reporting';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dateInput = $this->option('date');
        $targetDate = $dateInput ? Carbon::parse($dateInput) : Carbon::yesterday();
        $dateStr = $targetDate->format('Y-m-d');
        $startOfDay = $targetDate->copy()->startOfDay();
        $endOfDay = $targetDate->copy()->endOfDay();

        $this->info("Aggregating visitor telemetry for date: {$dateStr}");

        // 1. Total & Unique Visitors
        $totalSessions = VisitorSession::whereBetween('started_at', [$startOfDay, $endOfDay])->count();
        $uniqueVisitors = VisitorSession::whereBetween('started_at', [$startOfDay, $endOfDay])
            ->distinct('visitor_id')
            ->count('visitor_id');

        // 2. Events Counts
        $totalPageViews = VisitorEvent::whereBetween('created_at', [$startOfDay, $endOfDay])
            ->where('event_name', 'page_view')
            ->count();

        $totalPropertyViews = VisitorEvent::whereBetween('created_at', [$startOfDay, $endOfDay])
            ->where('event_name', 'view_property')
            ->count();

        $whatsappClicks = VisitorEvent::whereBetween('created_at', [$startOfDay, $endOfDay])
            ->where('event_name', 'whatsapp_click')
            ->count();

        // 3. Leads & Conversions
        $leadsCount = Lead::whereBetween('created_at', [$startOfDay, $endOfDay])->count();
        $conversionsCount = Lead::whereBetween('created_at', [$startOfDay, $endOfDay])
            ->where('lead_status', 'converted')
            ->count();

        // 4. Upsert Summary Record in visitor_daily_statistics
        VisitorDailyStatistic::updateOrCreate(
            [
                'date' => $dateStr,
                'city' => 'All',
                'source' => 'All',
            ],
            [
                'visitors_count' => $uniqueVisitors,
                'sessions_count' => $totalSessions,
                'page_views_count' => $totalPageViews,
                'property_views_count' => $totalPropertyViews,
                'leads_count' => $leadsCount,
                'whatsapp_clicks_count' => $whatsappClicks,
                'enquiries_count' => $leadsCount,
                'scheduled_visits_count' => 0,
                'conversions_count' => $conversionsCount,
            ]
        );

        $this->info("Successfully aggregated statistics for {$dateStr}: {$uniqueVisitors} visitors, {$totalSessions} sessions, {$leadsCount} leads.");
        return self::SUCCESS;
    }
}
