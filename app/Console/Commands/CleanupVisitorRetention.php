<?php

namespace App\Console\Commands;

use App\Models\Setting;
use App\Models\Visitor;
use App\Models\VisitorEvent;
use App\Models\VisitorSession;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CleanupVisitorRetention extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'visitor:cleanup-retention';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Purge expired anonymous visitor sessions and events based on configured DPDP retention policies';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $retentionDays = (int) Setting::where('key', 'tracking_retention_anonymous_days')->value('value') ?: 30;
        $cutoffDate = Carbon::now()->subDays($retentionDays);

        $this->info("Starting DPDP privacy retention cleanup. Retention window: {$retentionDays} days (Older than: {$cutoffDate->format('Y-m-d H:i:s')})");

        // 1. Find unconverted anonymous visitors older than cutoff
        $expiredVisitors = Visitor::where('has_converted_lead', false)
            ->whereNull('user_id')
            ->where('created_at', '<', $cutoffDate)
            ->whereDoesntHave('lead')
            ->get();

        $count = $expiredVisitors->count();

        if ($count === 0) {
            $this->info("No expired anonymous visitor records found.");
            return self::SUCCESS;
        }

        $visitorIds = $expiredVisitors->pluck('id')->toArray();

        // 2. Delete events associated with these visitors
        $deletedEvents = VisitorEvent::whereIn('visitor_id', $visitorIds)->delete();

        // 3. Delete sessions associated with these visitors
        $deletedSessions = VisitorSession::whereIn('visitor_id', $visitorIds)->delete();

        // 4. Delete the visitor records themselves
        $deletedVisitors = Visitor::whereIn('id', $visitorIds)->delete();

        $this->info("Purged {$deletedVisitors} anonymous visitors, {$deletedSessions} sessions, and {$deletedEvents} events successfully.");
        return self::SUCCESS;
    }
}
