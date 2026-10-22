<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\User;
use App\Models\Inquiry;
use App\Models\Setting;
use App\Models\Plan;
use App\Models\UserPlan;
use App\Models\PaymentLog;
use App\Models\Feedback;
use App\Models\ProcessStep;
use App\Models\Blog;
use App\Models\Lead;
use App\Models\LeadFollowUp;
use App\Models\CallbackRequest;
use App\Models\Visitor;
use App\Models\VisitorSession;
use App\Models\VisitorEvent;
use App\Models\Professional;
use App\Models\ProfessionalLead;
use App\Models\ChatbotMessage;
use App\Mail\SubscriptionActivated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * Show the admin dashboard.
     */
    public function dashboard()
    {
        $stats = Cache::remember('admin_dashboard_stats', 120, function () {
            return [
                'total_users'           => User::count(),
                'total_owners'          => User::where('role', 'owner')->count(),
                'total_tenants'         => User::where('role', 'tenant')->count(),
                'total_properties'      => Property::count(),
                'pending_properties'    => Property::pending()->count(),
                'approved_properties'   => Property::approved()->count(),
                'total_inquiries'       => Inquiry::count(),
                'unread_inquiries'      => Inquiry::unread()->count(),
                'total_plans'           => Plan::count(),
                'pending_subscriptions' => UserPlan::pending()->count(),
                'active_subscriptions'  => UserPlan::active()->count(),
                'total_feedback'        => Feedback::count(),
                'new_feedback'          => Feedback::where('status', 'new')->count(),
                'total_blogs'           => \Illuminate\Support\Facades\Schema::hasTable('blogs') ? Blog::count() : 0,
                'published_blogs'       => \Illuminate\Support\Facades\Schema::hasTable('blogs') ? Blog::where('is_published', true)->count() : 0,
            ];
        });

        // Comprehensive Real-time CRM Pipeline & Analytics Summary
        $crmStats = Cache::remember('admin_crm_summary_stats', 60, function () {
            $hasLeads = Schema::hasTable('leads');
            $hasFollowUps = Schema::hasTable('lead_follow_ups');
            $hasCallbacks = Schema::hasTable('callback_requests');
            $hasVisitors = Schema::hasTable('visitors');
            $hasSessions = Schema::hasTable('visitor_sessions');
            $hasProf = Schema::hasTable('professionals');
            $hasProfLeads = Schema::hasTable('professional_leads');
            $hasPlans = Schema::hasTable('user_plans');
            $hasInquiries = Schema::hasTable('inquiries');
            $hasChats = Schema::hasTable('chatbot_messages');

            $totalLeads = $hasLeads ? Lead::count() : 0;
            $newLeads = $hasLeads ? Lead::where('lead_status', 'new')->count() : 0;
            $todayLeads = $hasLeads ? Lead::whereDate('created_at', Carbon::today())->count() : 0;
            $inProgressLeads = $hasLeads ? Lead::whereIn('lead_status', ['contacted', 'interested', 'follow_up', 'visit_scheduled', 'negotiation'])->count() : 0;
            $convertedLeads = $hasLeads ? Lead::where('lead_status', 'converted')->count() : 0;
            $lostLeads = $hasLeads ? Lead::whereIn('lead_status', ['lost', 'invalid'])->count() : 0;
            $whatsappLeads = $hasLeads ? Lead::where('whatsapp_opt_in', true)->count() : 0;

            $leadFunnel = [
                'new' => $newLeads,
                'contacted' => $hasLeads ? Lead::where('lead_status', 'contacted')->count() : 0,
                'interested' => $hasLeads ? Lead::where('lead_status', 'interested')->count() : 0,
                'visit_scheduled' => $hasLeads ? Lead::where('lead_status', 'visit_scheduled')->count() : 0,
                'negotiation' => $hasLeads ? Lead::where('lead_status', 'negotiation')->count() : 0,
                'converted' => $convertedLeads,
                'lost' => $lostLeads,
            ];

            $leadConversionRate = $totalLeads > 0 ? round(($convertedLeads / $totalLeads) * 100, 1) : 0;

            // Follow-ups
            $totalFollowUps = $hasFollowUps ? LeadFollowUp::count() : 0;
            $pendingFollowUps = $hasFollowUps ? LeadFollowUp::where('status', 'pending')->count() : 0;
            $dueTodayFollowUps = $hasFollowUps ? LeadFollowUp::where('status', 'pending')->where('scheduled_at', '<=', Carbon::now()->endOfDay())->count() : 0;
            $overdueFollowUps = $hasFollowUps ? LeadFollowUp::where('status', 'pending')->where('scheduled_at', '<', Carbon::now())->count() : 0;

            // Callbacks
            $totalCallbacks = $hasCallbacks ? CallbackRequest::count() : 0;
            $pendingCallbacks = $hasCallbacks ? CallbackRequest::whereIn('status', ['new', 'pending'])->count() : 0;
            $handledCallbacks = $hasCallbacks ? CallbackRequest::whereIn('status', ['called', 'completed', 'interested'])->count() : 0;

            // Visitors & Traffic CRM
            $totalVisitors = $hasVisitors ? Visitor::count() : 0;
            $todayVisitors = $hasVisitors ? Visitor::whereDate('created_at', Carbon::today())->count() : 0;
            $todaySessions = $hasSessions ? VisitorSession::whereDate('started_at', Carbon::today())->count() : 0;
            $visitorToLeadRate = $totalVisitors > 0 ? round(($totalLeads / $totalVisitors) * 100, 1) : 0;

            // Monetization CRM (Paid Members & Revenue)
            $activePaidMembers = $hasPlans ? UserPlan::active()->count() : 0;
            $pendingPlanApprovals = $hasPlans ? UserPlan::pending()->count() : 0;
            $expiringSoonSubscriptions = $hasPlans ? UserPlan::active()->whereBetween('expires_at', [Carbon::now(), Carbon::now()->addDays(7)])->count() : 0;
            
            $totalRevenue = 0;
            if ($hasPlans) {
                try {
                    $totalRevenue = (float) UserPlan::where('status', 'approved')->sum('final_amount');
                    if ($totalRevenue <= 0) {
                        $totalRevenue = (float) UserPlan::where('status', 'approved')->sum('amount_paid');
                    }
                } catch (\Throwable $e) {
                    $totalRevenue = 0;
                }
            }

            // Local Professionals Marketplace CRM
            $totalProfessionals = $hasProf ? Professional::where('status', 'approved')->count() : 0;
            $pendingProfessionals = $hasProf ? Professional::where('status', 'pending')->count() : 0;
            $totalProfLeads = $hasProfLeads ? ProfessionalLead::count() : 0;

            // Inquiries & Chats
            $totalInquiries = $hasInquiries ? Inquiry::count() : 0;
            $unreadInquiries = $hasInquiries ? Inquiry::unread()->count() : 0;
            $unreadChats = 0;
            if ($hasChats) {
                try {
                    $unreadChats = ChatbotMessage::where('is_read', false)->count();
                } catch (\Throwable $e) {
                    $unreadChats = 0;
                }
            }

            return [
                'total_leads' => $totalLeads,
                'new_leads' => $newLeads,
                'today_leads' => $todayLeads,
                'in_progress_leads' => $inProgressLeads,
                'converted_leads' => $convertedLeads,
                'lost_leads' => $lostLeads,
                'whatsapp_leads' => $whatsappLeads,
                'conversion_rate' => $leadConversionRate,
                'lead_funnel' => $leadFunnel,
                'total_follow_ups' => $totalFollowUps,
                'pending_follow_ups' => $pendingFollowUps,
                'due_today_follow_ups' => $dueTodayFollowUps,
                'overdue_follow_ups' => $overdueFollowUps,
                'total_callbacks' => $totalCallbacks,
                'pending_callbacks' => $pendingCallbacks,
                'handled_callbacks' => $handledCallbacks,
                'total_visitors' => $totalVisitors,
                'today_visitors' => $todayVisitors,
                'today_sessions' => $todaySessions,
                'visitor_to_lead_rate' => $visitorToLeadRate,
                'active_paid_members' => $activePaidMembers,
                'pending_plan_approvals' => $pendingPlanApprovals,
                'expiring_soon_subscriptions' => $expiringSoonSubscriptions,
                'total_revenue' => $totalRevenue,
                'total_professionals' => $totalProfessionals,
                'pending_professionals' => $pendingProfessionals,
                'total_prof_leads' => $totalProfLeads,
                'total_inquiries' => $totalInquiries,
                'unread_inquiries' => $unreadInquiries,
                'unread_chats' => $unreadChats,
            ];
        });

        // Recent high-intent CRM leads
        $recentCrmLeads = collect();
        if (Schema::hasTable('leads')) {
            try {
                $recentCrmLeads = Lead::with(['property', 'assignedTo'])
                    ->latest()
                    ->take(5)
                    ->get();
            } catch (\Throwable $e) {
                $recentCrmLeads = collect();
            }
        }

        // Recent urgent callbacks awaiting action
        $recentCallbacks = collect();
        if (Schema::hasTable('callback_requests')) {
            try {
                $recentCallbacks = CallbackRequest::with(['property', 'user'])
                    ->whereIn('status', ['new', 'pending', 'called', 'interested'])
                    ->latest()
                    ->take(5)
                    ->get();
            } catch (\Throwable $e) {
                $recentCallbacks = collect();
            }
        }

        $pendingProperties = Property::pending()
            ->with(['owner', 'primaryImage', 'category'])
            ->latest()
            ->take(10)
            ->get();

        $pendingSubscriptions = UserPlan::pending()
            ->with(['user', 'plan'])
            ->latest()
            ->take(10)
            ->get();

        // Recent user search activity & origin locations (Where users are searching from)
        $recentSearches = collect();
        $topSearchOrigins = collect();
        $totalSearches = 0;
        if (Schema::hasTable('visitor_events')) {
            try {
                $recentSearches = VisitorEvent::with('visitor')
                    ->where('event_name', 'search')
                    ->latest('created_at')
                    ->take(6)
                    ->get();

                $topSearchOrigins = VisitorEvent::where('event_name', 'search')
                    ->where('visitor_events.created_at', '>=', now()->subDays(30))
                    ->join('visitors', 'visitor_events.visitor_id', '=', 'visitors.id')
                    ->whereNotNull('visitors.city')
                    ->where('visitors.city', '!=', '')
                    ->selectRaw('visitors.city, visitors.state, COUNT(visitor_events.id) as total_searches')
                    ->groupBy('visitors.city', 'visitors.state')
                    ->orderByDesc('total_searches')
                    ->take(6)
                    ->get();

                $totalSearches = VisitorEvent::where('event_name', 'search')->count();
            } catch (\Throwable $e) {
                $recentSearches = collect();
                $topSearchOrigins = collect();
                $totalSearches = 0;
            }
        }

        // Recent registered users
        $recentUsers = User::withCount(['properties', 'inquiries'])
            ->latest()
            ->take(6)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'crmStats',
            'recentCrmLeads',
            'recentCallbacks',
            'recentSearches',
            'topSearchOrigins',
            'totalSearches',
            'pendingProperties',
            'pendingSubscriptions',
            'recentUsers'
        ));
    }

    /**
     * Show all properties for admin review.
     */
    public function properties(Request $request)
    {
        $query = Property::with(['owner', 'primaryImage', 'category'])->withCount('images');

        // Filter by Status (pending, approved, rejected)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by Search Keyword (Title, Locality, Location, ID)
        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                if (is_numeric($s)) {
                    $q->orWhere('id', (int)$s);
                }
                $q->orWhere('title', 'like', "%{$s}%")
                  ->orWhere('location', 'like', "%{$s}%")
                  ->orWhere('locality', 'like', "%{$s}%")
                  ->orWhere('address', 'like', "%{$s}%");
            });
        }

        // Filter by City / Location
        if ($request->filled('location')) {
            $query->where('location', $request->location);
        }

        // Filter by Purpose (rent vs buy/sell)
        if ($request->filled('purpose')) {
            $p = $request->purpose;
            if ($p === 'buy' || $p === 'sell') {
                $query->whereIn('purpose', ['buy', 'sell']);
            } else {
                $query->where('purpose', $p);
            }
        }

        // Filter by Booked status
        if ($request->has('is_booked') && $request->is_booked !== '' && $request->is_booked !== null) {
            $query->where('is_booked', (bool)$request->is_booked);
        }

        // Filter by Type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by Date of Posting
        if ($request->filled('date_filter')) {
            switch ($request->date_filter) {
                case 'today':
                    $query->whereDate('created_at', Carbon::today());
                    break;
                case 'yesterday':
                    $query->whereDate('created_at', Carbon::yesterday());
                    break;
                case 'this_week':
                    $query->where('created_at', '>=', Carbon::now()->startOfWeek());
                    break;
                case 'this_month':
                    $query->where('created_at', '>=', Carbon::now()->startOfMonth());
                    break;
                case 'custom':
                    if ($request->filled('date_from')) {
                        $query->whereDate('created_at', '>=', $request->date_from);
                    }
                    if ($request->filled('date_to')) {
                        $query->whereDate('created_at', '<=', $request->date_to);
                    }
                    break;
            }
        } elseif ($request->filled('date_from') || $request->filled('date_to')) {
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }
        }

        // Sort by option
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->oldest('created_at');
                break;
            case 'price_high':
                $query->orderByDesc('price');
                break;
            case 'price_low':
                $query->orderBy('price');
                break;
            case 'latest':
            default:
                $query->latest('created_at');
                break;
        }

        $properties = $query->paginate(15)->withQueryString();

        // Get unique cities/locations for filter dropdown
        $availableLocations = Property::whereNotNull('location')
            ->where('location', '!=', '')
            ->distinct()
            ->orderBy('location')
            ->pluck('location');

        $allUsersList = User::orderBy('name')->select('id', 'name', 'phone', 'email', 'role')->get();

        $stats = [
            'total'     => Property::count(),
            'booked'    => Property::where('is_booked', true)->count(),
            'available' => Property::where('is_booked', false)->count(),
            'selling'   => Property::whereIn('purpose', ['buy', 'sell'])->count(),
            'rent'      => Property::where('purpose', 'rent')->count(),
            'pending'   => Property::where('status', 'pending')->count(),
            'approved'  => Property::where('status', 'approved')->count(),
        ];

        return view('admin.properties', compact('properties', 'availableLocations', 'allUsersList', 'stats'));
    }

    /**
     * Approve a property listing.
     */
    public function approve(Property $property)
    {
        $property->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);
        Cache::forget('admin_dashboard_stats');
        Cache::forget('home_featured_properties');
        Cache::forget('home_latest_properties');
        Cache::forget('home_featured_rentals');
        Cache::forget('home_stats');

        return redirect()->back()
            ->with('success', "Property \"{$property->title}\" has been approved.");
    }

    /**
     * Reject a property listing.
     */
    public function reject(Property $property)
    {
        $property->update([
            'status' => 'rejected',
        ]);
        Cache::forget('admin_dashboard_stats');
        Cache::forget('home_featured_properties');
        Cache::forget('home_latest_properties');
        Cache::forget('home_featured_rentals');
        Cache::forget('home_stats');

        return redirect()->back()
            ->with('success', "Property \"{$property->title}\" has been rejected.");
    }

    /**
     * Show all users with search, filters, relation counts, and stats.
     */
    public function users(Request $request)
    {
        $query = User::query();

        // Search by Name, Email, or Phone
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        // Role filter
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Phone Verification Status filter
        if ($request->filled('status')) {
            if ($request->status === 'verified') {
                $query->whereNotNull('phone_verified_at');
            } elseif ($request->status === 'unverified') {
                $query->whereNull('phone_verified_at');
            }
        }

        // Paid Membership filter
        if ($request->filled('membership')) {
            if ($request->membership === 'paid') {
                $query->whereHas('userPlans', function ($q) {
                    $q->active();
                });
            } elseif ($request->membership === 'free') {
                $query->whereDoesntHave('userPlans', function ($q) {
                    $q->active();
                });
            } elseif (is_numeric($request->membership)) {
                $query->whereHas('userPlans', function ($q) use ($request) {
                    $q->active()->where('plan_id', $request->membership);
                });
            }
        }

        // KPI Stats
        $stats = [
            'total'      => User::count(),
            'owners'     => User::where('role', 'owner')->count(),
            'tenants'    => User::where('role', 'tenant')->count(),
            'admins'     => User::where('role', 'admin')->count(),
            'verified'   => User::whereNotNull('phone_verified_at')->count(),
            'unverified' => User::whereNull('phone_verified_at')->count(),
            'paid'       => User::whereHas('userPlans', fn ($q) => $q->active())->count(),
            'free'       => User::whereDoesntHave('userPlans', fn ($q) => $q->active())->count(),
        ];

        $plans = Plan::where('is_active', true)->orderBy('price')->get();
        $allUsersList = User::orderBy('name')->select('id', 'name', 'phone', 'email', 'role')->get();

        $users = $query->withCount(['properties', 'inquiries'])
            ->with(['userPlans' => function ($q) {
                $q->active()->with('plan');
            }])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.users', compact('users', 'stats', 'plans', 'allUsersList'));
    }

    /**
     * Create and store a new user manually from admin panel.
     */
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|string|email|max:255|unique:users,email',
            'phone'          => 'nullable|string|max:20',
            'role'           => 'required|in:tenant,owner,admin',
            'password'       => 'required|string|min:8',
            'phone_verified' => 'nullable',
        ]);

        $hasPhone = !empty(trim($validated['phone'] ?? ''));

        $user = User::create([
            'name'              => $validated['name'],
            'email'             => $validated['email'],
            'phone'             => $hasPhone ? trim($validated['phone']) : null,
            'role'              => $validated['role'],
            'password'          => \Illuminate\Support\Facades\Hash::make($validated['password']),
            'phone_verified_at' => ($hasPhone && $request->boolean('phone_verified', true)) ? now() : null,
        ]);

        \Illuminate\Support\Facades\Cache::forget('admin_dashboard_stats');

        return redirect()->route('admin.users')
            ->with('success', "New user \"{$user->name}\" ({$user->role}) has been created successfully!");
    }

    /**
     * Export filtered users to Excel sheet format (CSV with UTF-8 BOM).
     */
    public function exportUsersExcel(Request $request): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $query = User::query();

        // Search by Name, Email, or Phone
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        // Role filter
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Phone Verification Status filter
        if ($request->filled('status')) {
            if ($request->status === 'verified') {
                $query->whereNotNull('phone_verified_at');
            } elseif ($request->status === 'unverified') {
                $query->whereNull('phone_verified_at');
            }
        }

        // Paid Membership filter
        if ($request->filled('membership')) {
            if ($request->membership === 'paid') {
                $query->whereHas('userPlans', function ($q) {
                    $q->active();
                });
            } elseif ($request->membership === 'free') {
                $query->whereDoesntHave('userPlans', function ($q) {
                    $q->active();
                });
            } elseif (is_numeric($request->membership)) {
                $query->whereHas('userPlans', function ($q) use ($request) {
                    $q->active()->where('plan_id', $request->membership);
                });
            }
        }

        $query->withCount(['properties', 'inquiries'])
              ->with(['userPlans' => function ($q) {
                  $q->active()->with('plan');
              }])
              ->latest();

        $filename = 'unlockrentals_users_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($query) {
            $handle = fopen('php://output', 'w');
            
            // UTF-8 BOM so Excel opens it with proper character encoding
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Excel Column Headers
            fputcsv($handle, [
                'User ID',
                'Full Name',
                'Email Address',
                'Phone Number',
                'Phone Verified',
                'Role',
                'Membership Tier',
                'Plan Name',
                'Contacts Left',
                'Properties Listed',
                'Inquiries Made',
                'Registered Date',
            ]);

            $query->chunk(100, function ($users) use ($handle) {
                foreach ($users as $u) {
                    $activePlan = $u->activePlan();
                    $planTitle = $activePlan?->plan?->name ?? 'Free Tier';
                    $contactsLeft = $activePlan ? $activePlan->remaining_contacts : 0;
                    $phoneStatus = $u->phone_verified_at ? 'Verified' : 'Unverified';

                    fputcsv($handle, [
                        $u->id,
                        $u->name,
                        $u->email,
                        $u->phone ? ("'" . $u->phone) : 'N/A',
                        $phoneStatus,
                        ucfirst($u->role),
                        $activePlan ? 'Paid Member' : 'Free Member',
                        $planTitle,
                        $activePlan ? $contactsLeft : '0',
                        $u->properties_count,
                        $u->inquiries_count,
                        $u->created_at ? $u->created_at->format('Y-m-d H:i:s') : 'N/A',
                    ]);
                }
            });

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Show settings page for site content and social media management.
     */
    public function settings()
    {
        $settings = Cache::get('site_settings', []);
        $paymentGateways = Setting::paymentGateways();
        $activePaymentGatewayId = Setting::get('active_payment_gateway_id', $paymentGateways[0]['id'] ?? null);

        $sliderBlogs = \Illuminate\Support\Facades\Schema::hasTable('blogs')
            ? Blog::published()->latest('published_at')->get()
            : collect();

        return view('admin.settings', compact('settings', 'paymentGateways', 'activePaymentGatewayId', 'sliderBlogs'));
    }

    /**
     * Update settings.
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'gst_rate' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        $data = $request->except('_token', 'payment_gateways', 'active_payment_gateway_id');
        
        // Handle checkboxes (if they aren't in request, they should be '0')
        $checkboxes = ['chatbot_enabled', 'feedback_enabled', 'bypass_property_approval', 'otp_mandatory_register', 'otp_phone_login_enabled', 'home_blog_slider_enabled'];
        foreach($checkboxes as $box) {
            if(!$request->has($box)) $data[$box] = '0';
        }

        // Handle homepage slider blog IDs array
        if ($request->has('home_blog_slider_ids')) {
            $sliderIds = array_map('intval', (array) $request->input('home_blog_slider_ids', []));
            $data['home_blog_slider_ids'] = json_encode($sliderIds);

            if (\Illuminate\Support\Facades\Schema::hasTable('blogs') && \Illuminate\Support\Facades\Schema::hasColumn('blogs', 'show_in_slider')) {
                Blog::whereIn('id', $sliderIds)->update(['show_in_slider' => true]);
                Blog::whereNotIn('id', $sliderIds)->update(['show_in_slider' => false]);
            }
        } elseif ($request->has('home_blog_slider_setting_submitted')) {
            $data['home_blog_slider_ids'] = json_encode([]);
            if (\Illuminate\Support\Facades\Schema::hasTable('blogs') && \Illuminate\Support\Facades\Schema::hasColumn('blogs', 'show_in_slider')) {
                Blog::query()->update(['show_in_slider' => false]);
            }
        }

        $paymentGateways = collect($request->input('payment_gateways', []))
            ->filter(fn ($gateway) => filled($gateway['name'] ?? null))
            ->map(function ($gateway) {
                $id = $gateway['id'] ?? null;
                $name = trim((string) ($gateway['name'] ?? ''));

                return [
                    'id' => filled($id) ? $id : Str::slug($name) . '-' . Str::random(6),
                    'name' => $name,
                    'type' => in_array(($gateway['type'] ?? 'manual'), ['razorpay', 'manual', 'external'], true)
                        ? $gateway['type']
                        : 'manual',
                    'enabled' => isset($gateway['enabled']) ? '1' : '0',
                    'account_name' => $gateway['account_name'] ?? '',
                    'identifier' => $gateway['identifier'] ?? '',
                    'payment_link' => $gateway['payment_link'] ?? '',
                    'qr_url' => $gateway['qr_url'] ?? '',
                    'reference_label' => $gateway['reference_label'] ?? 'Transaction ID / UTR Number',
                    'instructions' => $gateway['instructions'] ?? '',
                    'key_id' => $gateway['key_id'] ?? '',
                    'key_secret' => $gateway['key_secret'] ?? '',
                ];
            })
            ->values()
            ->all();

        $activePaymentGatewayId = $request->input('active_payment_gateway_id');
        if (!collect($paymentGateways)->contains(fn ($gateway) => $gateway['id'] === $activePaymentGatewayId)) {
            $activePaymentGatewayId = $paymentGateways[0]['id'] ?? null;
        }

        $data['payment_gateways'] = json_encode($paymentGateways);
        $data['active_payment_gateway_id'] = $activePaymentGatewayId;

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        Cache::forget('site_settings');
        Cache::forget('home_slider_blogs');
        Cache::forget('home_blogs');

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }

    /**
     * List all customer feedback.
     */
    public function feedback(Request $request)
    {
        $query = Feedback::with('user');
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $feedbacks = $query->latest()->paginate(15);
        return view('admin.feedback', compact('feedbacks'));
    }

    /**
     * Approve customer feedback.
     */
    public function approveFeedback(\App\Models\Feedback $feedback)
    {
        $feedback->update(['status' => 'approved']);
        \Illuminate\Support\Facades\Cache::forget('home_approved_feedbacks');
        return redirect()->back()->with('success', 'Feedback approved successfully.');
    }

    /**
     * Reject customer feedback.
     */
    public function rejectFeedback(\App\Models\Feedback $feedback)
    {
        $feedback->update(['status' => 'rejected']);
        \Illuminate\Support\Facades\Cache::forget('home_approved_feedbacks');
        return redirect()->back()->with('success', 'Feedback rejected successfully.');
    }

    /**
     * Delete customer feedback.
     */
    public function destroyFeedback(\App\Models\Feedback $feedback)
    {
        $feedback->delete();
        \Illuminate\Support\Facades\Cache::forget('home_approved_feedbacks');
        return redirect()->back()->with('success', 'Feedback deleted successfully.');
    }

    /**
     * List all chatbot conversations.
     */
    public function chats()
    {
        // Mark all unread messages from users as read
        \App\Models\ChatbotMessage::where('is_read', false)->where('sender', 'user')->update(['is_read' => true]);
        Cache::forget('admin_notifications');

        // Limit to latest 500 messages to prevent full-table load
        $chats = \App\Models\ChatbotMessage::with('user')
            ->orderBy('created_at', 'desc')
            ->take(500)
            ->get()
            ->groupBy('session_id');

        return view('admin.chats', compact('chats'));
    }

    /**
     * Send an admin reply to a chatbot session.
     */
    public function replyChat(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string',
            'message' => 'required|string|max:2000',
        ]);

        $firstMsg = \App\Models\ChatbotMessage::where('session_id', $request->session_id)->first();
        $userId = $firstMsg ? $firstMsg->user_id : null;

        $msg = \App\Models\ChatbotMessage::create([
            'user_id' => $userId,
            'session_id' => $request->session_id,
            'message' => trim($request->message),
            'sender' => 'bot',
            'is_read' => true,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => $msg->message,
                'time' => $msg->created_at->format('h:i A'),
            ]);
        }

        return redirect()->back()->with('success', 'Reply sent successfully.');
    }

    /**
     * Ensure callback_requests table has administrative notes and status tracking columns.
     */
    protected function ensureCallbackNotesColumnExists(): void
    {
        try {
            if (Schema::hasTable('callback_requests')) {
                if (!Schema::hasColumn('callback_requests', 'admin_notes')) {
                    Schema::table('callback_requests', function (Blueprint $table) {
                        $table->text('admin_notes')->nullable()->after('status');
                    });
                }
                if (!Schema::hasColumn('callback_requests', 'called_at')) {
                    Schema::table('callback_requests', function (Blueprint $table) {
                        $table->timestamp('called_at')->nullable()->after('admin_notes');
                    });
                }
                if (!Schema::hasColumn('callback_requests', 'called_by')) {
                    Schema::table('callback_requests', function (Blueprint $table) {
                        $table->unsignedBigInteger('called_by')->nullable()->after('called_at');
                    });
                }
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Callback table check warning: ' . $e->getMessage());
        }
    }

    /**
     * List all callback requests.
     */
    public function callbacks(Request $request)
    {
        $this->ensureCallbackNotesColumnExists();

        $query = \App\Models\CallbackRequest::with(['user', 'property', 'caller'])->latest();

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $term = trim($request->search);
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('phone', 'like', "%{$term}%")
                  ->orWhere('email', 'like', "%{$term}%")
                  ->orWhere('admin_notes', 'like', "%{$term}%");
            });
        }

        $callbacks = $query->paginate(20)->withQueryString();
        return view('admin.callbacks', compact('callbacks'));
    }

    /**
     * Update comment/notes and status for a callback request.
     */
    public function updateCallbackComment(Request $request, $id)
    {
        $this->ensureCallbackNotesColumnExists();

        $request->validate([
            'admin_notes' => 'nullable|string|max:3000',
            'status'      => 'nullable|string|in:new,called,no_answer,interested,completed,cancelled',
        ]);

        $callback = \App\Models\CallbackRequest::findOrFail($id);

        $updateData = [];
        if ($request->has('admin_notes')) {
            $updateData['admin_notes'] = $request->input('admin_notes');
        }
        if ($request->filled('status')) {
            $updateData['status'] = $request->input('status');
            if (in_array($request->input('status'), ['called', 'completed', 'interested']) && empty($callback->called_at)) {
                $updateData['called_at'] = now();
                $updateData['called_by'] = auth()->id();
            }
        }

        $callback->update($updateData);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Comment and status updated successfully.',
                'callback' => $callback,
            ]);
        }

        return redirect()->back()->with('success', 'Callback comment and status updated successfully.');
    }

    /**
     * Quick update status for a callback request.
     */
    public function updateCallbackStatus(Request $request, $id)
    {
        $this->ensureCallbackNotesColumnExists();

        $validated = $request->validate([
            'status' => 'required|string|in:new,called,no_answer,interested,completed,cancelled',
        ]);

        $callback = \App\Models\CallbackRequest::findOrFail($id);
        $updateData = ['status' => $validated['status']];

        if (in_array($validated['status'], ['called', 'completed', 'interested']) && empty($callback->called_at)) {
            $updateData['called_at'] = now();
            $updateData['called_by'] = auth()->id();
        }

        $callback->update($updateData);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully.',
            ]);
        }

        return redirect()->back()->with('success', 'Callback status updated to ' . ucfirst($validated['status']) . '.');
    }

    /**
     * Delete a callback request.
     */
    public function destroyCallback(Request $request, $id)
    {
        $callback = \App\Models\CallbackRequest::find($id);
        if ($callback) {
            $callback->delete();
        }

        return redirect()->back()->with('success', 'Callback request record deleted successfully.');
    }

    /**
     * List all password reset tokens.
     */
    public function resets()
    {
        $resets = \Illuminate\Support\Facades\DB::table('password_reset_tokens')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.resets', compact('resets'));
    }

    /**
     * Delete/Invalidate a password reset token.
     */
    public function deleteReset($email)
    {
        \Illuminate\Support\Facades\DB::table('password_reset_tokens')->where('email', $email)->delete();
        Cache::forget('admin_notifications');
        return redirect()->back()->with('success', 'Password reset token invalidated successfully.');
    }

    /**
     * Manually trigger/send password reset email to a user.
     */
    public function sendReset(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'We could not find a user with that email address.');
        }

        $token = \Illuminate\Support\Str::random(64);

        \Illuminate\Support\Facades\DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $token, 'created_at' => \Carbon\Carbon::now()]
        );

        \Illuminate\Support\Facades\Mail::send('auth.emails.password', ['token' => $token, 'email' => $request->email], function($message) use($request){
            $message->to($request->email);
            $message->subject('Reset Password Notification - Manually Triggered');
        });

        Cache::forget('admin_notifications');

        return redirect()->back()->with('success', 'A password reset link has been successfully generated and sent to ' . $request->email);
    }

    // ─── PLAN MANAGEMENT ────────────────────────

    /**
     * List all plans with optional purpose filter.
     */
    public function plans(Request $request)
    {
        Plan::ensureSchema();
        $query = Plan::query();
        if ($request->filled('purpose') && in_array($request->purpose, ['rent', 'buy'])) {
            $query->whereIn('purpose', [$request->purpose, 'both']);
        }
        $plans = $query->orderBy('sort_order')->get();
        $selectedPurpose = $request->input('purpose', 'all');
        $gstRate = Setting::get('gst_rate', '0');
        $annualDiscount = Setting::get('annual_discount_percentage', '0');

        return view('admin.plans', compact('plans', 'selectedPurpose', 'gstRate', 'annualDiscount'));
    }

    /**
     * Update Tax & Pricing Rules directly from the Plans page.
     */
    public function updateTaxSettings(Request $request)
    {
        $data = $request->validate([
            'gst_rate' => 'nullable|numeric|min:0|max:100',
            'annual_discount_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        Setting::set('gst_rate', (string) ($data['gst_rate'] ?? '0'));
        Setting::set('annual_discount_percentage', (string) ($data['annual_discount_percentage'] ?? '0'));

        return redirect()->route('admin.plans')->with('success', 'Tax & Pricing rules (GST & Discount) updated successfully.');
    }

    /**
     * Restore or re-seed standard default passes.
     */
    public function restoreDefaultPlans()
    {
        Plan::seedDefaultPlans(true);
        return redirect()->route('admin.plans')->with('success', 'Default rental and buyer passes restored successfully.');
    }

    /**
     * Show create plan form.
     */
    public function createPlan()
    {
        Plan::ensureSchema();
        return view('admin.plan-form', ['plan' => null]);
    }

    /**
     * Store a new plan.
     */
    public function storePlan(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'purpose' => 'required|string|in:rent,buy,both',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'contact_limit' => 'required|integer|min:1',
            'features' => 'nullable|string',
            'is_active' => 'boolean',
            'is_private' => 'boolean',
            'image' => 'nullable|image|max:2048',
            'sort_order' => 'integer',
        ]);

        if (!empty($data['features'])) {
            $data['features'] = array_filter(array_map('trim', explode("\n", $data['features'])));
        }

        $data['is_active'] = $request->has('is_active');
        $data['is_private'] = $request->has('is_private');

        if (array_key_exists('original_price', $data) && ($data['original_price'] === '' || $data['original_price'] === null)) {
            $data['original_price'] = null;
        }

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('plans', 'public');
        }

        Plan::ensureSchema();
        if (!\Illuminate\Support\Facades\Schema::hasColumn('plans', 'original_price')) {
            unset($data['original_price']);
        }
        if (!\Illuminate\Support\Facades\Schema::hasColumn('plans', 'purpose')) {
            unset($data['purpose']);
        }
        if (!\Illuminate\Support\Facades\Schema::hasColumn('plans', 'is_private')) {
            unset($data['is_private']);
        }
        if (!\Illuminate\Support\Facades\Schema::hasColumn('plans', 'image_path')) {
            unset($data['image_path']);
        }

        Plan::create($data);

        return redirect()->route('admin.plans')->with('success', 'Plan created successfully.');
    }

    /**
     * Show edit plan form.
     */
    public function editPlan(Plan $plan)
    {
        Plan::ensureSchema();
        return view('admin.plan-form', compact('plan'));
    }

    /**
     * Update a plan.
     */
    public function updatePlan(Request $request, Plan $plan)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'purpose' => 'required|string|in:rent,buy,both',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'contact_limit' => 'required|integer|min:1',
            'features' => 'nullable|string',
            'is_active' => 'boolean',
            'is_private' => 'boolean',
            'image' => 'nullable|image|max:2048',
            'sort_order' => 'integer',
        ]);

        if (!empty($data['features'])) {
            $data['features'] = array_filter(array_map('trim', explode("\n", $data['features'])));
        }

        $data['is_active'] = $request->has('is_active');
        $data['is_private'] = $request->has('is_private');

        if (array_key_exists('original_price', $data) && ($data['original_price'] === '' || $data['original_price'] === null)) {
            $data['original_price'] = null;
        }

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('plans', 'public');
        }

        Plan::ensureSchema();
        if (!\Illuminate\Support\Facades\Schema::hasColumn('plans', 'original_price')) {
            unset($data['original_price']);
        }
        if (!\Illuminate\Support\Facades\Schema::hasColumn('plans', 'purpose')) {
            unset($data['purpose']);
        }
        if (!\Illuminate\Support\Facades\Schema::hasColumn('plans', 'is_private')) {
            unset($data['is_private']);
        }
        if (!\Illuminate\Support\Facades\Schema::hasColumn('plans', 'image_path')) {
            unset($data['image_path']);
        }

        $plan->update($data);

        return redirect()->route('admin.plans')->with('success', 'Plan updated successfully.');
    }

    /**
     * Delete a plan.
     */
    public function destroyPlan(Plan $plan)
    {
        $plan->delete();
        return redirect()->route('admin.plans')->with('success', 'Plan deleted.');
    }

    // ─── SUBSCRIPTION MANAGEMENT ──────────────────

    /**
     * List all user subscriptions.
     */
    public function subscriptions(Request $request)
    {
        $query = UserPlan::with(['user', 'plan'])->withCount('contactViews');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $subscriptions = $query->latest()->paginate(15);
        $plans = Plan::active()->orderBy('sort_order')->get();
        return view('admin.subscriptions', compact('subscriptions', 'plans'));
    }

    /**
     * Show form to manually assign a subscription to a user.
     */
    public function showAssignSubscriptionForm(Request $request)
    {
        $users = User::orderBy('name')->get();
        $plans = Plan::active()->orderBy('sort_order')->get();
        $selectedUserId = $request->query('user_id');
        return view('admin.assign-subscription', compact('users', 'plans', 'selectedUserId'));
    }

    /**
     * Manually assign a subscription to a user.
     */
    public function assignSubscription(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'plan_id' => 'required|exists:plans,id',
            'assign_type' => 'required|in:custom_offer,instant',
            'billing_period' => 'required|in:monthly,yearly',
            'discounted_price' => 'nullable|numeric|min:0',
        ]);

        $user = User::findOrFail($request->user_id);
        $plan = Plan::findOrFail($request->plan_id);

        if ($request->assign_type === 'custom_offer') {
            \App\Models\PrivateUserOffer::updateOrCreate(
                ['user_id' => $user->id, 'plan_id' => $plan->id, 'billing_period' => $request->billing_period],
                [
                    'status' => 'active', 
                    'expires_at' => now()->addDays(30),
                    'discounted_price' => $request->discounted_price
                ]
            );
            
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\ExclusiveOfferAssigned($user, $plan, $request->discounted_price));

            return redirect()->route('admin.subscriptions')->with('success', "Custom offer '{$plan->name}' assigned to {$user->name}. They can now log in to view and purchase it.");
        }

        if ($user->hasActivePlan()) {
            // Cancel current active plans
            $user->userPlans()->active()->update([
                'status' => 'rejected',
                'expires_at' => now(),
            ]);
        }

        $durationDays = (int) ($plan->duration_days ?: 30);

        $userPlan = UserPlan::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'status' => 'approved',
            'payment_reference' => 'MANUAL_ASSIGNMENT_' . strtoupper(\Illuminate\Support\Str::random(6)),
            'amount_paid' => 0,
            'approved_at' => now(),
            'expires_at' => now()->addDays($durationDays),
            'admin_note' => 'Manually assigned by admin.',
        ]);

        \Illuminate\Support\Facades\Mail::to($user->email)->send(new SubscriptionActivated($userPlan));

        return redirect()->route('admin.subscriptions')->with('success', "Plan '{$plan->name}' has been manually assigned to {$user->name}.");
    }

    /**
     * Approve a user's plan subscription.
     */
    public function approveSubscription(UserPlan $userPlan)
    {
        $durationDays = (int) ($userPlan->plan->duration_days ?: 30);

        $userPlan->update([
            'status' => 'approved',
            'approved_at' => now(),
            'expires_at' => now()->addDays($durationDays),
        ]);

        \Illuminate\Support\Facades\Mail::to($userPlan->user->email)->send(new SubscriptionActivated($userPlan));

        return redirect()->back()
            ->with('success', "Subscription for {$userPlan->user->name} approved.");
    }

    /**
     * Reject a user's plan subscription.
     */
    public function rejectSubscription(UserPlan $userPlan, Request $request)
    {
        $userPlan->update([
            'status' => 'rejected',
            'admin_note' => $request->input('admin_note'),
        ]);

        return redirect()->back()
            ->with('success', "Subscription for {$userPlan->user->name} rejected.");
    }

    /**
     * Cancel an active subscription.
     */
    public function cancelSubscription(UserPlan $userPlan)
    {
        $userPlan->update([
            'status' => 'rejected',
            'expires_at' => now(), // End it immediately
        ]);

        return redirect()->back()
            ->with('success', "Subscription for {$userPlan->user->name} has been cancelled.");
    }

    /**
     * Change the plan tier for an existing subscription.
     */
    public function updateSubscriptionPlanTier(Request $request, UserPlan $userPlan)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
        ]);

        $userPlan->update([
            'plan_id' => $request->plan_id,
        ]);

        return redirect()->back()
            ->with('success', "Subscription for {$userPlan->user->name} updated to " . $userPlan->plan->name);
    }

    /**
     * Delete a user's subscription permanently.
     */
    public function destroySubscription(UserPlan $userPlan)
    {
        $userName = $userPlan->user->name ?? 'User';
        $userPlan->delete();

        return redirect()->back()
            ->with('success', "Subscription for {$userName} deleted permanently.");
    }

    /**
     * Display all user transactions and payment records with status management.
     */
    public function transactions(Request $request)
    {
        $status = $request->query('status', 'all');
        $method = $request->query('method', 'all');
        $search = trim((string) $request->query('search', ''));
        $dateRange = $request->query('date_range', 'all');
        $tab = $request->query('tab', 'transactions'); // 'transactions' or 'logs'

        // Base query on UserPlan (Plan purchases & subscription payments)
        $query = UserPlan::with(['user', 'plan'])->latest();

        if ($status !== 'all' && !empty($status)) {
            $query->where('status', $status);
        }

        if ($method !== 'all' && !empty($method)) {
            $query->where('payment_method', $method);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                  ->orWhere('invoice_id', 'like', "%{$search}%")
                  ->orWhere('payment_reference', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
                  })
                  ->orWhereHas('plan', function ($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($dateRange === 'today') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($dateRange === 'week') {
            $query->where('created_at', '>=', Carbon::now()->subDays(7));
        } elseif ($dateRange === 'month') {
            $query->where('created_at', '>=', Carbon::now()->subDays(30));
        }

        $transactions = $query->paginate(15)->withQueryString();

        // Gateway Logs Query (PaymentLog)
        $logQuery = PaymentLog::with('user')->latest();
        if ($status !== 'all' && !empty($status)) {
            $logQuery->where('status', $status);
        }
        if (!empty($search)) {
            $logQuery->where(function ($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                  ->orWhere('method', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        $paymentLogs = $logQuery->paginate(15, ['*'], 'log_page')->withQueryString();

        // Calculate Overview Statistics
        $totalApproved = UserPlan::where('status', 'approved')->count();
        $totalPending = UserPlan::where('status', 'pending')->count();
        $totalRejected = UserPlan::whereIn('status', ['rejected', 'failed'])->count();
        $totalExpired = UserPlan::where('status', 'expired')->count();
        $totalTransactionsCount = UserPlan::count();

        $totalRevenue = (float) UserPlan::where('status', 'approved')->sum('final_amount');
        if ($totalRevenue <= 0) {
            $totalRevenue = (float) UserPlan::where('status', 'approved')->sum('amount_paid');
        }

        $todayRevenue = (float) UserPlan::where('status', 'approved')
            ->whereDate('created_at', Carbon::today())
            ->sum('final_amount');
        if ($todayRevenue <= 0) {
            $todayRevenue = (float) UserPlan::where('status', 'approved')
                ->whereDate('created_at', Carbon::today())
                ->sum('amount_paid');
        }

        $stats = [
            'total_revenue'  => $totalRevenue,
            'today_revenue'  => $todayRevenue,
            'total_count'    => $totalTransactionsCount,
            'approved_count' => $totalApproved,
            'pending_count'  => $totalPending,
            'rejected_count' => $totalRejected,
            'expired_count'  => $totalExpired,
            'logs_count'     => PaymentLog::count(),
        ];

        return view('admin.transactions', compact(
            'transactions',
            'paymentLogs',
            'stats',
            'status',
            'method',
            'search',
            'dateRange',
            'tab'
        ));
    }

    /**
     * Export transactions as CSV download.
     */
    public function exportTransactionsCsv(Request $request)
    {
        $status = $request->query('status', 'all');
        $query = UserPlan::with(['user', 'plan'])->latest();

        if ($status !== 'all' && !empty($status)) {
            $query->where('status', $status);
        }

        $records = $query->get();

        $filename = 'unlockrentals-transactions-' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($records) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, [
                'Transaction ID',
                'Invoice ID',
                'Payment Ref',
                'User Name',
                'User Email',
                'User Phone',
                'Plan',
                'Billing Period',
                'Subtotal (INR)',
                'Discount (INR)',
                'GST (INR)',
                'Final Amount Paid (INR)',
                'Payment Method',
                'Status',
                'Purchased At',
                'Approved At',
                'Expires At',
                'Admin Note'
            ]);

            foreach ($records as $txn) {
                fputcsv($file, [
                    $txn->transaction_id ?: ('TXN-' . $txn->id),
                    $txn->invoice_id ?: ('INV-' . $txn->id),
                    $txn->payment_reference ?: 'N/A',
                    $txn->user->name ?? 'Deleted User',
                    $txn->user->email ?? 'N/A',
                    $txn->user->phone ?? 'N/A',
                    $txn->plan->name ?? 'Custom / Deleted Plan',
                    ucfirst($txn->billing_period ?: 'monthly'),
                    number_format($txn->subtotal_amount ?: ($txn->amount_paid ?: 0), 2, '.', ''),
                    number_format($txn->discount_amount ?: 0, 2, '.', ''),
                    number_format($txn->gst_amount ?: 0, 2, '.', ''),
                    number_format($txn->final_amount ?: ($txn->amount_paid ?: 0), 2, '.', ''),
                    strtoupper($txn->payment_method ?: 'ONLINE'),
                    strtoupper($txn->status ?: 'PENDING'),
                    $txn->created_at ? $txn->created_at->format('Y-m-d H:i:s') : '',
                    $txn->approved_at ? $txn->approved_at->format('Y-m-d H:i:s') : '',
                    $txn->expires_at ? $txn->expires_at->format('Y-m-d H:i:s') : '',
                    $txn->admin_note ?: ''
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Approve a pending transaction.
     */
    public function approveTransaction(UserPlan $userPlan)
    {
        $durationDays = (int) ($userPlan->plan->duration_days ?: 30);

        $userPlan->update([
            'status' => 'approved',
            'approved_at' => now(),
            'expires_at' => now()->addDays($durationDays),
        ]);

        try {
            \Illuminate\Support\Facades\Mail::to($userPlan->user->email)->send(new SubscriptionActivated($userPlan));
        } catch (\Throwable $e) {}

        return redirect()->back()
            ->with('success', "Transaction {$userPlan->transaction_id} for {$userPlan->user->name} has been approved and activated.");
    }

    /**
     * Reject a pending transaction.
     */
    public function rejectTransaction(UserPlan $userPlan, Request $request)
    {
        $userPlan->update([
            'status' => 'rejected',
            'admin_note' => $request->input('admin_note', 'Rejected by admin'),
        ]);

        return redirect()->back()
            ->with('success', "Transaction {$userPlan->transaction_id} has been marked as rejected.");
    }

    /**
     * Show all activity for a specific user.
     */
    public function userActivity(User $user)
    {
        $user->load(['userPlans.plan', 'inquiries.property']);
        
        $contactViews = \App\Models\ContactView::where('user_id', $user->id)
            ->with('property')
            ->latest()
            ->get();

        return view('admin.user-activity', compact('user', 'contactViews'));
    }

    /**
     * Toggle the bypass approval setting dynamically.
     */
    public function toggleBypassApproval(Request $request)
    {
        $current = \App\Models\Setting::get('bypass_property_approval', '0');
        $newVal = ($current == '1') ? '0' : '1';

        \App\Models\Setting::updateOrCreate(
            ['key' => 'bypass_property_approval'],
            ['value' => $newVal]
        );

        \Illuminate\Support\Facades\Cache::forget('site_settings');

        $statusMsg = ($newVal === '1') 
            ? 'Bypass Approval turned ON! All new properties will post directly.' 
            : 'Bypass Approval turned OFF! All new properties will now require manual verification.';

        return redirect()->back()->with('success', $statusMsg);
    }

    // ─── PROCESS STEPS MANAGEMENT ────────────────

    /**
     * List all process steps.
     */
    public function processSteps()
    {
        $steps = ProcessStep::orderBy('sort_order')->get();
        return view('admin.process-steps.index', compact('steps'));
    }

    /**
     * Show create process step form.
     */
    public function createProcessStep()
    {
        return view('admin.process-steps.form', ['step' => null]);
    }

    /**
     * Store a new process step.
     */
    public function storeProcessStep(Request $request)
    {
        $data = $request->validate([
            'step_number' => 'nullable|string|max:50',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon_svg' => 'nullable|string',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ]);

        $data['is_active'] = $request->has('is_active');

        ProcessStep::create($data);

        Cache::forget('home_process_steps');

        return redirect()->route('admin.process-steps')->with('success', 'Process step created successfully.');
    }

    /**
     * Show edit process step form.
     */
    public function editProcessStep(ProcessStep $processStep)
    {
        return view('admin.process-steps.form', ['step' => $processStep]);
    }

    /**
     * Update a process step.
     */
    public function updateProcessStep(Request $request, ProcessStep $processStep)
    {
        $data = $request->validate([
            'step_number' => 'nullable|string|max:50',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon_svg' => 'nullable|string',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ]);

        $data['is_active'] = $request->has('is_active');

        $processStep->update($data);

        Cache::forget('home_process_steps');

        return redirect()->route('admin.process-steps')->with('success', 'Process step updated successfully.');
    }

    /**
     * Delete a process step.
     */
    public function destroyProcessStep(ProcessStep $processStep)
    {
        $processStep->delete();
        Cache::forget('home_process_steps');
        return redirect()->route('admin.process-steps')->with('success', 'Process step deleted successfully.');
    }

    /**
     * List and manage locations (States, Cities/Districts, Localities).
     */
    public function locations()
    {
        $totalStates = \App\Models\State::count();
        $totalDistricts = \App\Models\District::count();
        $totalLocalities = \App\Models\Locality::count();

        $states = \App\Models\State::withCount('districts')->orderBy('name')->get();
        $allDistricts = \App\Models\District::with('state')->orderBy('name')->get();
        
        $selectedStateId = request('state_id');
        $selectedDistrictId = request('district_id');

        $districts = $selectedStateId 
            ? \App\Models\District::where('state_id', $selectedStateId)->withCount('localities')->orderBy('name')->get()
            : collect();

        $localities = $selectedDistrictId
            ? \App\Models\Locality::where('district_id', $selectedDistrictId)->with('district.state')->orderBy('name')->paginate(25)
            : collect();

        return view('admin.locations', compact(
            'states', 
            'allDistricts',
            'districts', 
            'localities', 
            'selectedStateId', 
            'selectedDistrictId',
            'totalStates',
            'totalDistricts',
            'totalLocalities'
        ));
    }

    /**
     * Store a new state.
     */
    public function storeState(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:5|unique:states,code',
            'name' => 'required|string|max:100|unique:states,name',
        ]);

        $state = \App\Models\State::create([
            'code' => strtoupper(trim($request->code)),
            'name' => trim($request->name),
        ]);

        \App\Providers\AppServiceProvider::clearLocationCache();

        return redirect()->route('admin.locations', ['state_id' => $state->id])
            ->with('success', "State '{$state->name}' ({$state->code}) created successfully.");
    }

    /**
     * Delete a state.
     */
    public function destroyState(\App\Models\State $state)
    {
        $name = $state->name;
        $state->delete();

        \App\Providers\AppServiceProvider::clearLocationCache();

        return redirect()->route('admin.locations')
            ->with('success', "State '{$name}' and its cities/localities deleted successfully.");
    }

    /**
     * Store a new district / city.
     */
    public function storeDistrict(Request $request)
    {
        $request->validate([
            'state_id' => 'required|exists:states,id',
            'name' => 'required|string|max:100',
        ]);

        $district = \App\Models\District::create([
            'state_id' => $request->state_id,
            'name' => trim($request->name),
        ]);

        \App\Providers\AppServiceProvider::clearLocationCache();

        return redirect()->route('admin.locations', [
            'state_id' => $district->state_id,
            'district_id' => $district->id
        ])->with('success', "City/District '{$district->name}' created successfully.");
    }

    /**
     * Delete a district / city.
     */
    public function destroyDistrict(\App\Models\District $district)
    {
        $name = $district->name;
        $stateId = $district->state_id;
        $district->delete();

        \App\Providers\AppServiceProvider::clearLocationCache();

        return redirect()->route('admin.locations', ['state_id' => $stateId])
            ->with('success', "City/District '{$name}' and its localities deleted successfully.");
    }

    /**
     * Store a new locality.
     */
    public function storeLocality(Request $request)
    {
        $request->validate([
            'district_id' => 'required|exists:districts,id',
            'name' => 'required|string|max:100',
        ]);

        $locality = \App\Models\Locality::create([
            'district_id' => $request->district_id,
            'name' => trim($request->name),
        ]);

        \App\Providers\AppServiceProvider::clearLocationCache();

        $district = \App\Models\District::find($request->district_id);

        return redirect()->route('admin.locations', [
            'state_id' => $district ? $district->state_id : null,
            'district_id' => $district ? $district->id : null
        ])->with('success', "Locality '{$locality->name}' added successfully.");
    }

    /**
     * Delete a locality.
     */
    public function destroyLocality(\App\Models\Locality $locality)
    {
        $name = $locality->name;
        $district = $locality->district;
        $locality->delete();

        \App\Providers\AppServiceProvider::clearLocationCache();

        return redirect()->route('admin.locations', [
            'state_id' => $district ? $district->state_id : null,
            'district_id' => $district ? $district->id : null
        ])->with('success', "Locality '{$name}' deleted successfully.");
    }

    // ─── BLOG POST MANAGEMENT ──────────────────────

    protected static bool $blogColumnsChecked = false;

    /**
     * Auto-ensure blogs table has required newer columns if migrations have not been run.
     */
    protected function ensureBlogColumnsExist(): void
    {
        if (static::$blogColumnsChecked) {
            return;
        }

        try {
            if (Schema::hasTable('blogs') && !Schema::hasColumn('blogs', 'show_in_slider')) {
                Schema::table('blogs', function (Blueprint $table) {
                    $table->boolean('show_in_slider')->default(false)->after('is_featured')->index();
                });
            }
            static::$blogColumnsChecked = true;
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Blogs table show_in_slider auto-create warning: ' . $e->getMessage());
        }
    }

    /**
     * Display a listing of blog posts with summary KPIs & filters.
     */
    public function blogs(Request $request)
    {
        $this->ensureBlogColumnsExist();

        if (!Schema::hasTable('blogs')) {
            $stats = ['total' => 0, 'published' => 0, 'draft' => 0, 'views' => 0];
            $blogs = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 12);
            $categories = collect(['Tenant Guide', 'Owner Insights', 'Commercial Hub', 'Legal & Finance', 'Lifestyle & Tech', 'Market Trends']);
            session()->flash('error', 'The blogs table has not been created in the database yet. Please run migrations by visiting /run-migrations?key=' . env('MIGRATION_KEY', 'UnlockRentalsSecureMigrateKey2026'));
            return view('admin.blogs.index', compact('blogs', 'stats', 'categories'));
        }

        $stats = [
            'total'     => Blog::count(),
            'published' => Blog::where('is_published', true)->count(),
            'draft'     => Blog::where('is_published', false)->count(),
            'views'     => Blog::sum('views_count'),
        ];

        $query = Blog::query();

        // Keyword search (title, excerpt, content, category, author)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('author_name', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // Status filter
        if ($request->filled('status')) {
            if ($request->status === 'published') {
                $query->where('is_published', true);
            } elseif ($request->status === 'draft') {
                $query->where('is_published', false);
            } elseif ($request->status === 'featured') {
                $query->where('is_featured', true);
            }
        }

        // Sorting (Date, Views, Title, Updated, Read Time)
        $sort = $request->get('sort', 'latest');
        match ($sort) {
            'oldest'          => $query->orderByRaw('COALESCE(published_at, created_at) ASC')->orderBy('id', 'asc'),
            'views_desc'      => $query->orderBy('views_count', 'desc')->orderBy('id', 'desc'),
            'views_asc'       => $query->orderBy('views_count', 'asc')->orderBy('id', 'desc'),
            'title_asc'       => $query->orderBy('title', 'asc'),
            'title_desc'      => $query->orderBy('title', 'desc'),
            'updated_desc'    => $query->orderBy('updated_at', 'desc'),
            'read_time_desc'  => $query->orderBy('read_time', 'desc')->orderBy('id', 'desc'),
            'read_time_asc'   => $query->orderBy('read_time', 'asc')->orderBy('id', 'desc'),
            default           => $query->orderByRaw('COALESCE(published_at, created_at) DESC')->orderBy('id', 'desc'),
        };

        $blogs = $query->paginate(8)->withQueryString();
        $categories = Blog::select('category')->distinct()->pluck('category')->filter()->values();

        return view('admin.blogs.index', compact('blogs', 'stats', 'categories'));
    }

    /**
     * Show the form for creating a new blog post.
     */
    public function createBlog()
    {
        $this->ensureBlogColumnsExist();

        if (!Schema::hasTable('blogs')) {
            return redirect()->route('admin.blogs.index')->with('error', 'The blogs table has not been created yet. Please run migrations first.');
        }

        $categories = Blog::select('category')->distinct()->pluck('category')->filter()->values();
        if ($categories->isEmpty()) {
            $categories = collect(['Tenant Guide', 'Owner Insights', 'Commercial Hub', 'Legal & Finance', 'Lifestyle & Tech', 'Market Trends']);
        }
        return view('admin.blogs.form', ['blog' => null, 'categories' => $categories]);
    }

    /**
     * Store a newly created blog post.
     */
    public function storeBlog(Request $request)
    {
        $this->ensureBlogColumnsExist();

        $data = $request->validate([
            'title'             => 'required|string|max:255',
            'slug'              => 'nullable|string|max:255|unique:blogs,slug',
            'category'          => 'required|string|max:100',
            'custom_category'   => 'nullable|string|max:100',
            'excerpt'           => 'nullable|string|max:1000',
            'content'           => 'required|string',
            'image'             => 'nullable|file|mimes:jpeg,png,jpg,webp,avif,gif,svg,jfif|max:20480',
            'image_url'         => 'nullable|string|max:1000',
            'author_name'       => 'nullable|string|max:150',
            'author_role'       => 'nullable|string|max:150',
            'author_avatar'     => 'nullable|file|mimes:jpeg,png,jpg,webp,avif,gif,svg,jfif|max:10240',
            'read_time'         => 'nullable|string|max:50',
            'is_featured'       => 'nullable|boolean',
            'show_in_slider'    => 'nullable|boolean',
            'is_published'      => 'nullable|boolean',
            'published_at'      => 'nullable|date',
            'meta_title'        => 'nullable|string|max:255',
            'meta_description'  => 'nullable|string|max:1000',
            'tags'              => 'nullable|string',
        ]);

        // Category selection fallback
        if (!empty($data['custom_category'])) {
            $data['category'] = trim($data['custom_category']);
        }
        unset($data['custom_category']);

        // Handle slug
        if (empty($data['slug'])) {
            $data['slug'] = Blog::generateUniqueSlug($data['title']);
        } else {
            $data['slug'] = Str::slug($data['slug']);
        }

        // Handle tags
        if (!empty($data['tags'])) {
            $data['tags'] = array_values(array_filter(array_map('trim', explode(',', $data['tags']))));
        } else {
            $data['tags'] = [];
        }

        // Handle Cover Image: Prioritize pre-compressed base64 for instant upload speed
        if ($request->filled('image_base64') && str_starts_with($request->image_base64, 'data:image/')) {
            $base64Data = $request->image_base64;
            @list($type, $base64Data) = explode(';', $base64Data);
            @list(, $base64Data)      = explode(',', $base64Data);
            $decoded = base64_decode($base64Data);
            
            $ext = 'jpg';
            if (str_contains($type, 'png')) $ext = 'png';
            elseif (str_contains($type, 'webp')) $ext = 'webp';

            $filename = time() . '_' . Str::random(12) . '.' . $ext;
            $path = 'blogs/' . $filename;

            Storage::disk('public')->put($path, $decoded);

            try {
                @mkdir(public_path('blogs'), 0777, true);
                @file_put_contents(public_path('blogs/' . $filename), $decoded);
            } catch (\Throwable $e) {}

            try {
                @mkdir(public_path('storage/blogs'), 0777, true);
                @file_put_contents(public_path('storage/blogs/' . $filename), $decoded);
            } catch (\Throwable $e) {}

            $data['image'] = $path;
        } elseif ($request->hasFile('image')) {
            $file = $request->file('image');
            $ext = strtolower($file->getClientOriginalExtension() ?: 'jpg');
            $filename = time() . '_' . Str::random(12) . '.' . $ext;
            $path = $file->storeAs('blogs', $filename, 'public');

            // Mirror directly into public directories for bulletproof access
            try {
                @mkdir(public_path('blogs'), 0777, true);
                @copy(storage_path('app/public/blogs/' . $filename), public_path('blogs/' . $filename));
            } catch (\Throwable $e) {}

            try {
                @mkdir(public_path('storage/blogs'), 0777, true);
                @copy(storage_path('app/public/blogs/' . $filename), public_path('storage/blogs/' . $filename));
            } catch (\Throwable $e) {}

            $data['image'] = $path;
        } elseif (!empty($data['image_url'])) {
            $url = trim($data['image_url']);
            if (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://') && !str_starts_with($url, '//')) {
                $url = 'https://' . $url;
            }
            $data['image'] = $url;
        }
        unset($data['image_url']);
        unset($data['image_base64']);

        // Handle Author Avatar: Prioritize pre-compressed base64
        if ($request->filled('author_avatar_base64') && str_starts_with($request->author_avatar_base64, 'data:image/')) {
            $base64Data = $request->author_avatar_base64;
            @list($type, $base64Data) = explode(';', $base64Data);
            @list(, $base64Data)      = explode(',', $base64Data);
            $decoded = base64_decode($base64Data);
            
            $ext = 'jpg';
            if (str_contains($type, 'png')) $ext = 'png';
            elseif (str_contains($type, 'webp')) $ext = 'webp';

            $filename = time() . '_' . Str::random(12) . '.' . $ext;
            $path = 'blogs/authors/' . $filename;

            Storage::disk('public')->put($path, $decoded);

            try {
                @mkdir(public_path('blogs/authors'), 0777, true);
                @file_put_contents(public_path('blogs/authors/' . $filename), $decoded);
            } catch (\Throwable $e) {}

            try {
                @mkdir(public_path('storage/blogs/authors'), 0777, true);
                @file_put_contents(public_path('storage/blogs/authors/' . $filename), $decoded);
            } catch (\Throwable $e) {}

            $data['author_avatar'] = $path;
        } elseif ($request->hasFile('author_avatar')) {
            $file = $request->file('author_avatar');
            $ext = strtolower($file->getClientOriginalExtension() ?: 'jpg');
            $filename = time() . '_' . Str::random(12) . '.' . $ext;
            $path = $file->storeAs('blogs/authors', $filename, 'public');

            try {
                @mkdir(public_path('blogs/authors'), 0777, true);
                @copy(storage_path('app/public/blogs/authors/' . $filename), public_path('blogs/authors/' . $filename));
            } catch (\Throwable $e) {}

            try {
                @mkdir(public_path('storage/blogs/authors'), 0777, true);
                @copy(storage_path('app/public/blogs/authors/' . $filename), public_path('storage/blogs/authors/' . $filename));
            } catch (\Throwable $e) {}

            $data['author_avatar'] = $path;
        }
        unset($data['author_avatar_base64']);

        $data['user_id'] = auth()->id();
        $data['is_published'] = $request->boolean('is_published');
        $data['is_featured'] = $request->boolean('is_featured');
        $showInSlider = $request->boolean('show_in_slider');

        if (Schema::hasColumn('blogs', 'show_in_slider')) {
            $data['show_in_slider'] = $showInSlider;
        } else {
            unset($data['show_in_slider']);
        }

        if ($data['is_published']) {
            $data['published_at'] = $request->filled('published_at') ? Carbon::parse($request->published_at) : now();
        } else {
            $data['published_at'] = $request->filled('published_at') ? Carbon::parse($request->published_at) : null;
        }

        // Auto read time if empty
        if (empty($data['read_time'])) {
            $wordCount = str_word_count(strip_tags($data['content']));
            $data['read_time'] = max(1, (int) ceil($wordCount / 200)) . ' min read';
        }

        $blog = Blog::create($data);

        // Synchronize with home_blog_slider_ids setting
        $sliderIds = json_decode(Setting::get('home_blog_slider_ids', '[]'), true) ?: [];
        if ($showInSlider) {
            if (!in_array($blog->id, $sliderIds)) {
                $sliderIds[] = $blog->id;
                Setting::updateOrCreate(['key' => 'home_blog_slider_ids'], ['value' => json_encode($sliderIds)]);
                Cache::forget('site_settings');
            }
        } else {
            if (in_array($blog->id, $sliderIds)) {
                $sliderIds = array_values(array_diff($sliderIds, [$blog->id]));
                Setting::updateOrCreate(['key' => 'home_blog_slider_ids'], ['value' => json_encode($sliderIds)]);
                Cache::forget('site_settings');
            }
        }

        Cache::forget('home_slider_blogs');
        Cache::forget('home_blogs');
        Cache::forget('sitemap_blogs');

        return redirect()->route('admin.blogs.index')
            ->with('success', "Blog post \"{$blog->title}\" created successfully.");
    }

    /**
     * Show the form for editing a blog post.
     */
    public function editBlog(Blog $blog)
    {
        $this->ensureBlogColumnsExist();

        $categories = Blog::select('category')->distinct()->pluck('category')->filter()->values();
        if ($categories->isEmpty()) {
            $categories = collect(['Tenant Guide', 'Owner Insights', 'Commercial Hub', 'Legal & Finance', 'Lifestyle & Tech', 'Market Trends']);
        }
        return view('admin.blogs.form', compact('blog', 'categories'));
    }

    /**
     * Update an existing blog post.
     */
    public function updateBlog(Request $request, Blog $blog)
    {
        $this->ensureBlogColumnsExist();

        $data = $request->validate([
            'title'                 => 'required|string|max:255',
            'slug'                  => 'nullable|string|max:255|unique:blogs,slug,' . $blog->id,
            'category'              => 'required|string|max:100',
            'custom_category'       => 'nullable|string|max:100',
            'excerpt'               => 'nullable|string|max:1000',
            'content'               => 'required|string',
            'image'                 => 'nullable|file|mimes:jpeg,png,jpg,webp,avif,gif,svg,jfif|max:20480',
            'image_base64'          => 'nullable|string',
            'image_url'             => 'nullable|string|max:1000',
            'author_name'           => 'nullable|string|max:150',
            'author_role'           => 'nullable|string|max:150',
            'author_avatar'         => 'nullable|file|mimes:jpeg,png,jpg,webp,avif,gif,svg,jfif|max:10240',
            'author_avatar_base64'  => 'nullable|string',
            'read_time'             => 'nullable|string|max:50',
            'is_featured'           => 'nullable|boolean',
            'show_in_slider'        => 'nullable|boolean',
            'is_published'          => 'nullable|boolean',
            'published_at'          => 'nullable|date',
            'meta_title'            => 'nullable|string|max:255',
            'meta_description'      => 'nullable|string|max:1000',
            'tags'                  => 'nullable|string',
        ]);

        if (!empty($data['custom_category'])) {
            $data['category'] = trim($data['custom_category']);
        }
        unset($data['custom_category']);

        if (empty($data['slug'])) {
            $data['slug'] = Blog::generateUniqueSlug($data['title'], $blog->id);
        } else {
            $data['slug'] = Str::slug($data['slug']);
        }

        if (!empty($data['tags'])) {
            $data['tags'] = array_values(array_filter(array_map('trim', explode(',', $data['tags']))));
        } else {
            $data['tags'] = [];
        }

        // Handle cover image: Prioritize pre-compressed base64
        if ($request->filled('image_base64') && str_starts_with($request->image_base64, 'data:image/')) {
            if ($blog->image && !str_starts_with($blog->image, 'http')) {
                Storage::disk('public')->delete($blog->image);
                @unlink(public_path($blog->image));
                @unlink(public_path('blogs/' . basename($blog->image)));
            }
            $base64Data = $request->image_base64;
            @list($type, $base64Data) = explode(';', $base64Data);
            @list(, $base64Data)      = explode(',', $base64Data);
            $decoded = base64_decode($base64Data);
            
            $ext = 'jpg';
            if (str_contains($type, 'png')) $ext = 'png';
            elseif (str_contains($type, 'webp')) $ext = 'webp';

            $filename = time() . '_' . Str::random(12) . '.' . $ext;
            $path = 'blogs/' . $filename;

            Storage::disk('public')->put($path, $decoded);

            try {
                @mkdir(public_path('blogs'), 0777, true);
                @file_put_contents(public_path('blogs/' . $filename), $decoded);
            } catch (\Throwable $e) {}

            try {
                @mkdir(public_path('storage/blogs'), 0777, true);
                @file_put_contents(public_path('storage/blogs/' . $filename), $decoded);
            } catch (\Throwable $e) {}

            $data['image'] = $path;
        } elseif ($request->hasFile('image')) {
            if ($blog->image && !str_starts_with($blog->image, 'http')) {
                Storage::disk('public')->delete($blog->image);
                @unlink(public_path($blog->image));
                @unlink(public_path('blogs/' . basename($blog->image)));
            }
            $file = $request->file('image');
            $ext = strtolower($file->getClientOriginalExtension() ?: 'jpg');
            $filename = time() . '_' . Str::random(12) . '.' . $ext;
            $path = $file->storeAs('blogs', $filename, 'public');

            try {
                @mkdir(public_path('blogs'), 0777, true);
                @copy(storage_path('app/public/blogs/' . $filename), public_path('blogs/' . $filename));
            } catch (\Throwable $e) {}

            try {
                @mkdir(public_path('storage/blogs'), 0777, true);
                @copy(storage_path('app/public/blogs/' . $filename), public_path('storage/blogs/' . $filename));
            } catch (\Throwable $e) {}

            $data['image'] = $path;
        } elseif (!empty($data['image_url'])) {
            $url = trim($data['image_url']);
            if (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://') && !str_starts_with($url, '//')) {
                $url = 'https://' . $url;
            }
            $data['image'] = $url;
        }
        unset($data['image_url']);
        unset($data['image_base64']);

        // Handle author avatar: Prioritize pre-compressed base64
        if ($request->filled('author_avatar_base64') && str_starts_with($request->author_avatar_base64, 'data:image/')) {
            if ($blog->author_avatar && !str_starts_with($blog->author_avatar, 'http')) {
                Storage::disk('public')->delete($blog->author_avatar);
                @unlink(public_path($blog->author_avatar));
                @unlink(public_path('blogs/authors/' . basename($blog->author_avatar)));
            }
            $base64Data = $request->author_avatar_base64;
            @list($type, $base64Data) = explode(';', $base64Data);
            @list(, $base64Data)      = explode(',', $base64Data);
            $decoded = base64_decode($base64Data);
            
            $ext = 'jpg';
            if (str_contains($type, 'png')) $ext = 'png';
            elseif (str_contains($type, 'webp')) $ext = 'webp';

            $filename = time() . '_' . Str::random(12) . '.' . $ext;
            $path = 'blogs/authors/' . $filename;

            Storage::disk('public')->put($path, $decoded);

            try {
                @mkdir(public_path('blogs/authors'), 0777, true);
                @file_put_contents(public_path('blogs/authors/' . $filename), $decoded);
            } catch (\Throwable $e) {}

            try {
                @mkdir(public_path('storage/blogs/authors'), 0777, true);
                @file_put_contents(public_path('storage/blogs/authors/' . $filename), $decoded);
            } catch (\Throwable $e) {}

            $data['author_avatar'] = $path;
        } elseif ($request->hasFile('author_avatar')) {
            if ($blog->author_avatar && !str_starts_with($blog->author_avatar, 'http')) {
                Storage::disk('public')->delete($blog->author_avatar);
                @unlink(public_path($blog->author_avatar));
                @unlink(public_path('blogs/authors/' . basename($blog->author_avatar)));
            }
            $file = $request->file('author_avatar');
            $ext = strtolower($file->getClientOriginalExtension() ?: 'jpg');
            $filename = time() . '_' . Str::random(12) . '.' . $ext;
            $path = $file->storeAs('blogs/authors', $filename, 'public');

            try {
                @mkdir(public_path('blogs/authors'), 0777, true);
                @copy(storage_path('app/public/blogs/authors/' . $filename), public_path('blogs/authors/' . $filename));
            } catch (\Throwable $e) {}

            try {
                @mkdir(public_path('storage/blogs/authors'), 0777, true);
                @copy(storage_path('app/public/blogs/authors/' . $filename), public_path('storage/blogs/authors/' . $filename));
            } catch (\Throwable $e) {}

            $data['author_avatar'] = $path;
        }
        unset($data['author_avatar_base64']);

        $data['is_published'] = $request->boolean('is_published');
        $data['is_featured'] = $request->boolean('is_featured');
        $showInSlider = $request->boolean('show_in_slider');

        if (Schema::hasColumn('blogs', 'show_in_slider')) {
            $data['show_in_slider'] = $showInSlider;
        } else {
            unset($data['show_in_slider']);
        }

        if ($data['is_published'] && empty($blog->published_at)) {
            $data['published_at'] = $request->filled('published_at') ? Carbon::parse($request->published_at) : now();
        } elseif ($request->filled('published_at')) {
            $data['published_at'] = Carbon::parse($request->published_at);
        }

        if (empty($data['read_time'])) {
            $wordCount = str_word_count(strip_tags($data['content']));
            $data['read_time'] = max(1, (int) ceil($wordCount / 200)) . ' min read';
        }

        $blog->update($data);

        // Synchronize with home_blog_slider_ids setting
        $sliderIds = json_decode(Setting::get('home_blog_slider_ids', '[]'), true) ?: [];
        if ($showInSlider) {
            if (!in_array($blog->id, $sliderIds)) {
                $sliderIds[] = $blog->id;
                Setting::updateOrCreate(['key' => 'home_blog_slider_ids'], ['value' => json_encode($sliderIds)]);
                Cache::forget('site_settings');
            }
        } else {
            if (in_array($blog->id, $sliderIds)) {
                $sliderIds = array_values(array_diff($sliderIds, [$blog->id]));
                Setting::updateOrCreate(['key' => 'home_blog_slider_ids'], ['value' => json_encode($sliderIds)]);
                Cache::forget('site_settings');
            }
        }

        Cache::forget('home_slider_blogs');
        Cache::forget('home_blogs');
        Cache::forget('sitemap_blogs');

        return redirect()->route('admin.blogs.index')
            ->with('success', "Blog post \"{$blog->title}\" updated successfully.");
    }

    /**
     * Dedicated direct upload for blog cover image (AJAX or direct POST).
     */
    public function uploadBlogImage(Request $request, Blog $blog)
    {
        if ($request->hasFile('image')) {
            if ($blog->image && !str_starts_with($blog->image, 'http')) {
                Storage::disk('public')->delete($blog->image);
                @unlink(public_path($blog->image));
                @unlink(public_path('blogs/' . basename($blog->image)));
            }
            $file = $request->file('image');
            $ext = strtolower($file->getClientOriginalExtension() ?: 'jpg');
            $filename = time() . '_' . Str::random(12) . '.' . $ext;
            $path = $file->storeAs('blogs', $filename, 'public');

            try {
                @mkdir(public_path('blogs'), 0777, true);
                @copy(storage_path('app/public/blogs/' . $filename), public_path('blogs/' . $filename));
            } catch (\Throwable $e) {}

            try {
                @mkdir(public_path('storage/blogs'), 0777, true);
                @copy(storage_path('app/public/blogs/' . $filename), public_path('storage/blogs/' . $filename));
            } catch (\Throwable $e) {}

            $blog->update(['image' => $path]);
            Cache::forget('home_blogs');
            Cache::forget('sitemap_blogs');

            return response()->json([
                'success' => true,
                'message' => 'Cover image updated successfully!',
                'image_url' => $blog->fresh()->cover_image_url
            ]);
        }

        if ($request->filled('image_base64') && str_starts_with($request->image_base64, 'data:image/')) {
            if ($blog->image && !str_starts_with($blog->image, 'http')) {
                Storage::disk('public')->delete($blog->image);
                @unlink(public_path($blog->image));
                @unlink(public_path('blogs/' . basename($blog->image)));
            }
            $base64Data = $request->image_base64;
            @list($type, $base64Data) = explode(';', $base64Data);
            @list(, $base64Data)      = explode(',', $base64Data);
            $decoded = base64_decode($base64Data);
            
            $ext = 'jpg';
            if (str_contains($type, 'png')) $ext = 'png';
            elseif (str_contains($type, 'webp')) $ext = 'webp';

            $filename = time() . '_' . Str::random(12) . '.' . $ext;
            $path = 'blogs/' . $filename;

            Storage::disk('public')->put($path, $decoded);

            try {
                @mkdir(public_path('blogs'), 0777, true);
                @file_put_contents(public_path('blogs/' . $filename), $decoded);
            } catch (\Throwable $e) {}

            try {
                @mkdir(public_path('storage/blogs'), 0777, true);
                @file_put_contents(public_path('storage/blogs/' . $filename), $decoded);
            } catch (\Throwable $e) {}

            $blog->update(['image' => $path]);
            Cache::forget('home_blogs');
            Cache::forget('sitemap_blogs');

            return response()->json([
                'success' => true,
                'message' => 'Cover image updated successfully!',
                'image_url' => $blog->fresh()->cover_image_url
            ]);
        }

        if ($request->filled('image_url')) {
            $url = trim($request->image_url);
            if (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://') && !str_starts_with($url, '//')) {
                $url = 'https://' . $url;
            }
            $blog->update(['image' => $url]);
            Cache::forget('home_blogs');
            Cache::forget('sitemap_blogs');

            return response()->json([
                'success' => true,
                'message' => 'Cover image URL updated successfully!',
                'image_url' => $blog->fresh()->cover_image_url
            ]);
        }

        return response()->json(['success' => false, 'message' => 'No image data provided.'], 422);
    }

    /**
     * Delete a blog post.
     */
    public function destroyBlog(Blog $blog)
    {
        $title = $blog->title;
        if ($blog->image && !str_starts_with($blog->image, 'http')) {
            Storage::disk('public')->delete($blog->image);
        }
        if ($blog->author_avatar && !str_starts_with($blog->author_avatar, 'http')) {
            Storage::disk('public')->delete($blog->author_avatar);
        }

        $blog->delete();

        Cache::forget('home_blogs');
        Cache::forget('sitemap_blogs');

        return redirect()->route('admin.blogs.index')
            ->with('success', "Blog post \"{$title}\" has been deleted.");
    }

    /**
     * Toggle published status via quick action.
     */
    public function togglePublishBlog(Blog $blog)
    {
        $blog->is_published = !$blog->is_published;
        if ($blog->is_published && empty($blog->published_at)) {
            $blog->published_at = now();
        }
        $blog->save();

        Cache::forget('home_blogs');
        Cache::forget('sitemap_blogs');

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'is_published' => $blog->is_published,
                'status_text' => $blog->is_published ? 'Published' : 'Draft',
            ]);
        }

        $state = $blog->is_published ? 'published' : 'saved as draft';
        return redirect()->back()->with('success', "Blog \"{$blog->title}\" is now {$state}.");
    }

    /**
     * Toggle featured status via quick action.
     */
    public function toggleFeaturedBlog(Blog $blog)
    {
        $blog->is_featured = !$blog->is_featured;
        $blog->save();

        Cache::forget('home_blogs');

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'is_featured' => $blog->is_featured,
            ]);
        }

        $state = $blog->is_featured ? 'featured on blog homepage' : 'removed from featured';
        return redirect()->back()->with('success', "Blog \"{$blog->title}\" is now {$state}.");
    }

    /**
     * Toggle homepage slider status via quick action.
     */
    public function toggleSliderBlog(Blog $blog)
    {
        $this->ensureBlogColumnsExist();

        $hasColumn = Schema::hasColumn('blogs', 'show_in_slider');
        if ($hasColumn) {
            $blog->show_in_slider = !$blog->show_in_slider;
            $blog->save();
            $inSlider = $blog->show_in_slider;
        } else {
            $inSlider = false;
        }

        // Synchronize with home_blog_slider_ids setting
        $sliderIds = json_decode(Setting::get('home_blog_slider_ids', '[]'), true) ?: [];
        if (!$hasColumn) {
            if (in_array($blog->id, $sliderIds)) {
                $sliderIds = array_values(array_diff($sliderIds, [$blog->id]));
                $inSlider = false;
            } else {
                $sliderIds[] = $blog->id;
                $inSlider = true;
            }
        } else {
            if ($inSlider && !in_array($blog->id, $sliderIds)) {
                $sliderIds[] = $blog->id;
            } elseif (!$inSlider && in_array($blog->id, $sliderIds)) {
                $sliderIds = array_values(array_diff($sliderIds, [$blog->id]));
            }
        }

        Setting::updateOrCreate(['key' => 'home_blog_slider_ids'], ['value' => json_encode($sliderIds)]);
        Cache::forget('site_settings');
        Cache::forget('home_slider_blogs');
        Cache::forget('home_blogs');

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'show_in_slider' => $inSlider,
                'message' => $inSlider ? 'Added to homepage slider' : 'Removed from homepage slider',
            ]);
        }

        $state = $inSlider ? 'added to homepage slider' : 'removed from homepage slider';
        return redirect()->back()->with('success', "Blog \"{$blog->title}\" is now {$state}.");
    }

    /**
     * Check if new properties have been added or updated since last client check.
     */
    public function checkPropertyUpdates(Request $request)
    {
        $lastId = (int) $request->get('last_id', 0);
        $lastTotal = (int) $request->get('total', 0);
        $lastUpdated = (int) $request->get('last_updated', 0);

        $latestProp = Property::latest('id')->first();
        $latestId = $latestProp ? $latestProp->id : 0;
        $total = Property::count();

        $latestUpdatedProp = Property::latest('updated_at')->first();
        $latestUpdatedTimestamp = $latestUpdatedProp && $latestUpdatedProp->updated_at ? $latestUpdatedProp->updated_at->timestamp : 0;

        $hasUpdates = false;
        if ($lastId > 0 && $latestId > $lastId) {
            $hasUpdates = true;
        } elseif ($lastTotal > 0 && $total !== $lastTotal) {
            $hasUpdates = true;
        } elseif ($lastUpdated > 0 && $latestUpdatedTimestamp > $lastUpdated) {
            $hasUpdates = true;
        }

        return response()->json([
            'has_updates'       => $hasUpdates,
            'latest_id'         => $latestId,
            'latest_updated'    => $latestUpdatedTimestamp,
            'total'             => $total,
            'checked_at'        => now()->toDateTimeString(),
        ]);
    }

    /**
     * Trigger on-demand sync from 99acres crawler API.
     */
    public function triggerPropertySync(Request $request)
    {
        $lock = Cache::lock('ur_scraper_crawler_lock', 90);
        if (!$lock->get()) {
            return response()->json([
                'success' => false,
                'message' => 'Another property crawl or sync operation is already actively running. Please wait a moment.',
            ], 429);
        }

        $city = $request->get('city', 'Gurgaon');
        $count = (int) $request->get('count', 3);

        try {
            // Free any active DB connection before starting long-running scraper
            try { \Illuminate\Support\Facades\DB::disconnect(); } catch (\Throwable $e) {}

            \Illuminate\Support\Facades\Artisan::call('scrape:city', [
                'city' => $city,
                '--count' => $count,
            ]);

            try {
                \Illuminate\Support\Facades\Artisan::call('properties:enrich-images');
            } catch (\Throwable $e) {}

            Cache::forget('home_featured_rentals');
            Cache::forget('home_featured_properties');
            Cache::forget('admin_dashboard_stats');

            $latestProp = Property::latest('id')->first();

            return response()->json([
                'success'    => true,
                'message'    => "Synced {$count} listings for {$city} successfully!",
                'latest_id'  => $latestProp ? $latestProp->id : 0,
                'total'      => Property::count(),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sync error: ' . $e->getMessage(),
            ], 500);
        } finally {
            optional($lock)->release();
            try { \Illuminate\Support\Facades\DB::disconnect(); } catch (\Throwable $e) {}
        }
    }

    /**
     * External / 1-minute cron API to sync scraper.
     * Can be invoked via GET/POST or by cron jobs.
     */
    public function apiSyncCrawler(Request $request)
    {
        $url = trim((string) $request->input('url', ''));
        $rawCity = trim((string) $request->input('city', ''));

        // If URL was sent in the city field
        if (empty($url) && (preg_match('/^(https?:\/\/|www\.)/i', $rawCity) || str_contains($rawCity, '99acres'))) {
            $url = $rawCity;
            $rawCity = '';
        }

        // Clean any extraneous query strings mistakenly attached to canonical -ffid URLs
        if (str_contains($url, '-ffid') && str_contains($url, '?')) {
            $url = explode('?', $url, 2)[0];
        }

        // Reconstruct URL query parameters ONLY for search endpoints if parameters were stripped
        if (!empty($url) && str_contains($url, '/search/') && !str_contains($url, '?') && ($request->has('city') || $request->has('preference') || $request->has('keyword'))) {
            $rebuildQuery = [];
            foreach (['city', 'keyword', 'preference', 'res_com', 'isPreLeased', 'budget_min', 'budget_max'] as $qp) {
                if ($request->has($qp) && !str_contains($url, "{$qp}=")) {
                    $rebuildQuery[$qp] = $request->get($qp);
                }
            }
            if (!empty($rebuildQuery)) {
                $url .= '?' . http_build_query($rebuildQuery);
            }
        }

        // If direct fetch-and-post is requested via check-sync or sync-crawler
        if ($request->boolean('fetch_and_post') || $request->get('mode') === 'fetch_and_post') {
            return $this->fetchAndPostFromUrl($request);
        }

        $purpose = $request->input('purpose');
        if (empty($purpose) && !empty($url)) {
            $purpose = Property::extractPurposeFromUrl($url);
        }
        $purpose = $purpose ?: 'rent';

        $type = $request->input('type') ?? $request->input('property_type');
        if (empty($type) && !empty($url)) {
            $type = Property::extractPropertyTypeFromUrl($url);
        }
        $type = $type ?: 'all';

        $city = 'Hyderabad';
        if (!empty($url)) {
            [$urlCity] = Property::extractCityAndStateFromUrl($url);
            $city = $urlCity ?: 'Hyderabad';
        } elseif (!empty($rawCity)) {
            [$resolvedCity] = Property::extractCityAndStateFromUrl($rawCity);
            $city = $resolvedCity ?: $rawCity;
        }

        $count = (int) $request->get('count', 2);
        $page = max(1, (int) $request->get('page', 1));
        $autoClean = $request->boolean('clean_duplicates', true);

        // Current authentication status
        $isLoggedIn = auth()->check();
        $currentUser = auth()->user();
        $authStatus = [
            'is_logged_in'   => $isLoggedIn,
            'user_id'        => $currentUser ? $currentUser->id : null,
            'user_name'      => $currentUser ? $currentUser->name : 'System Admin (Auto Fallback)',
            'role'           => $currentUser ? $currentUser->role : 'admin',
            'session_id'     => session()->getId(),
            'session_active' => true,
            'keep_alive'     => true,
            'timestamp'      => now()->toIso8601String(),
        ];

        // Allow direct JSON property payload ingestion
        if ($request->has('properties') && is_array($request->input('properties'))) {
            $inserted = 0;
            $duplicatesSkipped = 0;
            $adminUser = User::where('role', 'admin')->first() ?? User::first();
            $userId = $adminUser ? $adminUser->id : 1;
            $category = \App\Models\Category::first();
            $categoryId = $category ? $category->id : 1;
            $bypass = \App\Models\Setting::get('bypass_property_approval', '1') == '1';

            foreach ($request->input('properties') as $item) {
                if (empty($item['title'])) continue;

                // Check duplicate before creation
                $candidateCheck = [
                    'title'         => $item['title'],
                    'city'          => $item['location'] ?? $city,
                    'location'      => $item['location'] ?? $city,
                    'locality'      => $item['locality'] ?? '',
                    'price'         => $item['price'] ?? 25000,
                    'purpose'       => $item['purpose'] ?? $purpose,
                    'bedrooms'      => $item['bedrooms'] ?? 2,
                    'images'        => $item['images'] ?? [],
                    'contact_phone' => $item['contact_phone'] ?? null,
                ];

                $existing = Property::findDuplicate($candidateCheck);
                if ($existing) {
                    $duplicatesSkipped++;
                    continue;
                }

                $slug = Str::slug($item['title']) . '-' . rand(10000, 99999);
                $prop = Property::create([
                    'user_id'       => $userId,
                    'category_id'   => $categoryId,
                    'title'         => $item['title'],
                    'slug'          => $slug,
                    'description'   => $item['description'] ?? "Listing in " . ($item['locality'] ?? $city),
                    'type'          => $item['type'] ?? 'house',
                    'purpose'       => $item['purpose'] ?? $purpose,
                    'price'         => $item['price'] ?? 25000,
                    'price_period'  => 'month',
                    'state'         => $item['state'] ?? 'Telangana',
                    'location'      => $item['location'] ?? $city,
                    'locality'      => $item['locality'] ?? $city,
                    'address'       => $item['address'] ?? (($item['locality'] ?? $city) . ', ' . $city),
                    'contact_phone' => $item['contact_phone'] ?? '+91 98765 43210',
                    'bedrooms'      => $item['bedrooms'] ?? 2,
                    'bathrooms'     => $item['bathrooms'] ?? 2,
                    'area_sqft'     => $item['area_sqft'] ?? 900,
                    'furnishing'    => $item['furnishing'] ?? 'semi-furnished',
                    'status'        => $bypass ? 'approved' : 'pending',
                    'approved_at'   => $bypass ? now() : null,
                ]);

                if (!empty($item['images']) && is_array($item['images'])) {
                    foreach ($item['images'] as $idx => $img) {
                        \App\Models\PropertyImage::create([
                            'property_id' => $prop->id,
                            'path'        => $img,
                            'is_primary'  => ($idx === 0),
                            'sort_order'  => $idx,
                        ]);
                    }
                }
                $inserted++;
            }

            Cache::forget('home_featured_rentals');
            Cache::forget('home_featured_properties');
            Cache::forget('admin_dashboard_stats');

            return response()->json([
                'status'               => 'success',
                'success'              => true,
                'message'              => "Imported {$inserted} direct listings ({$duplicatesSkipped} duplicates skipped)",
                'page_scraped'         => $page,
                'next_page'            => $page + 1,
                'new_imported'         => $inserted,
                'duplicates_prevented' => $duplicatesSkipped,
                'total_properties'     => Property::count(),
                'latest_id'            => Property::latest('id')->first()?->id ?? 0,
                'synced_at'            => now()->toDateTimeString(),
                'auth_status'          => $authStatus,
            ]);
        }

        $lock = Cache::lock('ur_scraper_crawler_lock', 90);
        if (!$lock->get()) {
            return response()->json([
                'status'      => 'busy',
                'success'     => false,
                'message'     => 'Crawler is already actively executing in background.',
                'auth_status' => $authStatus,
            ], 429);
        }

        try {
            try { \Illuminate\Support\Facades\DB::disconnect(); } catch (\Throwable $e) {}

            $scrapeTarget = !empty($url) ? $url : $city;

            \Illuminate\Support\Facades\Artisan::call('scrape:city', [
                'city'        => $scrapeTarget,
                '--count'     => $count,
                '--page'      => $page,
                '--purpose'   => $purpose,
                '--type'      => $type,
                '--auto-next' => true,
            ]);

            try {
                \Illuminate\Support\Facades\Artisan::call('properties:enrich-images');
            } catch (\Throwable $e) {}

            $cleanedCount = 0;
            if ($autoClean) {
                $cleanedCount = Property::cleanDuplicates();
            }

            Cache::forget('home_featured_rentals');
            Cache::forget('home_featured_properties');
            Cache::forget('admin_dashboard_stats');

            $latestProp = Property::latest('id')->first();

            return response()->json([
                'status'               => 'success',
                'success'              => true,
                'message'              => "Checked and synced " . (!empty($url) ? $url : $city) . " (Page {$page}). Duplicates checked & filtered.",
                'city'                 => $city,
                'url'                  => $url ?: null,
                'purpose'              => $purpose,
                'property_type'        => $type,
                'page_scraped'         => $page,
                'next_page'            => $page + 1,
                'duplicates_cleaned'   => $cleanedCount,
                'synced_at'            => now()->toDateTimeString(),
                'total_properties'     => Property::count(),
                'latest_id'            => $latestProp ? $latestProp->id : 0,
                'auth_status'          => $authStatus,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status'      => 'error',
                'success'     => false,
                'message'     => $e->getMessage(),
                'auth_status' => $authStatus,
            ], 500);
        } finally {
            optional($lock)->release();
            try { \Illuminate\Support\Facades\DB::disconnect(); } catch (\Throwable $e) {}
        }
    }

    /**
     * Session Keep-Alive & Auth Status Heartbeat Ping.
     * Continuously touches the session so no timeout or logout occurs,
     * and reports detailed authentication status to client/mobile app.
     */
    public function sessionPing(Request $request)
    {
        $isLoggedIn = auth()->check();
        $user = auth()->user();

        if ($isLoggedIn) {
            session(['last_user_activity_time' => time()]);
            session(['last_activity' => time()]);
            try {
                $request->session()->save();
            } catch (\Throwable $e) {}
        }

        $sessionLifetime = (int) config('session.lifetime', 525600);

        return response()->json([
            'success'          => true,
            'status'           => 'active',
            'is_logged_in'     => $isLoggedIn,
            'user'             => $user ? [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role,
            ] : null,
            'user_name'        => $user ? $user->name : 'Guest / System Service',
            'role'             => $user ? $user->role : 'guest',
            'session_id'       => session()->getId(),
            'session_active'   => true,
            'keep_alive'       => true,
            'session_lifetime' => $sessionLifetime,
            'timestamp'        => now()->toIso8601String(),
            'message'          => $isLoggedIn
                ? "Session active for {$user->name} ({$user->role}). Auto-logout prevented."
                : "Session active (Guest / Public API).",
        ]);
    }

    /**
     * Dedicated API endpoint to clean duplicate properties on demand.
     */
    public function apiCleanDuplicates(Request $request)
    {
        $cleaned = Property::cleanDuplicates();
        return response()->json([
            'status'             => 'success',
            'success'            => true,
            'message'            => "Cleaned {$cleaned} duplicate property records from database.",
            'duplicates_cleaned' => $cleaned,
            'total_properties'   => Property::count(),
            'latest_id'          => Property::latest('id')->first()?->id ?? 0,
            'cleaned_at'         => now()->toDateTimeString(),
        ]);
    }

    /**
     * Fetch real listings from 99acres URL or city, create landlord/owner accounts, and post properties.
     * Can be invoked from the Admin Panel or via API.
     */
    public function fetchAndPostFromUrl(Request $request)
    {
        $url = trim((string) $request->input('url', ''));
        $city = trim((string) $request->input('city', ''));

        // If URL was sent in the city field
        if (empty($url) && (preg_match('/^(https?:\/\/|www\.)/i', $city) || str_contains($city, '99acres'))) {
            $url = $city;
            $city = '';
        }

        // Clean any extraneous query strings mistakenly attached to canonical -ffid URLs
        if (str_contains($url, '-ffid') && str_contains($url, '?')) {
            $url = explode('?', $url, 2)[0];
        }

        // Reconstruct URL query parameters ONLY for search endpoints if parameters were stripped
        if (!empty($url) && str_contains($url, '/search/') && !str_contains($url, '?') && ($request->has('city') || $request->has('preference') || $request->has('keyword'))) {
            $rebuildQuery = [];
            foreach (['city', 'keyword', 'preference', 'res_com', 'isPreLeased', 'budget_min', 'budget_max', 'property_type'] as $qp) {
                if ($request->has($qp) && !str_contains($url, "{$qp}=")) {
                    $rebuildQuery[$qp] = $request->get($qp);
                }
            }
            if (!empty($rebuildQuery)) {
                $url .= '?' . http_build_query($rebuildQuery);
            }
        }

        // Auto-detect purpose from URL if not explicitly chosen or default
        $urlPurpose = !empty($url) ? Property::extractPurposeFromUrl($url) : null;
        $explicitPurpose = $request->input('purpose');
        $purpose = $explicitPurpose ? strtolower(trim((string) $explicitPurpose)) : ($urlPurpose ?: 'rent');

        // Auto-detect property type from URL
        $urlType = !empty($url) ? Property::extractPropertyTypeFromUrl($url) : null;
        $explicitType = $request->input('property_type') ?? $request->input('type');
        $propertyType = $explicitType ? strtolower(trim((string) $explicitType)) : ($urlType ?: 'all');

        // Auto-detect city & state
        if (empty($city) && !empty($url)) {
            [$extractedCity] = Property::extractCityAndStateFromUrl($url);
            $city = $extractedCity ?: 'Hyderabad';
        }
        if (empty($city) && $request->has('keyword')) {
            [$extractedCity] = Property::extractCityAndStateFromUrl((string) $request->input('keyword'));
            $city = $extractedCity ?: 'Hyderabad';
        }
        if (empty($city)) {
            $city = 'Hyderabad';
        }

        // Touch session to prevent idle timeout
        $isLoggedIn = auth()->check();
        $currentUser = auth()->user();
        if ($isLoggedIn) {
            session(['last_user_activity_time' => time()]);
            session(['last_activity' => time()]);
            try { $request->session()->save(); } catch (\Throwable $e) {}
        }

        $authStatus = [
            'is_logged_in'   => $isLoggedIn,
            'user_id'        => $currentUser ? $currentUser->id : null,
            'user_name'      => $currentUser ? $currentUser->name : 'System Admin (Auto Fallback)',
            'role'           => $currentUser ? $currentUser->role : 'admin',
            'session_id'     => session()->getId(),
            'session_active' => true,
            'keep_alive'     => true,
            'timestamp'      => now()->toIso8601String(),
        ];

        // If direct URL is given, use it. Otherwise, target the city (or 'all' for all cities)
        $target = !empty($url) ? $url : (!empty($city) ? $city : 'Hyderabad');

        $count = max(1, min(60, (int) $request->input('count', 10)));
        $page = max(1, (int) $request->input('page', 1));
        $autoNextPage = $request->boolean('auto_next_page', true);
        $engine = strtolower(trim((string) $request->input('engine', 'playwright')));
        $createAccounts = $request->boolean('create_accounts', true);
        $autoApprove = $request->boolean('auto_approve', true);

        if (!empty($target) && (str_starts_with($target, 'http://') || str_starts_with($target, 'https://'))) {
            // Check if URL already specifies page number
            if (preg_match('/-page-(\d+)/', $target, $pm) || preg_match('/[?&]page=(\d+)/', $target, $pm)) {
                if ($page === 1 && (int)$pm[1] > 1) {
                    $page = (int)$pm[1];
                }
            }
            $target = Property::applyPageToUrl($target, $page);
        }

        $lock = Cache::lock('ur_scraper_crawler_lock', 120);
        if (!$lock->get()) {
            return response()->json([
                'success'     => false,
                'message'     => 'Another property crawl/fetch task is currently running. Please wait a minute for it to finish.',
                'auth_status' => $authStatus,
            ], 429);
        }

        try {
            $scriptPath = base_path('scrapers/crawl_and_import.py');
            $pythonBinary = 'python3';
            exec("python3 --version 2>&1", $out, $code);
            if ($code !== 0) {
                $pythonBinary = 'python';
            }

            $escapedTarget = escapeshellarg($target);
            $escapedScript = escapeshellarg($scriptPath);
            $escapedPurpose = escapeshellarg($purpose ?: 'rent');
            $escapedType = escapeshellarg($propertyType ?: 'all');
            $escapedEngine = escapeshellarg($engine ?: 'playwright');
            $command = "{$pythonBinary} {$escapedScript} {$escapedTarget} {$count} {$escapedPurpose} {$escapedType} --json --engine={$escapedEngine} --page={$page}";

            // Free DB connection while Python is crawling external web pages
            try { \Illuminate\Support\Facades\DB::disconnect(); } catch (\Throwable $e) {}

            exec($command, $output, $returnCode);

            $rawOutput = implode("\n", (array) $output);
            $items = [];
            if ($returnCode === 0 && preg_match('/===JSON_START===\s*(.*?)\s*===JSON_END===/s', $rawOutput, $matches)) {
                $items = json_decode($matches[1], true);
            }

            if (empty($items) || !is_array($items)) {
                $startedAt = now()->subSeconds(2);

                // Shared hosting fallback using scrape:city
                \Illuminate\Support\Facades\Artisan::call('scrape:city', [
                    'city' => $url ?: $city,
                    '--count' => $count,
                    '--page' => $page,
                    '--auto-next' => $autoNextPage,
                    '--type' => $propertyType,
                    '--purpose' => $purpose,
                    '--create-accounts' => true,
                ]);

                try {
                    \Illuminate\Support\Facades\Artisan::call('properties:enrich-images');
                } catch (\Throwable $e) {}

                Cache::forget('home_featured_rentals');
                Cache::forget('home_featured_properties');
                Cache::forget('admin_dashboard_stats');

                $recentProperties = Property::where('created_at', '>=', $startedAt)
                    ->latest('id')
                    ->take($count)
                    ->get();

                $returnedProps = [];
                foreach ($recentProperties as $p) {
                    $firstImg = $p->primaryImage?->path ?? $p->images()->first()?->path;
                    $returnedProps[] = [
                        'id'            => $p->id,
                        'title'         => $p->title,
                        'price'         => $p->price,
                        'purpose'       => $p->purpose,
                        'type'          => $p->type,
                        'category'      => $p->category?->name ?? 'Property',
                        'locality'      => $p->locality,
                        'city'          => $p->location,
                        'state'         => $p->state,
                        'owner'         => $p->user?->name ?? 'Property Owner',
                        'contact_phone' => $p->contact_phone,
                        'thumbnail'     => $firstImg,
                        'images_count'  => $p->images()->count(),
                        'bedrooms'      => $p->bedrooms,
                        'bathrooms'     => $p->bathrooms,
                        'furnishing'    => $p->furnishing,
                        'area_sqft'     => $p->area_sqft,
                        'engine'        => $engine,
                        'view_url'      => route('properties.show', $p->slug),
                    ];
                }

                $recentAccounts = User::where('role', 'owner')
                    ->where('created_at', '>=', $startedAt)
                    ->take($count)
                    ->get(['id', 'name', 'email', 'phone', 'role'])
                    ->toArray();

                $newImportedCount = count($returnedProps);
                $isAlreadyScraped = ($newImportedCount === 0);

                return response()->json([
                    'success'              => true,
                    'message'              => $isAlreadyScraped
                        ? "Page {$page} was already scraped! Zero duplicates added."
                        : "Successfully fetched listings for " . ($url ?: $city) . " (Page {$page}): {$newImportedCount} newly posted.",
                    'url'                  => $target,
                    'engine'               => $engine,
                    'page_scraped'         => $page,
                    'next_page'            => $page + 1,
                    'already_scraped'      => $isAlreadyScraped,
                    'imported_count'       => $newImportedCount,
                    'duplicates_prevented' => $isAlreadyScraped ? $count : 0,
                    'accounts_created'     => count($recentAccounts),
                    'accounts'             => $recentAccounts,
                    'properties'           => $returnedProps,
                    'total_properties'     => Property::count(),
                    'latest_id'            => Property::latest('id')->first()?->id ?? 0,
                    'synced_at'            => now()->toDateTimeString(),
                    'auth_status'          => $authStatus,
                ]);
            }

            $adminUser = User::where('role', 'admin')->first() ?? User::first();
            $defaultUserId = $adminUser ? $adminUser->id : 1;

            $createdAccounts = [];
            $createdProperties = [];
            $duplicatesSkippedCount = 0;

            foreach ($items as $item) {
                if (empty($item['title'])) continue;

                // Precision duplicate check before creating accounts or listings
                $candidateCheck = [
                    'title'         => $item['title'],
                    'city'          => $item['city'] ?? $city,
                    'location'      => $item['city'] ?? $city,
                    'locality'      => $item['locality'] ?? '',
                    'price'         => $item['price'] ?? 25000,
                    'purpose'       => $item['purpose'] ?? $purpose,
                    'bedrooms'      => $item['bedrooms'] ?? 2,
                    'images'        => $item['images'] ?? [],
                    'contact_phone' => $item['contact_phone'] ?? null,
                ];

                $existing = Property::findDuplicate($candidateCheck);
                if ($existing) {
                    $duplicatesSkippedCount++;
                    // Enrich images if existing property has no photos
                    if ($existing->images()->count() === 0 && !empty($item['images']) && is_array($item['images'])) {
                        foreach ($item['images'] as $idx => $imgUrl) {
                            \App\Models\PropertyImage::create([
                                'property_id' => $existing->id,
                                'path'        => $imgUrl,
                                'is_primary'  => ($idx === 0),
                                'sort_order'  => $idx,
                            ]);
                        }
                    }
                    continue;
                }

                $propertyUserId = $defaultUserId;
                $ownerName = 'Admin';
                $sellerName = trim($item['seller_name'] ?? '');

                if ($createAccounts && !empty($sellerName) && strtolower($sellerName) !== 'property owner') {
                    $contactPhone = $item['contact_phone'] ?? null;
                    $company = $item['seller_company'] ?? null;
                    $photo = $item['seller_photo'] ?? null;

                    $phoneDigits = preg_replace('/\D/', '', (string) $contactPhone);
                    $suffix = !empty($phoneDigits) ? substr($phoneDigits, -4) : rand(1000, 9999);
                    $cleanSlug = Str::slug($sellerName) ?: 'owner';
                    $email = $cleanSlug . '.' . $suffix . '@unlockrentals.com';

                    $sellerUser = null;
                    if (!empty($contactPhone)) {
                        $sellerUser = User::where('phone', $contactPhone)->first();
                    }
                    if (!$sellerUser) {
                        $sellerUser = User::where('email', $email)->first();
                    }
                    if (!$sellerUser) {
                        $fullName = $sellerName . (!empty($company) && $company !== $sellerName ? " ({$company})" : "");
                        $sellerUser = User::create([
                            'name'              => $fullName,
                            'email'             => $email,
                            'phone'             => $contactPhone,
                            'password'          => \Illuminate\Support\Facades\Hash::make('Password@123'),
                            'role'              => 'owner',
                            'avatar'            => $photo ?: null,
                            'email_verified_at' => now(),
                            'phone_verified_at' => now(),
                        ]);
                        $createdAccounts[] = [
                            'id'    => $sellerUser->id,
                            'name'  => $sellerUser->name,
                            'email' => $sellerUser->email,
                            'phone' => $sellerUser->phone,
                            'role'  => $sellerUser->role,
                        ];
                    }
                    $propertyUserId = $sellerUser->id;
                    $ownerName = $sellerUser->name;
                }

                // Dynamically resolve category per listing
                $catName = $item['category_name'] ?? 'Flat / Apartment';
                $category = \App\Models\Category::where('name', $catName)
                    ->orWhere('name', 'like', "%{$catName}%")
                    ->first();
                if (!$category) {
                    $category = \App\Models\Category::where('slug', Str::slug($catName))->first();
                }
                if (!$category) {
                    $category = \App\Models\Category::where('name', 'like', '%Flat%')->orWhere('name', 'like', '%Apartment%')->first() ?? \App\Models\Category::first();
                }
                $itemCategoryId = $category ? $category->id : 1;

                $itemType = in_array($item['type'] ?? '', ['house', 'shop', 'pg-hostel', 'hotel', 'plot']) ? $item['type'] : 'house';
                $itemPurpose = in_array($item['purpose'] ?? '', ['rent', 'buy']) ? $item['purpose'] : 'rent';

                // Explicit PG & Hostel Detection
                $isPg = in_array(strtolower($itemType), ['pg', 'pg-hostel']) || 
                        in_array(strtolower($propertyType ?? ''), ['pg', 'pg-hostel']) || 
                        str_contains(strtolower($item['title'] ?? ''), 'pg') || 
                        str_contains(strtolower($item['title'] ?? ''), 'paying guest') ||
                        str_contains(strtolower($item['category_name'] ?? ''), 'pg');

                if ($isPg) {
                    $itemType = 'pg-hostel';
                    $itemPurpose = 'rent';
                    $pgCategory = \App\Models\Category::where('slug', 'pg-hostel')->orWhere('name', 'like', '%PG%')->first();
                    if ($pgCategory) {
                        $itemCategoryId = $pgCategory->id;
                        $category = $pgCategory;
                    }
                }

                $itemCity = Property::cleanUrlFromText($item['city'] ?? $city, true) ?: ($city ?: 'Delhi');
                $itemState = Property::cleanUrlFromText($item['state'] ?? 'India', false) ?: 'India';
                $itemLocality = Property::cleanUrlFromText($item['locality'] ?? $itemCity, false) ?: $itemCity;
                $itemTitle = Property::cleanUrlFromText($item['title'] ?? '', true) ?: ("{$item['bedrooms']} BHK Property in {$itemLocality}");
                $itemDesc = Property::cleanUrlFromText($item['description'] ?? ("{$itemTitle} in {$itemLocality}, {$itemCity}"), false);
                $itemAddress = Property::cleanUrlFromText($item['address'] ?? "{$itemLocality}, {$itemCity}", true) ?: "{$itemLocality}, {$itemCity}";

                $slug = Str::slug($itemTitle) . '-' . rand(10000, 99999);
                $prop = Property::create([
                    'user_id'       => $propertyUserId,
                    'category_id'   => $itemCategoryId,
                    'title'         => $itemTitle,
                    'slug'          => $slug,
                    'description'   => $itemDesc,
                    'type'          => $itemType,
                    'purpose'       => $itemPurpose,
                    'price'         => $item['price'] ?? 25000,
                    'price_period'  => 'month',
                    'state'         => $itemState,
                    'location'      => $itemCity,
                    'locality'      => $itemLocality,
                    'address'       => $itemAddress,
                    'contact_phone' => $item['contact_phone'] ?? '+91 98765 43210',
                    'bedrooms'      => $itemType === 'plot' ? 0 : ($item['bedrooms'] ?? 2),
                    'bathrooms'     => $itemType === 'plot' ? 0 : ($item['bathrooms'] ?? 1),
                    'area_sqft'     => $item['area_sqft'] ?? 800,
                    'furnishing'    => $itemType === 'plot' ? 'unfurnished' : (in_array(strtolower(trim((string)($item['furnishing'] ?? ''))), ['furnished', 'fully', 'fully-furnished', 'full']) ? 'fully-furnished' : (str_contains(strtolower(trim((string)($item['furnishing'] ?? ''))), 'semi') ? 'semi-furnished' : 'unfurnished')),
                    'status'        => $autoApprove ? 'approved' : 'pending',
                    'approved_at'   => $autoApprove ? now() : null,
                    'is_featured'   => false,
                    'is_booked'     => false,
                ]);

                if (!empty($item['images']) && is_array($item['images'])) {
                    foreach ($item['images'] as $idx => $imgUrl) {
                        \App\Models\PropertyImage::create([
                            'property_id' => $prop->id,
                            'path'        => $imgUrl,
                            'is_primary'  => ($idx === 0),
                            'sort_order'  => $idx,
                        ]);
                    }
                }

                $createdProperties[] = [
                    'id'            => $prop->id,
                    'title'         => $prop->title,
                    'price'         => $prop->price,
                    'purpose'       => $prop->purpose,
                    'type'          => $prop->type,
                    'category'      => $category?->name ?? 'Property',
                    'locality'      => $prop->locality,
                    'city'          => $prop->location,
                    'state'         => $prop->state,
                    'owner'         => $ownerName,
                    'contact_phone' => $prop->contact_phone,
                    'thumbnail'     => !empty($item['images']) ? $item['images'][0] : null,
                    'images_count'  => count($item['images'] ?? []),
                    'bedrooms'      => $prop->bedrooms,
                    'bathrooms'     => $prop->bathrooms,
                    'furnishing'    => $prop->furnishing,
                    'area_sqft'     => $prop->area_sqft,
                    'engine'        => $engine,
                    'view_url'      => route('properties.show', $prop->slug),
                ];
            }

            try {
                \Illuminate\Support\Facades\Artisan::call('properties:enrich-images');
            } catch (\Throwable $e) {}

            Cache::forget('home_featured_rentals');
            Cache::forget('home_featured_properties');
            Cache::forget('admin_dashboard_stats');

            $nextPage = $page + 1;
            return response()->json([
                'success'              => true,
                'message'              => "Fetched listings (Page {$page}): " . count($createdProperties) . " newly posted, " . $duplicatesSkippedCount . " duplicates skipped (prevented), " . count($createdAccounts) . " owner accounts linked.",
                'url'                  => $target,
                'engine'               => $engine,
                'page_scraped'         => $page,
                'next_page'            => $nextPage,
                'already_scraped'      => ($duplicatesSkippedCount > 0 && count($createdProperties) === 0),
                'imported_count'       => count($createdProperties),
                'duplicates_prevented' => $duplicatesSkippedCount,
                'accounts_created'     => count($createdAccounts),
                'accounts'             => $createdAccounts,
                'properties'           => $createdProperties,
                'total_properties'     => Property::count(),
                'latest_id'            => Property::latest('id')->first()?->id ?? 0,
                'synced_at'            => now()->toDateTimeString(),
                'auth_status'          => $authStatus,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success'     => false,
                'message'     => 'Error: ' . $e->getMessage(),
                'auth_status' => $authStatus ?? null,
            ], 500);
        } finally {
            optional($lock)->release();
            try { \Illuminate\Support\Facades\DB::disconnect(); } catch (\Throwable $e) {}
        }
    }

    /**
     * Terminate idle/sleeping MySQL connections to free up connection slots.
     */
    public function cleanupDbConnections(Request $request)
    {
        try {
            \Illuminate\Support\Facades\Artisan::call('db:cleanup-connections', [
                '--all-sleep' => true
            ]);
            $output = \Illuminate\Support\Facades\Artisan::output();
            return response()->json([
                'success' => true,
                'message' => 'Cleaned up sleeping DB connections successfully',
                'output' => $output
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API endpoint to automatically scrape and publish listings across all major cities.
     */
    public function apiScrapeAllCities(Request $request)
    {
        $request->merge([
            'city'    => 'all',
            'purpose' => $request->input('purpose', 'rent'),
            'engine'  => $request->input('engine', 'playwright'),
        ]);
        return $this->fetchAndPostFromUrl($request);
    }

    /**
     * Proper posting advertising form handler: creates owner account automatically and publishes property in their name.
     */
    public function postAdvertise(Request $request)
    {
        $request->validate([
            'behalf_mode'   => 'nullable|string|in:new,existing',
            'behalf_name'   => 'required_if:behalf_mode,new|nullable|string|max:255',
            'behalf_phone'  => 'required_if:behalf_mode,new|nullable|string|max:20',
            'behalf_email'  => 'nullable|string|email|max:255',
            'behalf_user_id'=> 'required_if:behalf_mode,existing|nullable|exists:users,id',
            'title'         => 'required|string|max:255',
            'purpose'       => 'required|string|in:rent,buy,sell',
            'type'          => 'required|string|in:house,shop,pg-hostel,hotel,plot',
            'price'         => 'required|numeric|min:0',
            'price_period'  => 'nullable|string|in:month,year',
            'location'      => 'required|string|max:255',
            'locality'      => 'required|string|max:255',
            'address'       => 'nullable|string|max:500',
            'bedrooms'      => 'nullable|integer|min:0|max:50',
            'bathrooms'     => 'nullable|integer|min:0|max:20',
            'area_sqft'     => 'nullable|integer|min:0',
            'furnishing'    => 'nullable|string|in:furnished,semi-furnished,unfurnished,fully-furnished',
            'description'   => 'nullable|string',
            'images'        => 'nullable|array|max:25',
            'images.*'      => 'image|mimes:jpeg,png,jpg,webp|max:12288',
            'is_featured'   => 'nullable|boolean',
        ]);

        try {
            $ownerUser = null;
            $behalfMode = $request->input('behalf_mode', 'new');
            $accountCreated = false;

            if ($behalfMode === 'new') {
                $name = trim($request->input('behalf_name'));
                $phone = trim($request->input('behalf_phone'));
                $email = trim($request->input('behalf_email', ''));

                if (!empty($phone)) {
                    $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
                    $ownerUser = User::where('phone', $phone)
                        ->orWhere('phone', 'LIKE', "%{$cleanPhone}%")
                        ->first();
                }

                if (!$ownerUser && !empty($email)) {
                    $ownerUser = User::where('email', $email)->first();
                }

                if (!$ownerUser) {
                    $cleanNum = preg_replace('/[^0-9]/', '', $phone) ?: rand(1000, 9999);
                    $cleanSlug = Str::slug($name) ?: 'owner';
                    $genEmail = !empty($email) ? $email : "{$cleanSlug}.{$cleanNum}@unlockrentals.com";
                    if (User::where('email', $genEmail)->exists()) {
                        $genEmail = "{$cleanSlug}.{$cleanNum}." . rand(100, 999) . "@unlockrentals.com";
                    }

                    $ownerUser = User::create([
                        'name'              => $name,
                        'phone'             => $phone,
                        'email'             => $genEmail,
                        'role'              => 'owner',
                        'password'          => \Illuminate\Support\Facades\Hash::make('Password@123'),
                        'phone_verified_at' => now(),
                        'email_verified_at' => now(),
                    ]);
                    $accountCreated = true;
                } else {
                    if ($ownerUser->role === 'tenant') {
                        $ownerUser->update(['role' => 'owner']);
                    }
                }
            } elseif ($behalfMode === 'existing') {
                $ownerUser = User::findOrFail($request->input('behalf_user_id'));
                if ($ownerUser->role === 'tenant') {
                    $ownerUser->update(['role' => 'owner']);
                }
            } else {
                $ownerUser = auth()->user();
            }

            // Determine Category
            $catName = match($request->type) {
                'shop' => 'Commercial',
                'plot' => 'Land',
                'pg-hostel' => 'PG',
                default => 'Apartment',
            };
            $category = \App\Models\Category::where('name', 'like', "%{$catName}%")->first() ?? \App\Models\Category::first();
            $categoryId = $category ? $category->id : 1;

            // Generate Description if needed
            $desc = trim((string)$request->input('description', ''));
            if (strlen($desc) < 20) {
                $bhk = $request->bedrooms ? "{$request->bedrooms} BHK " : "";
                $desc = "Spacious {$bhk}{$request->type} available for {$request->purpose} in {$request->locality}, {$request->location}. Area of " . ($request->area_sqft ?: 1000) . " sq.ft. with " . ($request->bathrooms ?: 1) . " bathrooms. Comes " . ($request->furnishing ?: 'semi-furnished') . ". Well-connected to transport, local markets, and key hubs. Contact {$ownerUser->name} at {$ownerUser->phone} for viewing and booking details.";
            }

            $slug = Str::slug($request->title) . '-' . rand(10000, 99999);
            $state = match(strtolower($request->location)) {
                'gurgaon', 'gurugram', 'faridabad' => 'Haryana',
                'noida', 'greater noida', 'ghaziabad' => 'Uttar Pradesh',
                'delhi', 'new delhi' => 'Delhi',
                'bangalore', 'bengaluru' => 'Karnataka',
                'mumbai', 'pune', 'navi mumbai', 'thane' => 'Maharashtra',
                'hyderabad' => 'Telangana',
                default => 'India',
            };

            $prop = Property::create([
                'user_id'       => $ownerUser->id,
                'category_id'   => $categoryId,
                'title'         => $request->title,
                'slug'          => $slug,
                'description'   => $desc,
                'type'          => $request->type,
                'purpose'       => $request->purpose,
                'price'         => $request->price,
                'price_period'  => $request->input('price_period', 'month'),
                'state'         => $state,
                'location'      => $request->location,
                'locality'      => $request->locality,
                'address'       => $request->address ?: "{$request->locality}, {$request->location}",
                'contact_phone' => $ownerUser->phone ?: ($request->behalf_phone ?: auth()->user()->phone),
                'bedrooms'      => (int) ($request->bedrooms ?? 2),
                'bathrooms'     => (int) ($request->bathrooms ?? 1),
                'area_sqft'     => (int) ($request->area_sqft ?? 900),
                'furnishing'    => $request->input('furnishing', 'semi-furnished'),
                'status'        => 'approved',
                'approved_at'   => now(),
                'is_featured'   => $request->boolean('is_featured', false),
                'is_booked'     => false,
            ]);

            // Handle image uploads
            $uploadedCount = 0;
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $idx => $imgFile) {
                    if ($imgFile->isValid()) {
                        $path = \App\Services\ImageOptimizer::optimizeAndStore($imgFile, 'properties/' . $prop->id);
                        \App\Models\PropertyImage::create([
                            'property_id' => $prop->id,
                            'path'        => $path,
                            'is_primary'  => ($idx === 0),
                            'sort_order'  => $idx,
                        ]);
                        $uploadedCount++;
                    }
                }
            }

            // If no images were uploaded, automatically enrich with verified photos
            if ($uploadedCount === 0) {
                try {
                    \Illuminate\Support\Facades\Artisan::call('properties:enrich-images');
                } catch (\Throwable $e) {}
            }

            Cache::forget('home_featured_rentals');
            Cache::forget('home_featured_properties');
            Cache::forget('admin_dashboard_stats');
            Cache::forget('approved_locations');

            $prop->load('images', 'user');

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success'         => true,
                    'message'         => "Advertisement successfully posted on behalf of {$ownerUser->name}!" . ($accountCreated ? " (Active Owner account created)" : ""),
                    'property'        => [
                        'id'           => $prop->id,
                        'title'        => $prop->title,
                        'price'        => $prop->price,
                        'locality'     => $prop->locality,
                        'location'     => $prop->location,
                        'images_count' => $prop->images->count(),
                        'owner_name'   => $ownerUser->name,
                        'owner_email'  => $ownerUser->email,
                        'owner_phone'  => $ownerUser->phone,
                        'view_url'     => route('properties.show', $prop->slug),
                        'edit_url'     => route('properties.edit', $prop->id),
                    ],
                    'account_created' => $accountCreated,
                    'owner'           => [
                        'id'    => $ownerUser->id,
                        'name'  => $ownerUser->name,
                        'email' => $ownerUser->email,
                        'phone' => $ownerUser->phone,
                        'role'  => $ownerUser->role,
                    ],
                    'total_properties'=> Property::count(),
                ]);
            }

            return redirect()->route('admin.properties')->with('success', "🎉 Advertisement posted successfully on behalf of {$ownerUser->name}!");
        } catch (\Throwable $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage(),
                ], 422);
            }
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
