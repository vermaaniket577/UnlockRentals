<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadFollowUp;
use App\Models\CommunicationLog;
use App\Models\CrmAuditLog;
use App\Models\Property;
use App\Models\User;
use App\Services\WhatsApp\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LeadCrmController extends Controller
{
    /**
     * Display the Leads CRM pipeline and list.
     */
    public function index(Request $request)
    {
        // Proactively clean and merge any duplicate phone records
        Lead::cleanDuplicates();

        $query = Lead::with(['assignedTo', 'property', 'visitor', 'latestFollowUp']);

        // 1. Category Tab Filter (all, admission, property)
        if ($request->filled('category') && $request->category !== 'all') {
            if ($request->category === 'admission') {
                $query->where(function ($q) {
                    $q->where('lead_source', 'like', '%admission%')
                      ->orWhere('lead_source', 'like', '%anushram%')
                      ->orWhere('property_type', 'like', '%admission%')
                      ->orWhere('message', 'like', '%admission%')
                      ->orWhere('message', 'like', '%course%')
                      ->orWhere('message', 'like', '%phd%')
                      ->orWhere('notes', 'like', '%admission%');
                });
            } elseif ($request->category === 'property') {
                $query->where(function ($q) {
                    $q->where(function ($sub) {
                        $sub->where('lead_source', 'not like', '%admission%')
                            ->where('lead_source', 'not like', '%anushram%')
                            ->where('property_type', 'not like', '%admission%');
                    })->orWhereNull('lead_source');
                });
            }
        }

        // 2. Status Filter
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('lead_status', $request->status);
        }

        // 3. Intent Filter (maps to purpose column or admission)
        if ($request->filled('intent') && $request->intent !== 'all') {
            if (in_array($request->intent, ['rent', 'buy', 'sell'])) {
                $query->where('purpose', $request->intent);
            } elseif ($request->intent === 'admission') {
                $query->where(function ($q) {
                    $q->where('lead_source', 'like', '%admission%')
                      ->orWhere('lead_source', 'like', '%anushram%')
                      ->orWhere('property_type', 'like', '%admission%')
                      ->orWhere('message', 'like', '%admission%')
                      ->orWhere('message', 'like', '%course%');
                });
            }
        }

        // 4. Source Filter (maps to actual lead_source column)
        if ($request->filled('source') && $request->source !== 'all') {
            $query->where('lead_source', 'like', '%' . $request->source . '%');
        }

        // 5. Assigned Agent Filter
        if ($request->filled('assigned_to') && $request->assigned_to !== 'all') {
            if ($request->assigned_to === 'unassigned') {
                $query->whereNull('assigned_to');
            } else {
                $query->where('assigned_to', $request->assigned_to);
            }
        }

        // 6. Search across lead name, mobile, email, message, notes, city, source
        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('mobile', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('message', 'like', "%{$s}%")
                  ->orWhere('notes', 'like', "%{$s}%")
                  ->orWhere('lead_source', 'like', "%{$s}%")
                  ->orWhere('preferred_city', 'like', "%{$s}%")
                  ->orWhere('preferred_locality', 'like', "%{$s}%");
            });
        }

        // 7. Sorting: Default to Latest Created & Highest ID at top
        $sort = $request->input('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc')->orderBy('id', 'asc');
                break;
            case 'recently_updated':
                $query->orderByDesc('updated_at')->orderByDesc('id');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'highest_score':
                $query->orderByDesc('engagement_score')->orderByDesc('id');
                break;
            case 'latest':
            default:
                $query->orderByDesc('created_at')->orderByDesc('id');
                break;
        }

        // Summary KPI counts
        $totalLeadsCount = Lead::count();
        $admissionLeadsCount = Lead::where(function ($q) {
            $q->where('lead_source', 'like', '%admission%')
              ->orWhere('lead_source', 'like', '%anushram%')
              ->orWhere('property_type', 'like', '%admission%')
              ->orWhere('message', 'like', '%admission%')
              ->orWhere('message', 'like', '%course%')
              ->orWhere('notes', 'like', '%admission%');
        })->count();
        $newTodayCount = Lead::whereDate('created_at', Carbon::today())->count();
        $activeInProgressCount = Lead::whereIn('lead_status', ['contacted', 'interested', 'scheduled_visit', 'negotiation'])->count();
        $convertedCount = Lead::where('lead_status', 'converted')->count();
        $overdueFollowUpsCount = LeadFollowUp::where('status', 'pending')
            ->where('scheduled_at', '<', Carbon::now())
            ->count();

        $leads = $query->paginate(20)->withQueryString();

        // Staff members for assignment dropdown
        $staffUsers = User::whereIn('role', ['admin', 'owner'])->orderBy('name')->get();

        return view('admin.leads.index', compact(
            'leads',
            'totalLeadsCount',
            'admissionLeadsCount',
            'newTodayCount',
            'activeInProgressCount',
            'convertedCount',
            'overdueFollowUpsCount',
            'staffUsers'
        ));
    }

    /**
     * Display a single lead's full profile and CRM workspace.
     */
    public function show(Lead $lead)
    {
        $lead->load([
            'assignedTo',
            'property.primaryImage',
            'visitor.sessions',
            'visitor.events' => function ($q) {
                $q->with('property')->latest()->take(30);
            },
            'followUps.assignedTo',
            'communicationLogs' => function ($q) {
                $q->latest();
            },
            'auditLogs.user'
        ]);

        $staffUsers = User::whereIn('role', ['admin', 'owner'])->orderBy('name')->get();

        // Find matching properties based on lead preferences
        $matchingPropertiesQuery = Property::approved()->with('primaryImage')->latest();

        if ($lead->intent === 'buy') {
            $matchingPropertiesQuery->where('purpose', 'sale');
        } elseif ($lead->intent === 'rent') {
            $matchingPropertiesQuery->where('purpose', 'rent');
        }

        if ($lead->budget_max && $lead->budget_max > 0) {
            $matchingPropertiesQuery->where('price', '<=', $lead->budget_max * 1.15); // +15% tolerance
        }

        if ($lead->preferred_city) {
            $matchingPropertiesQuery->where(function ($q) use ($lead) {
                $q->where('location', 'like', '%' . $lead->preferred_city . '%')
                  ->orWhere('locality', 'like', '%' . $lead->preferred_city . '%');
            });
        }

        if ($lead->bhk_preference) {
            $bhkNum = (int) filter_var($lead->bhk_preference, FILTER_SANITIZE_NUMBER_INT);
            if ($bhkNum > 0) {
                $matchingPropertiesQuery->where('bedrooms', $bhkNum);
            }
        }

        $matchingProperties = $matchingPropertiesQuery->take(6)->get();

        return view('admin.leads.show', compact('lead', 'staffUsers', 'matchingProperties'));
    }

    /**
     * Manually record a new lead (walk-in, phone call, reference).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|regex:/^[6-9]\d{9}$/',
            'email' => 'nullable|email|max:150',
            'intent' => 'required|in:rent,buy,sell,inquire,admission',
            'lead_source' => 'nullable|string|max:60',
            'course' => 'nullable|string|max:200',
            'budget_min' => 'nullable|numeric|min:0',
            'budget_max' => 'nullable|numeric|min:0',
            'bhk_preference' => 'nullable|string|max:20',
            'preferred_city' => 'nullable|string|max:100',
            'preferred_locality' => 'nullable|string|max:100',
            'property_id' => 'nullable|exists:properties,id',
            'assigned_to' => 'nullable|exists:users,id',
            'message' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:1000',
            'whatsapp_opt_in' => 'nullable|boolean',
        ]);

        $isAdmission = $validated['intent'] === 'admission' || !empty($validated['course']);
        $leadSource = $validated['lead_source'] ?: ($isAdmission ? 'admission' : 'manual');
        $notes = $validated['notes'] ?? '';
        $msg = $validated['message'] ?? '';
        if (!empty($validated['course'])) {
            $msg = $msg ? "Course: {$validated['course']}\n{$msg}" : "Course: {$validated['course']}";
        }

        $lead = Lead::create([
            'name' => $validated['name'],
            'mobile' => $validated['phone'],
            'email' => $validated['email'] ?? null,
            'purpose' => in_array($validated['intent'], ['rent', 'buy', 'sell']) ? $validated['intent'] : 'rent',
            'property_type' => $isAdmission ? 'admission' : null,
            'budget_min' => $validated['budget_min'] ?? null,
            'budget_max' => $validated['budget_max'] ?? null,
            'bedrooms' => $validated['bhk_preference'] ?? null,
            'preferred_city' => $validated['preferred_city'] ?? null,
            'preferred_locality' => $validated['preferred_locality'] ?? null,
            'property_id' => $validated['property_id'] ?? null,
            'assigned_to' => $validated['assigned_to'] ?? Auth::id(),
            'message' => $msg ?: null,
            'notes' => $notes ?: null,
            'lead_source' => $leadSource,
            'lead_status' => 'new',
            'engagement_score' => 25,
            'whatsapp_opt_in' => $request->boolean('whatsapp_opt_in', true),
        ]);

        CrmAuditLog::create([
            'lead_id' => $lead->id,
            'user_id' => Auth::id(),
            'action' => 'lead_created_manually',
            'description' => "Lead manually created by staff " . Auth::user()->name,
        ]);

        return redirect()->route('admin.leads.show', $lead)->with('success', 'Lead created successfully.');
    }

    /**
     * Update lead status, assignment, or details.
     */
    public function update(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'lead_status' => 'required|in:new,contacted,interested,scheduled_visit,negotiation,converted,lost,spam',
            'intent' => 'required|in:rent,buy,sell,inquire,admission',
            'assigned_to' => 'nullable|exists:users,id',
            'budget_min' => 'nullable|numeric|min:0',
            'budget_max' => 'nullable|numeric|min:0',
            'bhk_preference' => 'nullable|string|max:20',
            'preferred_city' => 'nullable|string|max:100',
            'preferred_locality' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'whatsapp_opt_in' => 'nullable|boolean',
        ]);

        $oldStatus = $lead->lead_status;
        $oldAssigned = $lead->assigned_to;

        $lead->update([
            'lead_status' => $validated['lead_status'],
            'intent' => $validated['intent'],
            'assigned_to' => $validated['assigned_to'] ?? null,
            'budget_min' => $validated['budget_min'] ?? null,
            'budget_max' => $validated['budget_max'] ?? null,
            'bhk_preference' => $validated['bhk_preference'] ?? null,
            'preferred_city' => $validated['preferred_city'] ?? null,
            'preferred_locality' => $validated['preferred_locality'] ?? null,
            'notes' => $validated['notes'] ?? $lead->notes,
            'whatsapp_opt_in' => $request->has('whatsapp_opt_in') ? $request->boolean('whatsapp_opt_in') : $lead->whatsapp_opt_in,
        ]);

        // Audit status changes
        if ($oldStatus !== $lead->lead_status) {
            CrmAuditLog::create([
                'lead_id' => $lead->id,
                'user_id' => Auth::id(),
                'action' => 'status_changed',
                'description' => "Status changed from {$oldStatus} to {$lead->lead_status}",
            ]);
        }

        // Audit reassignment
        if ($oldAssigned != $lead->assigned_to) {
            $assignedName = $lead->assignedTo ? $lead->assignedTo->name : 'Unassigned';
            CrmAuditLog::create([
                'lead_id' => $lead->id,
                'user_id' => Auth::id(),
                'action' => 'assigned_agent',
                'description' => "Lead assigned to {$assignedName}",
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Lead updated successfully.']);
        }

        return back()->with('success', 'Lead updated successfully.');
    }

    /**
     * Add an internal note to the lead.
     */
    public function addNote(Request $request, Lead $lead)
    {
        $request->validate([
            'note' => 'required|string|max:2000',
        ]);

        $timestamp = Carbon::now()->format('d M Y, h:i A');
        $author = Auth::user()->name;
        $newNoteEntry = "[{$timestamp} by {$author}]\n" . trim($request->note) . "\n\n";

        $lead->notes = $newNoteEntry . ($lead->notes ?? '');
        $lead->save();

        CrmAuditLog::create([
            'lead_id' => $lead->id,
            'user_id' => Auth::id(),
            'action' => 'note_added',
            'description' => "Added internal note: " . mb_substr(trim($request->note), 0, 80) . '...',
        ]);

        return back()->with('success', 'Note added to lead file.');
    }

    /**
     * Send direct WhatsApp message or template.
     */
    public function sendWhatsApp(Request $request, Lead $lead)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        if (!$lead->whatsapp_opt_in) {
            return back()->with('error', 'This lead has opted out of WhatsApp communication.');
        }

        $whatsAppService = app(WhatsAppService::class);
        $result = $whatsAppService->sendMessage($lead->phone, $request->message, $lead->id);

        if ($result['success']) {
            CrmAuditLog::create([
                'lead_id' => $lead->id,
                'user_id' => Auth::id(),
                'action' => 'whatsapp_sent',
                'description' => "Sent WhatsApp message via " . ($result['provider'] ?? 'CRM'),
            ]);

            return back()->with('success', 'WhatsApp message dispatched successfully.');
        }

        return back()->with('error', 'Failed to send WhatsApp message: ' . ($result['error'] ?? 'Unknown error'));
    }

    /**
     * Export filtered leads to CSV.
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $query = Lead::with(['assignedTo', 'property'])
            ->orderByDesc('created_at')
            ->orderByDesc('id');

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('lead_status', $request->status);
        }
        if ($request->filled('intent') && $request->intent !== 'all') {
            $query->where('intent', $request->intent);
        }

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="unlockrentals_leads_' . date('Y-m-d_His') . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($query) {
            $handle = fopen('php://output', 'w');
            
            // Header row
            fputcsv($handle, [
                'ID', 'Name', 'Phone', 'Email', 'Intent', 'Budget Min', 'Budget Max', 
                'BHK Preference', 'Preferred City', 'Preferred Locality', 'Status', 
                'Score', 'Assigned To', 'Source', 'WhatsApp Opt-In', 'Created At'
            ]);

            $query->chunk(100, function ($leads) use ($handle) {
                foreach ($leads as $l) {
                    fputcsv($handle, [
                        $l->id,
                        $l->name,
                        $l->phone,
                        $l->email ?? 'N/A',
                        ucfirst($l->intent ?? 'Inquire'),
                        $l->budget_min ? '₹' . number_format($l->budget_min) : '-',
                        $l->budget_max ? '₹' . number_format($l->budget_max) : '-',
                        $l->bhk_preference ?? '-',
                        $l->preferred_city ?? '-',
                        $l->preferred_locality ?? '-',
                        ucfirst(str_replace('_', ' ', $l->lead_status)),
                        $l->lead_score,
                        $l->assignedTo ? $l->assignedTo->name : 'Unassigned',
                        $l->source,
                        $l->whatsapp_opt_in ? 'Yes' : 'No',
                        $l->created_at->format('Y-m-d H:i:s'),
                    ]);
                }
            });

            fclose($handle);
        }, 200, $headers);
    }
}
