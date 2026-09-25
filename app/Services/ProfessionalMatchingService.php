<?php

namespace App\Services;

use App\Models\Professional;
use App\Models\ProfessionalLead;
use App\Models\ServiceRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ProfessionalMatchingService
{
    /**
     * Find and dispatch matching professionals for a service request.
     *
     * @param ServiceRequest $request
     * @param int $maxMatches Limit dispatch to top N closest/verified professionals
     * @return Collection
     */
    public function matchAndDispatch(ServiceRequest $request, int $maxMatches = 5): Collection
    {
        $query = Professional::approved()
            ->where('category_id', $request->category_id);

        // 1. If specific service requested, prioritize professionals offering that service
        if ($request->service_id) {
            $query->whereHas('services', function ($q) use ($request) {
                $q->where('professional_services.id', $request->service_id);
            });
        }

        // 2. Location filter: Match City or Locality or Service Areas
        $city = trim($request->city);
        $locality = trim($request->locality ?? '');
        $pincode = trim($request->pincode ?? '');

        $query->where(function ($q) use ($city, $locality, $pincode) {
            $q->where(function ($sub) use ($city, $locality, $pincode) {
                $sub->where('city', 'like', "%{$city}%");
                if ($locality) {
                    $sub->orWhere('locality', 'like', "%{$locality}%");
                }
                if ($pincode) {
                    $sub->orWhere('pincode', $pincode);
                }
            })
            // Or matches via additional registered service areas
            ->orWhereHas('locations', function ($locQ) use ($city, $locality, $pincode) {
                $locQ->where('city', 'like', "%{$city}%");
                if ($locality) {
                    $locQ->orWhere('locality', 'like', "%{$locality}%");
                }
                if ($pincode) {
                    $locQ->orWhere('pincode', $pincode);
                }
            });
        });

        // 3. Optional Geolocation Distance prioritization
        if ($request->latitude && $request->longitude) {
            $query->nearby($request->latitude, $request->longitude, 35);
        } else {
            // Prioritize verified, then high ratings, then experienced
            $query->orderByRaw("CASE WHEN verification_status = 'verified' THEN 1 ELSE 2 END")
                ->orderBy('featured', 'desc')
                ->orderBy('average_rating', 'desc')
                ->orderBy('years_experience', 'desc');
        }

        $matchedProfessionals = $query->take($maxMatches)->get();

        // 4. Create Controlled Leads (Privacy compliant)
        foreach ($matchedProfessionals as $professional) {
            ProfessionalLead::firstOrCreate(
                [
                    'service_request_id' => $request->id,
                    'professional_id' => $professional->id,
                ],
                [
                    'customer_id' => $request->user_id,
                    'lead_source' => 'request_service',
                    'status' => 'new',
                ]
            );

            // Increment lead counters
            $professional->increment('lead_count');
        }

        Log::info("Dispatched Service Request #{$request->id} to {$matchedProfessionals->count()} professionals in {$city}");

        return $matchedProfessionals;
    }
}
