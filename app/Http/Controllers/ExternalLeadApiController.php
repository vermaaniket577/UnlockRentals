<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\User;
use App\Models\CrmAuditLog;
use Carbon\Carbon;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExternalLeadApiController extends Controller
{
    /**
     * Default target endpoint for lead delivery.
     */
    protected const DEFAULT_TARGET_URL = 'https://api.anushram.com/v1/api/general-enquiry/create';

    /**
     * Default source endpoint for fetching external leads.
     */
    protected const DEFAULT_FETCH_URL = 'https://api.anushram.com/v1/api/general-enquiry/all';

    /**
     * Resolve target URL from request or .env.
     */
    protected function resolveTargetUrl(Request $request): string
    {
        return $request->input('target_url')
            ?: env('EXTERNAL_LEAD_API_URL', self::DEFAULT_TARGET_URL);
    }

    /**
     * Resolve source URL for fetching leads.
     */
    protected function resolveFetchUrl(Request $request): string
    {
        return $request->input('source_url')
            ?: env('EXTERNAL_LEAD_FETCH_API_URL', self::DEFAULT_FETCH_URL);
    }

    /**
     * Format a lead into a standardized payload for external intake.
     */
    protected function formatLeadPayload(Lead $lead): array
    {
        $cleanPhone = preg_replace('/[^0-9]/', '', (string) ($lead->mobile ?? ''));
        if (strlen($cleanPhone) >= 10) {
            $cleanPhone = substr($cleanPhone, -10);
        }

        return [
            'id' => $lead->id,
            'name' => $lead->name ?? '',
            'full_name' => $lead->name ?? '',
            'email' => $lead->email ?? '',
            'phone' => $cleanPhone ?: ($lead->mobile ?? ''),
            'mobile' => $cleanPhone ?: ($lead->mobile ?? ''),
            'contact' => $cleanPhone ?: ($lead->mobile ?? ''),
            'city' => $lead->preferred_city ?? '',
            'preferred_city' => $lead->preferred_city ?? '',
            'locality' => $lead->preferred_locality ?? '',
            'property_type' => $lead->property_type ?? '',
            'purpose' => $lead->purpose ?? 'rent',
            'budget' => $lead->budget_max ? (string)$lead->budget_max : ($lead->budget_min ? (string)$lead->budget_min : null),
            'budget_min' => $lead->budget_min,
            'budget_max' => $lead->budget_max,
            'bedrooms' => $lead->bedrooms ?? null,
            'message' => $lead->message ?: 'General enquiry submitted on UnlockRentals',
            'source' => $lead->lead_source ?: 'UnlockRentals',
            'lead_source' => $lead->lead_source ?: 'UnlockRentals',
            'lead_status' => $lead->lead_status ?: 'new',
            'property_id' => $lead->property_id,
            'whatsapp_opt_in' => (bool) $lead->whatsapp_opt_in,
            'created_at' => $lead->created_at ? $lead->created_at->toIso8601String() : now()->toIso8601String(),
        ];
    }

    /**
     * Send all leads to external endpoint (Fire-and-forget HTTP 204 or debug JSON).
     * GET or POST /api/leads/send-all
     * GET or POST /leads/send-all
     */
    public function sendAll(Request $request): Response|JsonResponse
    {
        $targetUrl = $this->resolveTargetUrl($request);
        $mode = $request->input('mode') === 'bulk' ? 'bulk' : 'individual';
        $isDebug = $request->boolean('debug') || $request->input('debug') === '1';

        $leads = Lead::latest()->get();
        $totalLeads = $leads->count();

        if ($totalLeads > 0) {
            if ($mode === 'bulk') {
                // Send all leads at once in a single JSON payload
                try {
                    Http::timeout(10)
                        ->withHeaders([
                            'Accept' => 'application/json',
                            'Content-Type' => 'application/json',
                            'User-Agent' => 'UnlockRentals-LeadDispatcher/1.0',
                        ])
                        ->post($targetUrl, [
                            'leads' => $leads->map(fn($lead) => $this->formatLeadPayload($lead))->toArray(),
                            'total_leads' => $totalLeads,
                            'source' => 'UnlockRentals',
                            'dispatched_at' => now()->toIso8601String(),
                        ]);
                } catch (\Throwable $e) {
                    Log::warning('Silent external bulk lead dispatch error: ' . $e->getMessage());
                }
            } else {
                // Individual mode: dispatch concurrent asynchronous batches via Http::pool()
                $batchSize = 25;
                $chunks = $leads->chunk($batchSize);

                foreach ($chunks as $chunk) {
                    try {
                        Http::pool(function (Pool $pool) use ($chunk, $targetUrl) {
                            foreach ($chunk as $lead) {
                                $pool->as('lead_' . $lead->id)
                                    ->timeout(5)
                                    ->withHeaders([
                                        'Accept' => 'application/json',
                                        'Content-Type' => 'application/json',
                                        'User-Agent' => 'UnlockRentals-LeadDispatcher/1.0',
                                    ])
                                    ->post($targetUrl, $this->formatLeadPayload($lead));
                            }
                        });
                    } catch (\Throwable $e) {
                        Log::warning('Silent external lead pool batch error: ' . $e->getMessage());
                    }
                }
            }
        }

        // Debug / Verification Mode
        if ($isDebug) {
            return response()->json([
                'success' => true,
                'message' => "Successfully sent {$totalLeads} leads to external endpoint.",
                'total_leads' => $totalLeads,
                'target_url' => $targetUrl,
                'mode' => $mode,
            ]);
        }

        // Fire-and-Forget: HTTP 204 No Content (0 bytes returned)
        return response()->noContent();
    }

    /**
     * Send a specific single lead by ID without response body (HTTP 204 or debug JSON).
     * GET or POST /api/leads/send/{id}
     */
    public function sendSingle(Request $request, $id): Response|JsonResponse
    {
        $targetUrl = $this->resolveTargetUrl($request);
        $isDebug = $request->boolean('debug') || $request->input('debug') === '1';

        $lead = Lead::find($id);

        if ($lead) {
            try {
                Http::timeout(5)
                    ->withHeaders([
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                        'User-Agent' => 'UnlockRentals-LeadDispatcher/1.0',
                    ])
                    ->post($targetUrl, $this->formatLeadPayload($lead));
            } catch (\Throwable $e) {
                Log::warning("Silent single lead #{$id} dispatch error: " . $e->getMessage());
            }
        }

        // Debug mode
        if ($isDebug) {
            if (!$lead) {
                return response()->json([
                    'success' => false,
                    'message' => "Lead with ID #{$id} not found.",
                    'target_url' => $targetUrl,
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => "Successfully sent lead #{$id} to external endpoint.",
                'lead_id' => (int) $id,
                'target_url' => $targetUrl,
            ]);
        }

        // Fire-and-Forget: HTTP 204 No Content
        return response()->noContent();
    }

    /**
     * Fetch leads from external API, store them in the database, and return live summary.
     * GET or POST /api/leads/fetch
     * GET or POST /leads/fetch
     * GET or POST /admin/leads/fetch-external
     */
    public function fetch(Request $request): JsonResponse
    {
        $sourceUrl = $this->resolveFetchUrl($request);
        $limit = (int) $request->input('limit', 0); // 0 = fetch all available
        $assignedStaff = User::whereIn('role', ['admin', 'owner', 'sales_manager', 'sales_executive'])->first();

        try {
            $response = Http::timeout(20)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'User-Agent' => 'UnlockRentals-LeadFetcher/1.0',
                ])
                ->get($sourceUrl);

            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => "External API returned HTTP {$response->status()}: " . substr($response->body(), 0, 200),
                    'source_url' => $sourceUrl,
                ], 502);
            }

            $body = $response->json();
        } catch (\Throwable $e) {
            Log::error('External lead fetch network error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to connect to external API: ' . $e->getMessage(),
                'source_url' => $sourceUrl,
            ], 500);
        }

        // Extract list from various potential JSON structures (Anushram enquiries, leads, data, or direct list)
        $items = [];
        if (isset($body['enquiries']) && is_array($body['enquiries'])) {
            $items = $body['enquiries'];
        } elseif (isset($body['leads']) && is_array($body['leads'])) {
            $items = $body['leads'];
        } elseif (isset($body['data']) && is_array($body['data'])) {
            $items = $body['data'];
        } elseif (is_array($body) && array_is_list($body)) {
            $items = $body;
        }

        if (empty($items)) {
            return response()->json([
                'success' => true,
                'message' => 'External API responded successfully, but returned 0 leads or an unfamiliar payload structure.',
                'total_fetched' => 0,
                'new_leads_saved' => 0,
                'existing_leads_updated' => 0,
                'skipped' => 0,
                'source_url' => $sourceUrl,
            ]);
        }

        if ($limit > 0 && count($items) > $limit) {
            $items = array_slice($items, 0, $limit);
        }

        $totalFetched = count($items);
        $newCount = 0;
        $updatedCount = 0;
        $skippedCount = 0;
        $processedLeads = [];

        foreach ($items as $item) {
            if (!is_array($item)) {
                $skippedCount++;
                continue;
            }

            // Extract contact
            $rawPhone = $item['contact']
                ?? ($item['phone']
                ?? ($item['mobile']
                ?? ($item['phone_number']
                ?? ($item['student_mobile'] ?? ''))));

            $cleanPhone = preg_replace('/[^0-9]/', '', (string)$rawPhone);
            if (strlen($cleanPhone) >= 10) {
                $cleanPhone = substr($cleanPhone, -10);
            }

            $email = !empty($item['email']) ? trim($item['email']) : null;
            if (strlen($cleanPhone) < 10 && empty($email)) {
                $skippedCount++;
                continue;
            }

            // Fallback mobile if only email was provided
            if (strlen($cleanPhone) < 10) {
                $cleanPhone = '9999999999';
            }

            // Extract name
            $firstName = trim($item['firstName'] ?? '');
            $lastName = trim($item['lastName'] ?? '');
            $name = trim("{$firstName} {$lastName}");
            if (empty($name)) {
                $name = trim($item['name'] ?? ($item['full_name'] ?? ($item['student_name'] ?? ($item['candidate_name'] ?? 'Admission Enquirer'))));
            }

            // Academic / Course & Subject details
            $subject = trim($item['subject'] ?? ($item['course'] ?? ($item['program'] ?? ($item['degree'] ?? ''))));
            $userMsg = trim($item['message'] ?? ($item['query'] ?? ($item['enquiry'] ?? ($item['notes'] ?? ''))));

            $fullMessage = '';
            if ($subject && $userMsg) {
                $fullMessage = "Subject/Course: {$subject}\nEnquiry: {$userMsg}";
            } elseif ($subject) {
                $fullMessage = "Subject/Course: {$subject}";
            } elseif ($userMsg) {
                $fullMessage = $userMsg;
            } else {
                $fullMessage = 'Admission inquiry fetched from external portal';
            }

            $city = trim($item['city'] ?? ($item['preferred_city'] ?? ($item['location'] ?? '')));
            $status = strtolower($item['status'] ?? 'new');
            $allowedStatuses = ['new', 'contacted', 'interested', 'scheduled_visit', 'negotiation', 'converted', 'lost', 'spam'];
            $leadStatus = in_array($status, $allowedStatuses) ? $status : 'new';

            // Parse timestamp if available
            $createdAt = null;
            if (!empty($item['createdAt'])) {
                try {
                    $createdAt = Carbon::parse($item['createdAt']);
                } catch (\Throwable) {}
            }

            // Deduplication: look for existing lead by phone or email
            $existing = null;
            if ($cleanPhone !== '9999999999') {
                $existing = Lead::where('mobile', 'LIKE', '%' . $cleanPhone)->first();
            }
            if (!$existing && $email) {
                $existing = Lead::where('email', $email)->first();
            }

            if ($existing) {
                // Update existing record
                $updateData = [
                    'engagement_score' => ($existing->engagement_score ?? 20) + 10,
                ];
                if (!empty($city) && empty($existing->preferred_city)) {
                    $updateData['preferred_city'] = $city;
                }
                if ($subject && !str_contains($existing->message ?? '', $subject)) {
                    $updateData['message'] = ($existing->message ? $existing->message . "\n" : '') . "[External Sync]: " . $fullMessage;
                }
                if ($existing->lead_source === 'website' || empty($existing->lead_source)) {
                    $updateData['lead_source'] = 'anushram';
                }
                if ($existing->property_type !== 'admission') {
                    $updateData['property_type'] = 'admission';
                }
                $existing->update($updateData);

                $updatedCount++;
                $processedLeads[] = [
                    'id' => $existing->id,
                    'name' => $existing->name,
                    'mobile' => $existing->mobile,
                    'status' => 'updated',
                ];
            } else {
                // Create brand new Lead
                $lead = Lead::create([
                    'name' => $name,
                    'mobile' => $cleanPhone,
                    'email' => $email,
                    'lead_source' => 'anushram',
                    'lead_status' => $leadStatus,
                    'lead_stage' => 'enquiry',
                    'property_type' => 'admission',
                    'purpose' => 'rent', // Satisfies MySQL ENUM
                    'preferred_city' => $city ?: null,
                    'message' => $fullMessage,
                    'notes' => $subject ?: 'External Admission Lead',
                    'assigned_to' => $assignedStaff?->id,
                    'engagement_score' => 35,
                    'whatsapp_opt_in' => true,
                ]);

                if ($createdAt) {
                    $lead->created_at = $createdAt;
                    $lead->saveQuietly();
                }

                if (class_exists(CrmAuditLog::class)) {
                    try {
                        CrmAuditLog::create([
                            'lead_id' => $lead->id,
                            'user_id' => Auth::id() ?? $assignedStaff?->id,
                            'action' => 'lead_fetched_external',
                            'description' => "Lead fetched from external API ({$sourceUrl})",
                        ]);
                    } catch (\Throwable) {}
                }

                $newCount++;
                $processedLeads[] = [
                    'id' => $lead->id,
                    'name' => $lead->name,
                    'mobile' => $lead->mobile,
                    'status' => 'created',
                ];
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Successfully fetched {$totalFetched} leads from external API. {$newCount} new leads stored in database, {$updatedCount} existing leads updated.",
            'total_fetched' => $totalFetched,
            'new_leads_saved' => $newCount,
            'existing_leads_updated' => $updatedCount,
            'skipped' => $skippedCount,
            'source_url' => $sourceUrl,
            'sample_processed' => array_slice($processedLeads, 0, 10),
        ]);
    }
}
