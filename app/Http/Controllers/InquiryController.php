<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Models\Property;
use App\Http\Requests\StoreInquiryRequest;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    /**
     * Store a new inquiry.
     */
    public function store(StoreInquiryRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        Inquiry::create($data);

        return redirect()->back()
            ->with('success', 'Your inquiry has been sent to the property owner!');
    }

    /**
     * Display received inquiries for the owner.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        $inquiries = Inquiry::whereHas('property', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->with(['property.primaryImage', 'user'])
            ->latest()
            ->paginate(15);

        return view('dashboard.inquiries', compact('inquiries'));
    }

    /**
     * Show a specific inquiry and mark as read.
     */
    public function show(Inquiry $inquiry)
    {
        // Ensure the user owns the property this inquiry is about
        if ($inquiry->property->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        if ($inquiry->status === 'unread') {
            $inquiry->update(['status' => 'read']);
        }

        $inquiry->load(['property.images', 'user']);

        return view('dashboard.inquiry-show', compact('inquiry'));
    }
}
