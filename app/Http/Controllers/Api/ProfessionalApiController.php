<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Professional;
use App\Models\ProfessionalCategory;
use App\Models\ProfessionalService;
use App\Models\ServiceRequest;
use App\Services\ProfessionalMatchingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfessionalApiController extends Controller
{
    /**
     * Get all active categories with service count.
     */
    public function categories(): JsonResponse
    {
        $categories = ProfessionalCategory::active()->ordered()->withCount(['services', 'professionals' => function ($q) {
            $q->approved();
        }])->get();

        return response()->json([
            'status' => 'success',
            'data' => $categories,
        ]);
    }

    /**
     * Get services optionally filtered by category.
     */
    public function services(Request $request): JsonResponse
    {
        $query = ProfessionalService::active()->ordered()->with('category:id,name,slug');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        return response()->json([
            'status' => 'success',
            'data' => $query->get(),
        ]);
    }

    /**
     * Search and list approved professionals.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Professional::approved()->with(['category:id,name,slug,icon', 'services:id,name,slug']);

        if ($request->filled('category')) {
            $cat = $request->input('category');
            $query->whereHas('category', function ($q) use ($cat) {
                $q->where('slug', $cat)->orWhere('id', $cat);
            });
        }

        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->input('city') . '%');
        }

        if ($request->filled('pincode')) {
            $query->where('pincode', $request->input('pincode'));
        }

        if ($request->boolean('verified')) {
            $query->verified();
        }

        if ($request->boolean('available_today')) {
            $query->availableToday();
        }

        if ($request->boolean('emergency')) {
            $query->emergency();
        }

        $professionals = $query->paginate(15);

        return response()->json([
            'status' => 'success',
            'data' => $professionals->items(),
            'meta' => [
                'current_page' => $professionals->currentPage(),
                'last_page' => $professionals->lastPage(),
                'total' => $professionals->total(),
            ],
        ]);
    }

    /**
     * Find professionals nearby based on latitude and longitude coordinates.
     */
    public function nearby(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
            'radius' => 'nullable|numeric|min:1|max:100',
            'category' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $lat = (float) $request->input('lat');
        $lng = (float) $request->input('lng');
        $radius = (float) $request->input('radius', 25);

        $query = Professional::approved()
            ->with(['category:id,name,slug,icon', 'services:id,name,slug'])
            ->nearby($lat, $lng, $radius);

        if ($request->filled('category')) {
            $cat = $request->input('category');
            $query->whereHas('category', function ($q) use ($cat) {
                $q->where('slug', $cat)->orWhere('id', $cat);
            });
        }

        $results = $query->paginate(15);

        return response()->json([
            'status' => 'success',
            'data' => $results->items(),
            'meta' => [
                'current_page' => $results->currentPage(),
                'last_page' => $results->lastPage(),
                'total' => $results->total(),
                'search_center' => ['lat' => $lat, 'lng' => $lng],
                'radius_km' => $radius,
            ],
        ]);
    }

    /**
     * Get single professional profile.
     */
    public function show(string $slug): JsonResponse
    {
        $professional = Professional::approved()
            ->where('slug', $slug)
            ->with([
                'category',
                'services',
                'locations',
                'photos',
                'approvedReviews.user:id,name,avatar',
                'availability',
            ])
            ->first();

        if (!$professional) {
            return response()->json(['status' => 'error', 'message' => 'Professional listing not found.'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $professional,
        ]);
    }

    /**
     * Submit a customer service request via API.
     */
    public function createServiceRequest(Request $request, ProfessionalMatchingService $matchingService): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:professional_categories,id',
            'service_id' => 'nullable|exists:professional_services,id',
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:150',
            'description' => 'required|string|min:10|max:2000',
            'city' => 'required|string|max:100',
            'locality' => 'nullable|string|max:150',
            'pincode' => 'nullable|string|max:10',
            'preferred_date' => 'nullable|date',
            'budget' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $serviceRequest = ServiceRequest::create([
            'user_id' => $request->user()?->id,
            'category_id' => $request->input('category_id'),
            'service_id' => $request->input('service_id'),
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'description' => $request->input('description'),
            'city' => $request->input('city'),
            'locality' => $request->input('locality'),
            'pincode' => $request->input('pincode'),
            'preferred_date' => $request->input('preferred_date'),
            'budget' => $request->input('budget'),
            'status' => 'new',
        ]);

        // Run matching engine
        $matchedCount = $matchingService->matchAndDispatch($serviceRequest, 4);

        return response()->json([
            'status' => 'success',
            'message' => 'Service request created and sent to verified professionals.',
            'data' => [
                'request_id' => $serviceRequest->id,
                'matched_professionals' => $matchedCount,
            ],
        ], 201);
    }
}
