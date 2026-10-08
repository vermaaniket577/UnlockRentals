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

        $allData = $request->all();

        // Check if incoming payload is a bulk batch of leads from database migration/sync
        $batch = null;
        if (is_array($allData) && array_is_list($allData)) {
            $batch = $allData;
        } elseif (isset($allData['leads']) && is_array($allData['leads'])) {
            $batch = $allData['leads'];
        } elseif (isset($allData['enquiries']) && is_array($allData['enquiries'])) {
            $batch = $allData['enquiries'];
        } elseif (isset($allData['data']) && is_array($allData['data']) && array_is_list($allData['data'])) {
            $batch = $allData['data'];
        }

        // --- BULK BATCH MODE (Database sync / Multi-lead migration) ---
        if ($batch !== null && !empty($batch)) {
            $importedCount = 0;
            $updatedCount = 0;
            $skippedCount = 0;
            $processedLeads = [];

            // Auto-clean duplicates before batch import
            Lead::cleanDuplicates();

            foreach ($batch as $item) {
                if (!is_array($item)) {
                    $skippedCount++;
                    continue;
                }
                $result = $this->saveOrUpdateLead($item);
                if ($result) {
                    if ($result['action'] === 'created') {
                        $importedCount++;
                    } else {
                        $updatedCount++;
                    }
                    $processedLeads[] = $result['lead_summary'];
                } else {
                    $skippedCount++;
                }
            }

            // Auto-clean duplicates after batch import
            Lead::cleanDuplicates();

            return response()->json([
                'success' => true,
                'message' => "Successfully processed " . count($batch) . " Admission Dekho leads ({$importedCount} newly created, {$updatedCount} updated, {$skippedCount} skipped).",
                'total_received' => count($batch),
                'imported_count' => $importedCount,
                'updated_count' => $updatedCount,
                'skipped_count' => $skippedCount,
                'leads' => $processedLeads,
            ], 200)->withHeaders($this->corsHeaders());
        }

        // --- SINGLE FORM SUBMISSION MODE ---
        $result = $this->saveOrUpdateLead($request->all());

        if (!$result) {
            return response()->json([
                'success' => false,
                'message' => 'A valid 10-digit mobile or contact number is required.',
                'errors' => [
                    'contact' => ['Please enter a valid 10-digit phone number.']
                ]
            ], 422)->withHeaders($this->corsHeaders());
        }

        $lead = $result['lead'];
        $action = $result['action'];

        return response()->json([
            'success' => true,
            'message' => 'Admission enquiry received and saved successfully in CRM.',
            'action' => $action,
            'lead_id' => $lead->id,
            'lead' => [
                'id' => $lead->id,
                'name' => $lead->name,
                'first_name' => $result['first_name'] ?: null,
                'last_name' => $result['last_name'] ?: null,
                'email' => $lead->email,
                'phone' => $lead->mobile,
                'contact' => $lead->mobile,
                'city' => $lead->preferred_city,
                'state' => $lead->preferred_locality,
                'course' => $lead->course,
                'stream' => $lead->stream,
                'source' => $lead->lead_source,
                'status' => $lead->lead_status,
                'created_at' => $lead->created_at->toIso8601String(),
            ]
        ], $action === 'created' ? 201 : 200)->withHeaders($this->corsHeaders());
    }

    /**
     * Parse, validate, deduplicate, and persist an admission lead enquiry into UnlockRentals database.
     */
    protected function saveOrUpdateLead(array $data): ?array
    {
        // 1. Extract and Clean Contact / Phone Number
        $rawPhone = $data['contact']
            ?? $data['phone']
            ?? $data['mobile']
            ?? $data['phoneNumber']
            ?? $data['phone_number']
            ?? $data['student_mobile']
            ?? $data['tel']
            ?? '';

        $cleanPhone = preg_replace('/[^0-9]/', '', (string) $rawPhone);
        if (strlen($cleanPhone) >= 10) {
            $cleanPhone = substr($cleanPhone, -10);
        }

        if (empty($cleanPhone) || strlen($cleanPhone) < 10) {
            return null;
        }

        // 2. Extract Name
        $firstName = trim((string) (
            $data['firstName'] 
            ?? $data['first_name'] 
            ?? $data['first'] 
            ?? $data['fname'] 
            ?? ''
        ));
        $lastName = trim((string) (
            $data['lastName'] 
            ?? $data['last_name'] 
            ?? $data['last'] 
            ?? $data['lname'] 
            ?? ''
        ));

        $composedName = trim("{$firstName} {$lastName}");
        $name = $composedName ?: (
            $data['name']
            ?? $data['full_name']
            ?? $data['student_name']
            ?? $data['candidate_name']
            ?? 'Admission Enquirer'
        );

        // 3. Extract Email
        $email = trim((string) ($data['email'] ?? $data['student_email'] ?? '')) ?: null;

        // 4. Extract Location
        $state = trim((string) ($data['state'] ?? $data['region'] ?? $data['province'] ?? ''));
        $city = trim((string) ($data['city'] ?? $data['preferred_city'] ?? $data['district'] ?? ''));

        // 5. Extract Course / Stream / Academic Info
        $subject = trim((string) ($data['subject'] ?? ''));
        $course = trim((string) ($data['course'] ?? $data['stream'] ?? $data['program'] ?? ''));
        $pageRef = trim((string) ($data['pageRef'] ?? $data['page'] ?? $data['url'] ?? ''));

        $academicParts = array_filter([
            $subject ?: null,
            $course ?: null,
            $data['specialization'] ?? null,
            $data['degree'] ?? null,
            $data['college'] ?? null,
            $data['university'] ?? null,
        ]);
        $academicSummary = implode(' • ', $academicParts);

        // 6. Extract Message
        $userMessage = trim((string) (
            $data['message']
            ?? $data['enquiry']
            ?? $data['query']
            ?? $data['notes']
            ?? $data['comments']
            ?? ''
        ));

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

        // External ID reference (if provided from Admission Dekho database)
        $externalId = (string) ($data['id'] ?? $data['_id'] ?? $data['lead_id'] ?? '');

        // 7. Strict duplicate check by phone number
        $existing = null;
        if (!empty($externalId)) {
            $existing = Lead::where('consent_text', $externalId)
                ->orWhere('notes', 'LIKE', "%[Ref:{$externalId}]%")
                ->first();
        }

        if (!$existing) {
            $existing = Lead::where('mobile', 'LIKE', '%' . $cleanPhone)->first();
        }

        if ($existing) {
            $existingMsg = $existing->message ?? '';
            $cleanNewMsg = trim($fullMessage);
            $finalMsg = $existingMsg;
            if (empty($finalMsg)) {
                $finalMsg = $cleanNewMsg;
            } elseif (!empty($cleanNewMsg) && !str_contains($finalMsg, $cleanNewMsg)) {
                $finalMsg = $finalMsg . "\n[Admission Dekho Update " . now()->format('d M H:i') . "]:\n" . $cleanNewMsg;
            }

            $existing->update([
                'name' => ($name && $name !== 'Admission Enquirer') ? $name : $existing->name,
                'email' => $email ?: $existing->email,
                'preferred_city' => $city ?: $existing->preferred_city,
                'preferred_locality' => $state ?: $existing->preferred_locality,
                'lead_source' => 'Admission Dekho',
                'lead_status' => 'new',
                'consent_text' => $externalId ?: $existing->consent_text,
                'notes' => $notesTag ?: $existing->notes,
                'message' => $finalMsg,
                'engagement_score' => ($existing->engagement_score ?? 20) + 15,
            ]);

            // Preserve historical creation timestamp if provided in payload
            $dateField = $data['created_at'] ?? $data['date'] ?? $data['createdAt'] ?? null;
            if (!empty($dateField)) {
                try {
                    $parsed = Carbon::parse($dateField);
                    if ($parsed->lt($existing->created_at)) {
                        $existing->created_at = $parsed;
                        $existing->saveQuietly();
                    }
                } catch (\Throwable $e) {}
            }

            $lead = $existing;
            $action = 'updated';
        } else {
            $assignedStaff = User::whereIn('role', ['admin', 'owner', 'sales_manager', 'sales_executive'])->first();

            $lead = Lead::create([
                'name' => $name,
                'mobile' => $cleanPhone,
                'email' => $email,
                'preferred_city' => $city ?: ($state ?: null),
                'preferred_locality' => $state ?: null,
                'property_type' => 'admission',
                'purpose' => 'rent',
                'lead_source' => 'Admission Dekho',
                'lead_status' => 'new',
                'lead_stage' => 'enquiry',
                'message' => $fullMessage,
                'notes' => $notesTag,
                'consent_text' => $externalId ?: null,
                'assigned_to' => $assignedStaff?->id,
                'engagement_score' => 35,
                'whatsapp_opt_in' => true,
                'next_follow_up_at' => now()->addHours(2),
            ]);

            // Preserve original database creation timestamp
            $dateField = $data['created_at'] ?? $data['date'] ?? $data['createdAt'] ?? null;
            if (!empty($dateField)) {
                try {
                    $lead->created_at = Carbon::parse($dateField);
                    $lead->saveQuietly();
                } catch (\Throwable $e) {}
            }

            $action = 'created';
        }

        return [
            'lead' => $lead,
            'action' => $action,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'lead_summary' => [
                'id' => $lead->id,
                'name' => $lead->name,
                'phone' => $lead->mobile,
                'email' => $lead->email,
                'course' => $lead->course,
                'stream' => $lead->stream,
                'city' => $lead->preferred_city,
                'source' => $lead->lead_source,
                'action' => $action,
            ]
        ];
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
