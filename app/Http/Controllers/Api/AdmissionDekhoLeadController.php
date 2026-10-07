<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class AdmissionDekhoLeadController extends Controller
{
    /**
     * API Status & Schema Documentation.
     * GET /api/admission-dekho
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'status' => 'online',
            'service' => 'Admission Dekho Lead CRM Intake API',
            'version' => '1.0',
            'description' => 'Receives enquiry form data from Admission Dekho and saves into CRM database.',
            'endpoints' => [
                'POST /api/admission-dekho/enquiry' => 'Submit form data (JSON, FormData, or URL-encoded)',
                'POST /api/admission-dekho' => 'Alias for enquiry submission',
                'POST /api/general-enquiry/create' => 'Standard Anushram compatible endpoint',
            ],
            'form_fields' => [
                'firstName' => 'string | First Name (e.g. "John")',
                'lastName' => 'string | Last Name (e.g. "Doe")',
                'name' => 'string | Full Name alternative (e.g. "John Doe")',
                'email' => 'string | Valid email address',
                'contact' => 'string | 10-digit mobile number (e.g. "9876543210")',
                'phone' => 'string | Mobile alternative',
                'state' => 'string | State / Region (e.g. "Delhi", "Uttar Pradesh")',
                'city' => 'string | City (e.g. "New Delhi", "Lucknow")',
                'message' => 'string | Student enquiry / query',
                'subject' => 'string | Academic subject or form context (Optional)',
                'course' => 'string | Desired course/stream (Optional)',
                'pageRef' => 'string | URL/Path of the page where form was submitted (Optional)',
            ]
        ])->withHeaders($this->corsHeaders());
    }

    /**
     * Store admission enquiry lead from Admission Dekho form.
     * POST /api/admission-dekho/enquiry
     * POST /api/admission-dekho
     */
    public function store(Request $request): JsonResponse|Response
    {
        // Handle preflight OPTIONS request
        if ($request->isMethod('OPTIONS')) {
            return response('', 204)->withHeaders($this->corsHeaders());
        }

        // 1. Extract and Clean Contact / Phone Number
        $rawPhone = $request->input('contact')
            ?? $request->input('phone')
            ?? $request->input('mobile')
            ?? $request->input('phoneNumber')
            ?? $request->input('phone_number')
            ?? $request->input('student_mobile')
            ?? $request->input('tel')
            ?? '';

        $cleanPhone = preg_replace('/[^0-9]/', '', (string) $rawPhone);
        if (strlen($cleanPhone) >= 10) {
            $cleanPhone = substr($cleanPhone, -10);
        }

        if (empty($cleanPhone) || strlen($cleanPhone) < 10) {
            return response()->json([
                'success' => false,
                'message' => 'A valid 10-digit mobile or contact number is required.',
                'errors' => [
                    'contact' => ['Please enter a valid 10-digit phone number.']
                ]
            ], 422)->withHeaders($this->corsHeaders());
        }

        // 2. Extract Name (Supports First + Last or combined Name)
        $firstName = trim((string) (
            $request->input('firstName') 
            ?? $request->input('first_name') 
            ?? $request->input('first') 
            ?? $request->input('fname') 
            ?? ''
        ));
        $lastName = trim((string) (
            $request->input('lastName') 
            ?? $request->input('last_name') 
            ?? $request->input('last') 
            ?? $request->input('lname') 
            ?? ''
        ));

        $composedName = trim("{$firstName} {$lastName}");
        $name = $composedName ?: (
            $request->input('name')
            ?? $request->input('full_name')
            ?? $request->input('student_name')
            ?? $request->input('candidate_name')
            ?? 'Admission Enquirer'
        );

        // 3. Extract Email
        $email = trim((string) ($request->input('email') ?? $request->input('student_email') ?? '')) ?: null;

        // 4. Extract Location: State & City
        $state = trim((string) ($request->input('state') ?? $request->input('region') ?? $request->input('province') ?? ''));
        $city = trim((string) ($request->input('city') ?? $request->input('preferred_city') ?? $request->input('district') ?? ''));

        // 5. Extract Course / Stream / Subject / Academic Info
        $subject = trim((string) ($request->input('subject') ?? ''));
        $course = trim((string) ($request->input('course') ?? $request->input('stream') ?? $request->input('program') ?? ''));
        $pageRef = trim((string) ($request->input('pageRef') ?? $request->input('page') ?? $request->input('url') ?? ''));

        $academicParts = array_filter([
            $subject ?: null,
            $course ?: null,
            $request->input('specialization'),
            $request->input('degree'),
            $request->input('college'),
            $request->input('university'),
        ]);
        $academicSummary = implode(' • ', $academicParts);

        // 6. Extract Message
        $userMessage = trim((string) (
            $request->input('message')
            ?? $request->input('enquiry')
            ?? $request->input('query')
            ?? $request->input('notes')
            ?? $request->input('comments')
            ?? ''
        ));

        // Build comprehensive message for CRM notes
        $messageLines = [];
        if ($academicSummary) {
            $messageLines[] = "Subject/Course: {$academicSummary}";
        }
        if ($state || $city) {
            $locParts = array_filter([$city ? "City: {$city}" : null, $state ? "State: {$state}" : null]);
            $messageLines[] = "Location: " . implode(', ', $locParts);
        }
        if ($pageRef) {
            $messageLines[] = "Submitted From: {$pageRef}";
        }
        if ($userMessage) {
            $messageLines[] = "Enquiry: {$userMessage}";
        }

        $fullMessage = !empty($messageLines)
            ? implode("\n", $messageLines)
            : 'Enquiry submitted via Admission Dekho form';

        $notesTag = $academicSummary ?: ($subject ?: 'Admission Dekho Lead');

        // 7. Prevent accidental rapid double-submission (within 60 seconds with identical mobile & message)
        $existing = Lead::where('mobile', 'LIKE', '%' . $cleanPhone)
            ->where('message', $fullMessage)
            ->where('created_at', '>=', now()->subSeconds(60))
            ->first();

        if ($existing) {
            $existing->update([
                'name' => ($name && $name !== 'Admission Enquirer') ? $name : $existing->name,
                'email' => $email ?: $existing->email,
                'preferred_city' => $city ?: $existing->preferred_city,
                'preferred_locality' => $state ?: $existing->preferred_locality,
                'lead_source' => 'Admission Dekho',
                'lead_status' => 'new', // Flag as new inquiry so agents review immediately
                'notes' => $notesTag ?: $existing->notes,
                'message' => $existing->message
                    ? ($existing->message . "\n[Admission Dekho Update " . now()->format('d M H:i') . "]:\n" . $fullMessage)
                    : $fullMessage,
                'engagement_score' => ($existing->engagement_score ?? 20) + 15,
            ]);
            $lead = $existing;
            $action = 'updated';
        } else {
            // Auto-assign to available sales admin or owner
            $assignedStaff = User::whereIn('role', ['admin', 'owner', 'sales_manager', 'sales_executive'])->first();

            $lead = Lead::create([
                'name' => $name,
                'mobile' => $cleanPhone,
                'email' => $email,
                'preferred_city' => $city ?: ($state ?: null),
                'preferred_locality' => $state ?: null,
                'property_type' => 'admission',
                'purpose' => 'rent', // Satisfies MySQL ENUM constraint
                'lead_source' => 'Admission Dekho',
                'lead_status' => 'new',
                'lead_stage' => 'enquiry',
                'message' => $fullMessage,
                'notes' => $notesTag,
                'assigned_to' => $assignedStaff?->id,
                'engagement_score' => 35,
                'whatsapp_opt_in' => true,
                'next_follow_up_at' => now()->addHours(2),
            ]);
            $action = 'created';
        }

        // Return standard JSON response
        return response()->json([
            'success' => true,
            'message' => 'Admission enquiry received and saved successfully in CRM.',
            'action' => $action,
            'lead_id' => $lead->id,
            'lead' => [
                'id' => $lead->id,
                'name' => $lead->name,
                'first_name' => $firstName ?: null,
                'last_name' => $lastName ?: null,
                'email' => $lead->email,
                'phone' => $lead->mobile,
                'contact' => $lead->mobile,
                'city' => $lead->preferred_city,
                'state' => $state ?: null,
                'course' => $lead->course,
                'stream' => $lead->stream,
                'source' => $lead->lead_source,
                'status' => $lead->lead_status,
                'created_at' => $lead->created_at->toIso8601String(),
            ]
        ], $action === 'created' ? 201 : 200)->withHeaders($this->corsHeaders());
    }

    /**
     * Standard CORS headers allowing external form submissions from Admission Dekho.
     */
    protected function corsHeaders(): array
    {
        return [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With, Accept',
        ];
    }
}
