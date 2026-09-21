<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PushNotification;
use App\Models\PushSubscription;
use App\Models\User;
use App\Services\PushNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class PushNotificationController extends Controller
{
    protected PushNotificationService $pushService;

    public function __construct(PushNotificationService $pushService)
    {
        $this->pushService = $pushService;
    }

    /**
     * Auto-create push notification tables if they do not exist yet.
     */
    protected function ensureTablesExist(): void
    {
        try {
            if (!Schema::hasTable('push_subscriptions')) {
                Schema::create('push_subscriptions', function (Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('user_id')->nullable()->index();
                    $table->text('endpoint');
                    $table->string('endpoint_hash', 64)->unique()->index();
                    $table->text('public_key')->nullable();
                    $table->text('auth_token')->nullable();
                    $table->string('fcm_token')->nullable()->index();
                    $table->string('device_type', 20)->default('web');
                    $table->text('user_agent')->nullable();
                    $table->string('ip_address', 45)->nullable();
                    $table->timestamp('last_active_at')->nullable();
                    $table->timestamps();
                });
            }

            if (!Schema::hasTable('push_notifications')) {
                Schema::create('push_notifications', function (Blueprint $table) {
                    $table->id();
                    $table->string('title');
                    $table->text('body');
                    $table->string('icon')->nullable();
                    $table->string('image_url')->nullable();
                    $table->string('action_url')->nullable();
                    $table->string('channel', 20)->default('both');
                    $table->string('target_type', 30)->default('all');
                    $table->string('target_value')->nullable();
                    $table->unsignedInteger('sent_count')->default(0);
                    $table->unsignedInteger('failed_count')->default(0);
                    $table->string('status', 20)->default('sent');
                    $table->unsignedBigInteger('sent_by')->nullable()->index();
                    $table->timestamps();
                });
            } else {
                if (!Schema::hasColumn('push_notifications', 'channel')) {
                    Schema::table('push_notifications', function (Blueprint $table) {
                        $table->string('channel', 20)->default('both')->after('action_url');
                    });
                }
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Push tables auto-creation warning: ' . $e->getMessage());
        }
    }

    /**
     * Display push notification composer and campaign history.
     */
    public function index(Request $request)
    {
        $this->ensureTablesExist();

        try {
            $subscribersCount       = PushSubscription::active()->count();
            $webSubscribersCount    = PushSubscription::active()->where('device_type', 'web')->count();
            $mobileSubscribersCount = PushSubscription::active()->whereIn('device_type', ['android', 'ios'])->count();
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Push subscriber stats error: ' . $e->getMessage());
            $subscribersCount       = 0;
            $webSubscribersCount    = 0;
            $mobileSubscribersCount = 0;
        }

        try {
            $registeredUsersCount = User::count();
            $users = User::select('id', 'name', 'email', 'phone', 'role')
                ->latest()
                ->take(100)
                ->get();
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Push users query error: ' . $e->getMessage());
            $registeredUsersCount = 0;
            $users = collect();
        }

        try {
            $campaigns = PushNotification::with('sender')
                ->latest()
                ->paginate(15);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Push campaigns query error: ' . $e->getMessage());
            $campaigns = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15);
        }

        return view('admin.push-notifications.index', compact(
            'subscribersCount',
            'webSubscribersCount',
            'mobileSubscribersCount',
            'registeredUsersCount',
            'users',
            'campaigns'
        ));
    }

    /**
     * Send custom push notification.
     */
    public function send(Request $request)
    {
        $this->ensureTablesExist();

        $validated = $request->validate([
            'title'        => 'required|string|max:120',
            'body'         => 'required|string|max:500',
            'action_url'   => 'nullable|string|max:500',
            'channel'      => 'nullable|in:both,web,app',
            'image_url'    => 'nullable|string|max:500',
            'image_file'   => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'icon'         => 'nullable|string|max:500',
            'target_type'  => 'required|in:all,role,specific_user',
            'target_value' => 'nullable|string|max:255',
        ]);

        // Direct file upload handling
        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('push-banners', 'public');
            $validated['image_url'] = asset('storage/' . $path);
        }

        $validated['channel'] = $validated['channel'] ?? 'both';

        // Auto-resolve role or user target value if submitted via subfields
        if ($validated['target_type'] === 'role' && empty($validated['target_value'])) {
            $validated['target_value'] = $request->input('target_value_role') ?: 'tenant';
        } elseif ($validated['target_type'] === 'specific_user' && empty($validated['target_value'])) {
            $validated['target_value'] = $request->input('target_value_user');
        }

        if ($validated['target_type'] === 'specific_user' && empty($validated['target_value'])) {
            return back()->with('error', 'Please select a specific user to target.')->withInput();
        }

        if ($validated['target_type'] === 'role' && empty($validated['target_value'])) {
            return back()->with('error', 'Please select a user role to target.')->withInput();
        }

        try {
            $campaign = $this->pushService->dispatch($validated);

            $channelText = match ($campaign->channel) {
                'web'   => 'Web Browsers',
                'app'   => 'Mobile App',
                default => 'Web & Mobile App',
            };

        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Push send error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->with('error', 'Failed to dispatch push notification: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Delete campaign history record.
     */
    public function destroy(PushNotification $pushNotification)
    {
        $this->ensureTablesExist();
        $pushNotification->delete();

        return back()->with('success', 'Notification record deleted successfully.');
    }

    /**
     * Client-side endpoint for registering Web Push / FCM subscription tokens.
     */
    public function subscribe(Request $request)
    {
        $this->ensureTablesExist();

        $request->validate([
            'endpoint' => 'required|string',
        ]);

        $subscription = PushSubscription::registerSubscription($request->all(), auth()->id());

        return response()->json([
            'success' => true,
            'message' => 'Subscription registered successfully.',
            'id'      => $subscription?->id,
        ]);
    }
}
