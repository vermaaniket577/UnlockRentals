<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PushNotification;
use App\Models\PushSubscription;
use App\Models\User;
use App\Services\PushNotificationService;
use Illuminate\Http\Request;

class PushNotificationController extends Controller
{
    protected PushNotificationService $pushService;

    public function __construct(PushNotificationService $pushService)
    {
        $this->pushService = $pushService;
    }

    /**
     * Display push notification composer and campaign history.
     */
    public function index(Request $request)
    {
        $subscribersCount = PushSubscription::active()->count();
        $webSubscribersCount = PushSubscription::active()->where('device_type', 'web')->count();
        $mobileSubscribersCount = PushSubscription::active()->whereIn('device_type', ['android', 'ios'])->count();
        $registeredUsersCount = User::count();

        // Recent users for targeting specific users
        $users = User::select('id', 'name', 'email', 'phone', 'role')
            ->latest()
            ->take(100)
            ->get();

        // Campaign history
        $campaigns = PushNotification::with('sender')
            ->latest()
            ->paginate(15);

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
        $validated = $request->validate([
            'title'        => 'required|string|max:120',
            'body'         => 'required|string|max:500',
            'action_url'   => 'nullable|string|max:500',
            'image_url'    => 'nullable|url|max:500',
            'icon'         => 'nullable|string|max:500',
            'target_type'  => 'required|in:all,role,specific_user',
            'target_value' => 'nullable|string|max:255',
        ]);

        if ($validated['target_type'] === 'specific_user' && empty($validated['target_value'])) {
            return back()->with('error', 'Please select a specific user to target.')->withInput();
        }

        if ($validated['target_type'] === 'role' && empty($validated['target_value'])) {
            return back()->with('error', 'Please select a user role to target.')->withInput();
        }

        try {
            $campaign = $this->pushService->dispatch($validated);

            return back()->with('success', "Push notification \"{$campaign->title}\" dispatched successfully to {$campaign->audience_label}!");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to dispatch push notification: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Delete campaign history record.
     */
    public function destroy(PushNotification $pushNotification)
    {
        $pushNotification->delete();

        return back()->with('success', 'Notification record deleted successfully.');
    }

    /**
     * Client-side endpoint for registering Web Push / FCM subscription tokens.
     */
    public function subscribe(Request $request)
    {
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
