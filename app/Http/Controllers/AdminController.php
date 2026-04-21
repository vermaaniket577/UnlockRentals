<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\User;
use App\Models\Inquiry;
use App\Models\Setting;
use App\Models\Plan;
use App\Models\UserPlan;
use App\Models\Feedback;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Show the admin dashboard.
     */
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_owners' => User::where('role', 'owner')->count(),
            'total_tenants' => User::where('role', 'tenant')->count(),
            'total_properties' => Property::count(),
            'pending_properties' => Property::pending()->count(),
            'approved_properties' => Property::approved()->count(),
            'total_inquiries' => Inquiry::count(),
            'unread_inquiries' => Inquiry::unread()->count(),
            'total_plans' => Plan::count(),
            'pending_subscriptions' => UserPlan::pending()->count(),
            'active_subscriptions' => UserPlan::active()->count(),
            'total_feedback' => Feedback::count(),
            'new_feedback' => Feedback::where('status', 'new')->count(),
        ];

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

        return view('admin.dashboard', compact('stats', 'pendingProperties', 'pendingSubscriptions'));
    }

    /**
     * Show all properties for admin review.
     */
    public function properties(Request $request)
    {
        $query = Property::with(['owner', 'primaryImage', 'category']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $properties = $query->latest()->paginate(15);

        return view('admin.properties', compact('properties'));
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

        return redirect()->back()
            ->with('success', "Property \"{$property->title}\" has been rejected.");
    }

    /**
     * Show all users.
     */
    public function users(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->withCount('properties')
            ->latest()
            ->paginate(15);

        return view('admin.users', compact('users'));
    }

    /**
     * Show settings page for site content and social media management.
     */
    public function settings()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('admin.settings', compact('settings'));
    }

    /**
     * Update settings.
     */
    public function updateSettings(Request $request)
    {
        $data = $request->except('_token');
        
        // Handle checkboxes (if they aren't in request, they should be '0')
        $checkboxes = ['chatbot_enabled', 'feedback_enabled'];
        foreach($checkboxes as $box) {
            if(!$request->has($box)) $data[$box] = '0';
        }

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

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
     * List all chatbot conversations.
     */
    public function chats()
    {
        $chats = \App\Models\ChatbotMessage::with('user')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('session_id');

        return view('admin.chats', compact('chats'));
    }

    /**
     * List all callback requests.
     */
    public function callbacks()
    {
        $callbacks = \App\Models\CallbackRequest::with('user')->latest()->paginate(20);
        return view('admin.callbacks', compact('callbacks'));
    }

    // ─── PLAN MANAGEMENT ────────────────────────

    /**
     * List all plans.
     */
    public function plans()
    {
        $plans = Plan::orderBy('sort_order')->get();
        return view('admin.plans', compact('plans'));
    }

    /**
     * Show create plan form.
     */
    public function createPlan()
    {
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
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'contact_limit' => 'required|integer|min:1',
            'features' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        if (!empty($data['features'])) {
            $data['features'] = array_filter(array_map('trim', explode("\n", $data['features'])));
        }

        $data['is_active'] = $request->has('is_active');

        Plan::create($data);

        return redirect()->route('admin.plans')->with('success', 'Plan created successfully.');
    }

    /**
     * Show edit plan form.
     */
    public function editPlan(Plan $plan)
    {
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
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'contact_limit' => 'required|integer|min:1',
            'features' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        if (!empty($data['features'])) {
            $data['features'] = array_filter(array_map('trim', explode("\n", $data['features'])));
        }

        $data['is_active'] = $request->has('is_active');

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
     * Approve a user's plan subscription.
     */
    public function approveSubscription(UserPlan $userPlan)
    {
        $userPlan->update([
            'status' => 'approved',
            'approved_at' => now(),
            'expires_at' => now()->addDays($userPlan->plan->duration_days),
        ]);

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
}
