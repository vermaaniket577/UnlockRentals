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
use App\Models\Blog;
use App\Mail\SubscriptionActivated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
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
        $request->validate([
            'gst_rate' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

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

        Cache::forget('indian_location_data');

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

        Cache::forget('indian_location_data');

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

        Cache::forget('indian_location_data');

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

        Cache::forget('indian_location_data');

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

        Cache::forget('indian_location_data');

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

        Cache::forget('indian_location_data');

        return redirect()->route('admin.locations', [
            'state_id' => $district ? $district->state_id : null,
            'district_id' => $district ? $district->id : null
        ])->with('success', "Locality '{$name}' deleted successfully.");
    }

    // ─── BLOG POST MANAGEMENT ──────────────────────

    /**
     * Display a listing of blog posts with summary KPIs & filters.
     */
    public function blogs(Request $request)
    {
        if (!\Illuminate\Support\Facades\Schema::hasTable('blogs')) {
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

        $blogs = $query->latest('updated_at')->paginate(12)->withQueryString();
        $categories = Blog::select('category')->distinct()->pluck('category')->filter()->values();

        return view('admin.blogs.index', compact('blogs', 'stats', 'categories'));
    }

    /**
     * Show the form for creating a new blog post.
     */
    public function createBlog()
    {
        if (!\Illuminate\Support\Facades\Schema::hasTable('blogs')) {
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

        // Handle Cover Image
        if ($request->hasFile('image')) {
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
        } elseif ($request->filled('image_base64') && str_starts_with($request->image_base64, 'data:image/')) {
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
        } elseif (!empty($data['image_url'])) {
            $url = trim($data['image_url']);
            if (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://') && !str_starts_with($url, '//')) {
                $url = 'https://' . $url;
            }
            $data['image'] = $url;
        }
        unset($data['image_url']);
        unset($data['image_base64']);

        // Handle Author Avatar
        if ($request->hasFile('author_avatar')) {
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
        } elseif ($request->filled('author_avatar_base64') && str_starts_with($request->author_avatar_base64, 'data:image/')) {
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
        }
        unset($data['author_avatar_base64']);

        $data['user_id'] = auth()->id();
        $data['is_published'] = $request->boolean('is_published');
        $data['is_featured'] = $request->boolean('is_featured');

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

        // Handle cover image
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

            $data['image'] = $path;
        } elseif ($request->filled('image_base64') && str_starts_with($request->image_base64, 'data:image/')) {
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
        } elseif (!empty($data['image_url'])) {
            $url = trim($data['image_url']);
            if (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://') && !str_starts_with($url, '//')) {
                $url = 'https://' . $url;
            }
            $data['image'] = $url;
        }
        unset($data['image_url']);
        unset($data['image_base64']);

        // Handle author avatar
        if ($request->hasFile('author_avatar')) {
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
        } elseif ($request->filled('author_avatar_base64') && str_starts_with($request->author_avatar_base64, 'data:image/')) {
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
        }
        unset($data['author_avatar_base64']);

        $data['is_published'] = $request->boolean('is_published');
        $data['is_featured'] = $request->boolean('is_featured');

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

        Cache::forget('home_blogs');
        Cache::forget('sitemap_blogs');

        return redirect()->route('admin.blogs.index')
            ->with('success', "Blog post \"{$blog->title}\" updated successfully.");
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
}
