<?php

namespace App\Http\Controllers;

use App\Models\ConsentRecord;
use App\Services\VisitorTrackingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VisitorTrackingController extends Controller
{
    public function __construct(
        protected VisitorTrackingService $tracker
    ) {}

    /**
     * Record a client-side visitor event.
     * POST /api/visitor/event
     */
    public function recordEvent(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'event_name' => 'required|string|max:60',
            'property_id' => 'nullable|integer|exists:properties,id',
            'page_url' => 'nullable|string|max:500',
            'metadata' => 'nullable|array',
        ]);

        $visitor = $this->tracker->resolveVisitor($request);
        $session = $this->tracker->resolveSession($visitor, $request);

        $event = $this->tracker->recordEvent(
            $visitor,
            $validated['event_name'],
            $validated['property_id'] ?? null,
            $validated['page_url'] ?? null,
            $validated['metadata'] ?? null,
            $session
        );

        return response()->json([
            'success' => true,
            'event_id' => $event->id,
            'visitor_uuid' => $visitor->visitor_uuid,
            'engagement_score' => $visitor->engagement_score,
            'engagement_tier' => $visitor->engagement_tier,
        ]);
    }

    /**
     * Update Cookie & Privacy Consent preference.
     * POST /api/consent/update
     */
    public function updateConsent(Request $request): JsonResponse
    {
        if ($request->has('consent_given') && !$request->has('is_granted')) {
            $request->merge(['is_granted' => $request->boolean('consent_given')]);
        }

        $validated = $request->validate([
            'consent_type' => 'required|string|max:50',
            'is_granted' => 'required|boolean',
            'form_source' => 'nullable|string|max:100',
        ]);

        $visitor = $this->tracker->resolveVisitor($request);

        ConsentRecord::create([
            'visitor_id' => $visitor->id,
            'user_id' => auth()->id() ?? $visitor->user_id,
            'consent_type' => $validated['consent_type'],
            'is_granted' => $validated['is_granted'],
            'consent_text' => $validated['is_granted'] ? 'User granted consent via cookie/privacy banner.' : 'User declined optional consent.',
            'form_source' => $validated['form_source'] ?? 'cookie_banner',
            'ip_address' => $request->ip(),
            'granted_at' => $validated['is_granted'] ? now() : null,
            'withdrawn_at' => !$validated['is_granted'] ? now() : null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Consent preference recorded.',
        ]);
    }
}
