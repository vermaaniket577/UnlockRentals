<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadFollowUp;
use App\Models\CrmAuditLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowUpController extends Controller
{
    /**
     * Display the follow-up management workspace.
     */
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'today');
        $now = Carbon::now();
        $todayStart = Carbon::today()->startOfDay();
        $todayEnd = Carbon::today()->endOfDay();

        $query = LeadFollowUp::with(['lead.assignedTo', 'assignedTo'])->latest('scheduled_at');

        if ($tab === 'today') {
            $query->where('status', 'pending')
                  ->whereBetween('scheduled_at', [$todayStart, $todayEnd]);
        } elseif ($tab === 'overdue') {
            $query->where('status', 'pending')
                  ->where('scheduled_at', '<', $todayStart);
        } elseif ($tab === 'upcoming') {
            $query->where('status', 'pending')
                  ->where('scheduled_at', '>', $todayEnd);
        } elseif ($tab === 'completed') {
            $query->where('status', 'completed');
        }

        // Filter by assigned agent
        if ($request->filled('agent_id') && $request->agent_id !== 'all') {
            $query->where('assigned_to', $request->agent_id);
        }

        // Tab counts
        $countToday = LeadFollowUp::where('status', 'pending')->whereBetween('scheduled_at', [$todayStart, $todayEnd])->count();
        $countOverdue = LeadFollowUp::where('status', 'pending')->where('scheduled_at', '<', $todayStart)->count();
        $countUpcoming = LeadFollowUp::where('status', 'pending')->where('scheduled_at', '>', $todayEnd)->count();
        $countCompleted = LeadFollowUp::where('status', 'completed')->count();

        $followUps = $query->paginate(25)->withQueryString();

        return view('admin.follow-ups.index', compact(
            'followUps',
            'tab',
            'countToday',
            'countOverdue',
            'countUpcoming',
            'countCompleted'
        ));
    }

    /**
     * Schedule a new follow-up for a lead.
     */
    public function store(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'scheduled_at' => 'required|date|after:now - 1 hour',
            'follow_up_type' => 'required|in:call,whatsapp,site_visit,meeting,email',
            'notes' => 'nullable|string|max:1000',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $followUp = LeadFollowUp::create([
            'lead_id' => $lead->id,
            'assigned_to' => $validated['assigned_to'] ?? Auth::id(),
            'scheduled_at' => Carbon::parse($validated['scheduled_at']),
            'follow_up_type' => $validated['follow_up_type'],
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
        ]);

        CrmAuditLog::create([
            'lead_id' => $lead->id,
            'user_id' => Auth::id(),
            'action' => 'follow_up_scheduled',
            'description' => "Scheduled " . strtoupper($followUp->follow_up_type) . " follow-up for " . $followUp->scheduled_at->format('d M Y, h:i A'),
        ]);

        return back()->with('success', 'Follow-up scheduled successfully.');
    }

    /**
     * Mark a follow-up as completed with outcome notes.
     */
    public function complete(Request $request, LeadFollowUp $followUp)
    {
        $validated = $request->validate([
            'outcome' => 'nullable|string|max:1000',
            'update_lead_status' => 'nullable|in:contacted,interested,scheduled_visit,negotiation,converted,lost',
        ]);

        $followUp->update([
            'status' => 'completed',
            'completed_at' => Carbon::now(),
            'notes' => ($followUp->notes ? $followUp->notes . "\n\n" : '') . "[Outcome on " . Carbon::now()->format('d M Y, h:i A') . "]: " . ($validated['outcome'] ?? 'Completed'),
        ]);

        if (!empty($validated['update_lead_status'])) {
            $followUp->lead->update([
                'lead_status' => $validated['update_lead_status'],
            ]);
        }

        CrmAuditLog::create([
            'lead_id' => $followUp->lead_id,
            'user_id' => Auth::id(),
            'action' => 'follow_up_completed',
            'description' => "Marked " . strtoupper($followUp->follow_up_type) . " follow-up completed. Outcome: " . ($validated['outcome'] ?? 'None'),
        ]);

        return back()->with('success', 'Follow-up marked as completed.');
    }

    /**
     * Reschedule or update a follow-up.
     */
    public function update(Request $request, LeadFollowUp $followUp)
    {
        $validated = $request->validate([
            'scheduled_at' => 'required|date',
            'follow_up_type' => 'required|in:call,whatsapp,site_visit,meeting,email',
            'notes' => 'nullable|string|max:1000',
        ]);

        $oldDate = $followUp->scheduled_at->format('d M Y, h:i A');

        $followUp->update([
            'scheduled_at' => Carbon::parse($validated['scheduled_at']),
            'follow_up_type' => $validated['follow_up_type'],
            'notes' => $validated['notes'] ?? $followUp->notes,
        ]);

        CrmAuditLog::create([
            'lead_id' => $followUp->lead_id,
            'user_id' => Auth::id(),
            'action' => 'follow_up_rescheduled',
            'description' => "Follow-up rescheduled from {$oldDate} to " . $followUp->scheduled_at->format('d M Y, h:i A'),
        ]);

        return back()->with('success', 'Follow-up updated successfully.');
    }
}
