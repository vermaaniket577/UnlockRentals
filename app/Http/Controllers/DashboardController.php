<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Inquiry;
use App\Models\VisitBooking;
use App\Models\ContactView;
use App\Models\UserPlan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the role-based dashboard.
     */
    public function index()
    {
        $user = auth()->user();

        // Get active plan IDs for this user (so we can hide offers for plans they already own)
        $activePlanIds = \App\Models\UserPlan::where('user_id', $user->id)
            ->where('status', 'approved')
            ->where(function($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->pluck('plan_id')
            ->toArray();

        $privateOffers = \App\Models\PrivateUserOffer::with('plan')
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->where(function($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->whereNotIn('plan_id', $activePlanIds)
            ->latest()
            ->get();

        if ($user->isOwner()) {
            $properties = $user->properties()
                ->with('primaryImage')
                ->latest()
                ->paginate(10);

            $totalInquiries = Inquiry::whereHas('property', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count();

            $unreadInquiries = Inquiry::whereHas('property', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->unread()->count();

            return view('dashboard.owner', compact('properties', 'totalInquiries', 'unreadInquiries', 'privateOffers'));
        }

        if ($user->isTenant()) {
            $inquiries = $user->inquiries()
                ->with('property.primaryImage')
                ->latest()
                ->take(10)
                ->get();

            // Stats for dashboard cards
            $totalInquiries    = $user->inquiries()->count();
            $repliedInquiries  = $user->inquiries()->where('status', 'replied')->count();
            $unreadInquiries   = $user->inquiries()->where('status', 'unread')->count();
            $savedProperties   = ContactView::where('user_id', $user->id)->count();
            $recentVisits      = VisitBooking::where('user_id', $user->id)->count();
            $activePlan        = $user->activePlan();

            // Recommended: 3 newest approved properties (excluding already-inquired)
            $inquiredPropertyIds = $user->inquiries()->pluck('property_id');
            $recommendedProperties = Property::with('primaryImage')
                ->where('status', 'approved')
                ->whereNotIn('id', $inquiredPropertyIds)
                ->latest()
                ->take(3)
                ->get();

            return view('dashboard.tenant', compact(
                'inquiries',
                'privateOffers',
                'totalInquiries',
                'repliedInquiries',
                'unreadInquiries',
                'savedProperties',
                'recentVisits',
                'activePlan',
                'recommendedProperties'
            ));
        }

        // Admin redirect
        return redirect()->route('admin.dashboard');
    }

    /**
     * Show the billing and transaction history for the authenticated user.
     */
    public function billingHistory()
    {
        $user = auth()->user();
        $userPlans = $user->userPlans()
            ->with('plan')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('dashboard.billing_history', compact('userPlans'));
    }

    /**
     * Show a detailed, printable invoice for a user plan subscription.
     */
    public function invoice(UserPlan $userPlan)
    {
        if (auth()->id() !== $userPlan->user_id && !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access to invoice.');
        }

        $userPlan->load(['user', 'plan']);
        return view('dashboard.invoice', compact('userPlan'));
    }

    /**
     * Update the authenticated user's profile info.
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
        ]);

        $user->update([
            'name' => $data['name'],
            'phone' => $data['phone'],
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully!',
                'user' => [
                    'name' => $user->name,
                    'phone' => $user->phone,
                ]
            ]);
        }

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
}

