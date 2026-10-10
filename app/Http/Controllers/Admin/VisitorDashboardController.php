<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Visitor;
use App\Models\VisitorSession;
use App\Models\VisitorEvent;
use App\Models\Lead;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisitorDashboardController extends Controller
{
    /**
     * Display the visitor analytics dashboard.
     */
    public function index(Request $request)
    {
        $days = (int) $request->get('range', 30);
        if (!in_array($days, [1, 7, 30, 90])) {
            $days = 30;
        }

        $startDate = Carbon::now()->subDays($days - 1)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        // 1. High Level KPI Metrics
        $totalVisitors = Visitor::whereBetween('created_at', [$startDate, $endDate])->count();
        $totalSessions = VisitorSession::whereBetween('started_at', [$startDate, $endDate])->count();
        $totalLeads = Lead::whereBetween('created_at', [$startDate, $endDate])->count();
        $conversionRate = $totalVisitors > 0 ? round(($totalLeads / $totalVisitors) * 100, 2) : 0;

        // Average duration of sessions with end time
        $avgDurationSeconds = (int) VisitorSession::whereBetween('started_at', [$startDate, $endDate])
            ->whereNotNull('ended_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(SECOND, started_at, ended_at)) as avg_dur')
            ->value('avg_dur');

        // Bounce rate (sessions with only 1 page view or duration < 10 seconds)
        $bounceSessionsCount = VisitorSession::whereBetween('started_at', [$startDate, $endDate])
            ->where(function ($q) {
                $q->where('page_views', '<=', 1)
                  ->orWhereRaw('TIMESTAMPDIFF(SECOND, started_at, ended_at) <= 10');
            })->count();

        $bounceRate = $totalSessions > 0 ? round(($bounceSessionsCount / $totalSessions) * 100, 1) : 0;

        // 2. Daily Trends Chart Data
        $dailyData = VisitorSession::whereBetween('started_at', [$startDate, $endDate])
            ->selectRaw('DATE(started_at) as date, COUNT(*) as sessions, COUNT(DISTINCT visitor_id) as visitors')
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get()
            ->keyBy('date');

        $dailyLeads = Lead::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as leads')
            ->groupBy('date')
            ->pluck('leads', 'date');

        $chartLabels = [];
        $chartVisitors = [];
        $chartSessions = [];
        $chartLeads = [];

        $period = Carbon::now()->subDays($days - 1);
        while ($period <= Carbon::now()) {
            $dateStr = $period->format('Y-m-d');
            $chartLabels[] = $period->format('d M');
            $chartVisitors[] = $dailyData->has($dateStr) ? $dailyData[$dateStr]->visitors : 0;
            $chartSessions[] = $dailyData->has($dateStr) ? $dailyData[$dateStr]->sessions : 0;
            $chartLeads[] = $dailyLeads[$dateStr] ?? 0;
            $period->addDay();
        }

        // 3. Device Breakdown
        $deviceBreakdown = VisitorSession::whereBetween('started_at', [$startDate, $endDate])
            ->selectRaw('COALESCE(device_type, "desktop") as device, COUNT(*) as count')
            ->groupBy('device')
            ->orderByDesc('count')
            ->get();

        // 4. Traffic Sources
        $sourceBreakdown = VisitorSession::whereBetween('started_at', [$startDate, $endDate])
            ->selectRaw('
                CASE 
                    WHEN referrer LIKE "%google%" THEN "Google Search"
                    WHEN referrer LIKE "%facebook%" OR referrer LIKE "%fb%" THEN "Facebook"
                    WHEN referrer LIKE "%instagram%" THEN "Instagram"
                    WHEN referrer LIKE "%indus%" OR referrer LIKE "%indusappstore%" THEN "Indus Appstore"
                    WHEN referrer LIKE "%whatsapp%" THEN "WhatsApp"
                    WHEN referrer IS NULL OR referrer = "" OR referrer LIKE "%unlockrentals%" THEN "Direct"
                    ELSE "Referral / Other"
                END as source_channel,
                COUNT(*) as count
            ')
            ->groupBy('source_channel')
            ->orderByDesc('count')
            ->get();

        // 5. Top Indian Cities & States
        $topCities = Visitor::whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->selectRaw('city, state, COUNT(*) as total_visits')
            ->groupBy('city', 'state')
            ->orderByDesc('total_visits')
            ->limit(8)
            ->get();

        // 6. Top Viewed Properties
        $topProperties = VisitorEvent::whereBetween('visitor_events.created_at', [$startDate, $endDate])
            ->where('event_name', 'view_property')
            ->whereNotNull('property_id')
            ->join('properties', 'visitor_events.property_id', '=', 'properties.id')
            ->selectRaw('properties.id, properties.title, properties.location, properties.price, properties.purpose, COUNT(visitor_events.id) as views_count')
            ->groupBy('properties.id', 'properties.title', 'properties.location', 'properties.price', 'properties.purpose')
            ->orderByDesc('views_count')
            ->limit(6)
            ->get();

        // 7. Top Search Origins (From where users are searching)
        $topSearchOrigins = VisitorEvent::where('event_name', 'search')
            ->whereBetween('visitor_events.created_at', [$startDate, $endDate])
            ->join('visitors', 'visitor_events.visitor_id', '=', 'visitors.id')
            ->whereNotNull('visitors.city')
            ->where('visitors.city', '!=', '')
            ->selectRaw('visitors.city, visitors.state, COUNT(visitor_events.id) as total_searches, COUNT(DISTINCT visitor_events.visitor_id) as unique_searchers')
            ->groupBy('visitors.city', 'visitors.state')
            ->orderByDesc('total_searches')
            ->limit(8)
            ->get();

        // 8. Top Searched Demands & Targets (What locations/keywords users search for)
        $searchEventsForTargets = VisitorEvent::where('event_name', 'search')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest('created_at')
            ->take(300)
            ->get();

        $targetCounts = [];
        foreach ($searchEventsForTargets as $se) {
            $meta = $se->metadata ?? [];
            $target = null;
            if (!empty($meta['locality']) && !empty($meta['district'])) {
                $target = ucwords(str_replace('-', ' ', $meta['locality'])) . ', ' . ucwords(str_replace('-', ' ', $meta['district']));
            } elseif (!empty($meta['locality'])) {
                $target = ucwords(str_replace('-', ' ', $meta['locality']));
            } elseif (!empty($meta['district'])) {
                $target = ucwords(str_replace('-', ' ', $meta['district']));
            } elseif (!empty($meta['search'])) {
                $target = '"' . \Illuminate\Support\Str::limit($meta['search'], 25) . '"';
            } elseif (!empty($meta['near_me'])) {
                $target = 'Near Me (GPS)';
            }

            if ($target) {
                $targetCounts[$target] = ($targetCounts[$target] ?? 0) + 1;
            }
        }
        arsort($targetCounts);
        $topSearchTargets = array_slice($targetCounts, 0, 8, true);

        // 9. Live User Search Activity Feed with Filters
        $searchFeedQuery = VisitorEvent::with(['visitor', 'session'])
            ->where('event_name', 'search')
            ->latest('created_at');

        if ($request->filled('search_origin')) {
            $so = trim($request->search_origin);
            $searchFeedQuery->whereHas('visitor', function ($vq) use ($so) {
                $vq->where('city', 'like', "%{$so}%")
                   ->orWhere('state', 'like', "%{$so}%");
            });
        }

        $recentSearches = $searchFeedQuery->paginate(15, ['*'], 'searches_page')->withQueryString();
        $totalSearches = VisitorEvent::where('event_name', 'search')->whereBetween('created_at', [$startDate, $endDate])->count();

        // 10. Filterable Recent Visitors Table
        $visitorsQuery = Visitor::with(['latestSession', 'lead', 'user'])
            ->latest('last_seen_at');

        if ($request->filled('filter_converted')) {
            if ($request->filter_converted === 'yes') {
                $visitorsQuery->where('has_converted_lead', true);
            } elseif ($request->filter_converted === 'no') {
                $visitorsQuery->where('has_converted_lead', false);
            }
        }

        if ($request->filled('device')) {
            $device = $request->device;
            $visitorsQuery->whereHas('latestSession', function ($q) use ($device) {
                $q->where('device_type', $device);
            });
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $visitorsQuery->where(function ($q) use ($search) {
                $q->where('visitor_uuid', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('state', 'like', "%{$search}%")
                  ->orWhereHas('lead', function ($lq) use ($search) {
                      $lq->where('name', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        $visitors = $visitorsQuery->paginate(20)->withQueryString();

        return view('admin.visitors.index', compact(
            'days',
            'totalVisitors',
            'totalSessions',
            'totalLeads',
            'totalSearches',
            'conversionRate',
            'avgDurationSeconds',
            'bounceRate',
            'chartLabels',
            'chartVisitors',
            'chartSessions',
            'chartLeads',
            'deviceBreakdown',
            'sourceBreakdown',
            'topCities',
            'topProperties',
            'topSearchOrigins',
            'topSearchTargets',
            'recentSearches',
            'visitors'
        ));
    }

    /**
     * Display a specific visitor journey and event stream.
     */
    public function show(Visitor $visitor)
    {
        $visitor->load([
            'sessions' => function ($q) {
                $q->latest('started_at')->take(20);
            },
            'events' => function ($q) {
                $q->with('property')->latest('created_at')->take(100);
            },
            'lead.assignedTo',
            'lead.followUps',
            'lead.communicationLogs',
            'user'
        ]);

        $sessionsCount = $visitor->sessions()->count();
        $eventsCount = $visitor->events()->count();
        $totalPageViews = $visitor->events()->where('event_name', 'page_view')->count();
        $propertyViewsCount = $visitor->events()->where('event_name', 'view_property')->count();
        $whatsappClicksCount = $visitor->events()->where('event_name', 'whatsapp_click')->count();

        // Distinct properties viewed
        $viewedProperties = $visitor->events()
            ->where('event_name', 'view_property')
            ->whereNotNull('property_id')
            ->with('property.primaryImage')
            ->get()
            ->pluck('property')
            ->filter()
            ->unique('id');

        return view('admin.visitors.show', compact(
            'visitor',
            'sessionsCount',
            'eventsCount',
            'totalPageViews',
            'propertyViewsCount',
            'whatsappClicksCount',
            'viewedProperties'
        ));
    }
}
