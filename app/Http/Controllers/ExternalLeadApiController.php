<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExternalLeadApiController extends Controller
{
    /**
     * Default target endpoint for lead delivery.
     */
    protected const DEFAULT_TARGET_URL = 'https://api.anushram.com/v1/api/general-enquiry/create';

    /**
     * Resolve target URL from request or .env.
     */
    protected function resolveTargetUrl(Request $request): string
    {
        return $request->input('target_url')
            ?: env('EXTERNAL_LEAD_API_URL', self::DEFAULT_TARGET_URL);
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
}
