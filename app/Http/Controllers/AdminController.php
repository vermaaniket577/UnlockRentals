<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\User;
use App\Models\Inquiry;
use App\Models\Setting;
use App\Models\Plan;
use App\Models\UserPlan;
use App\Models\Feedback;
use App\Models\ProcessStep;
use App\Mail\SubscriptionActivated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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
            ];
        });

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
        $settings = Cache::get('site_settings', []);
        $paymentGateways = Setting::paymentGateways();
        $activePaymentGatewayId = Setting::get('active_payment_gateway_id', $paymentGateways[0]['id'] ?? null);

        return view('admin.settings', compact('settings', 'paymentGateways', 'activePaymentGatewayId'));
    }

    /**
     * Update settings.
     */
    public function updateSettings(Request $request)
    {
        $data = $request->except('_token', 'payment_gateways', 'active_payment_gateway_id');
        
        // Handle checkboxes (if they aren't in request, they should be '0')
        $checkboxes = ['chatbot_enabled', 'feedback_enabled', 'bypass_property_approval'];
        foreach($checkboxes as $box) {
            if(!$request->has($box)) $data[$box] = '0';
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
     * List all callback requests.
     */
    public function callbacks()
    {
        $callbacks = \App\Models\CallbackRequest::with('user')->latest()->paginate(20);
        return view('admin.callbacks', compact('callbacks'));
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
            'is_private' => 'boolean',
            'image' => 'nullable|image|max:2048',
            'sort_order' => 'integer',
        ]);

        if (!empty($data['features'])) {
            $data['features'] = array_filter(array_map('trim', explode("\n", $data['features'])));
        }

        $data['is_active'] = $request->has('is_active');
        $data['is_private'] = $request->has('is_private');

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('plans', 'public');
        }

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
            'is_private' => 'boolean',
            'image' => 'nullable|image|max:2048',
            'sort_order' => 'integer',
        ]);

        if (!empty($data['features'])) {
            $data['features'] = array_filter(array_map('trim', explode("\n", $data['features'])));
        }

        $data['is_active'] = $request->has('is_active');
        $data['is_private'] = $request->has('is_private');

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('plans', 'public');
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
            'discounted_price' => 'nullable|numeric|min:0',
        ]);

        $user = User::findOrFail($request->user_id);
        $plan = Plan::findOrFail($request->plan_id);

        if ($request->assign_type === 'custom_offer') {
            \App\Models\PrivateUserOffer::updateOrCreate(
                ['user_id' => $user->id, 'plan_id' => $plan->id],
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

        $userPlan = UserPlan::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'status' => 'approved',
            'payment_reference' => 'MANUAL_ASSIGNMENT_' . strtoupper(\Illuminate\Support\Str::random(6)),
            'amount_paid' => 0,
            'approved_at' => now(),
            'expires_at' => now()->addDays($plan->duration_days),
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
        $userPlan->update([
            'status' => 'approved',
            'approved_at' => now(),
            'expires_at' => now()->addDays($userPlan->plan->duration_days),
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
}
