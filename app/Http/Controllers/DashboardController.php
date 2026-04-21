<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Inquiry;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the role-based dashboard.
     */
    public function index()
    {
        $user = auth()->user();

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

            return view('dashboard.owner', compact('properties', 'totalInquiries', 'unreadInquiries'));
        }

        if ($user->isTenant()) {
            $inquiries = $user->inquiries()
                ->with('property.primaryImage')
                ->latest()
                ->take(10)
                ->get();

            return view('dashboard.tenant', compact('inquiries'));
        }

        // Admin redirect
        return redirect()->route('admin.dashboard');
    }
}
