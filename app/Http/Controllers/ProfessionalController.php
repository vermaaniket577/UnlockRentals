<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateReviewRequest;
use App\Http\Requests\ReportProfessionalRequest;
use App\Models\Professional;
use App\Models\ProfessionalCategory;
use App\Models\ProfessionalClickLog;
use App\Models\ProfessionalReport;
use App\Models\ProfessionalReview;
use App\Models\ProfessionalService;
use App\Models\ServiceRequest;
use App\Models\District;
use App\Models\Locality;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProfessionalController extends Controller
{
    /**
     * Display the main marketplace search and discovery directory.
     */
    public function index(Request $request)
    {
        $categories = Cache::remember('active_professional_categories', 3600, function () {
            return ProfessionalCategory::active()->ordered()->withCount(['professionals' => function ($q) {
                $q->approved();
            }])->get();
        });

        $query = Professional::approved()->with(['category', 'services', 'locations']);

        // Filter by Category
        if ($request->filled('category')) {
            $catSlug = $request->input('category');
            $query->whereHas('category', function ($q) use ($catSlug) {
                $q->where('slug', $catSlug)->orWhere('name', $catSlug);
            });
        }

        // Filter by specific service
        if ($request->filled('service')) {
            $servSlug = $request->input('service');
            $query->whereHas('services', function ($q) use ($servSlug) {
                $q->where('slug', $servSlug)->orWhere('name', 'like', "%{$servSlug}%");
            });
        }

        // Search text / keyword
        if ($request->filled('q')) {
            $keyword = trim($request->input('q'));
            $query->where(function ($q) use ($keyword) {
                $q->where('business_name', 'like', "%{$keyword}%")
                  ->orWhere('full_name', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%")
                  ->orWhereHas('category', function ($cq) use ($keyword) {
                      $cq->where('name', 'like', "%{$keyword}%");
                  })
                  ->orWhereHas('services', function ($sq) use ($keyword) {
                      $sq->where('name', 'like', "%{$keyword}%");
                  });
            });
        }

        // Location / City / Locality / Pincode
        $location = trim($request->input('location', ''));
        if ($location !== '') {
            $cleanLoc = str_replace('-', ' ', $location);
            $query->where(function ($q) use ($cleanLoc) {
                $q->where('city', 'like', "%{$cleanLoc}%")
                  ->orWhere('locality', 'like', "%{$cleanLoc}%")
                  ->orWhere('pincode', 'like', "%{$cleanLoc}%")
                  ->orWhere('state', 'like', "%{$cleanLoc}%")
                  ->orWhereHas('locations', function ($lq) use ($cleanLoc) {
                      $lq->where('city', 'like', "%{$cleanLoc}%")
                         ->orWhere('locality', 'like', "%{$cleanLoc}%")
                         ->orWhere('pincode', 'like', "%{$cleanLoc}%");
                  });
            });
        }

        // Geolocation / Radius Search
        if ($request->filled('lat') && $request->filled('lng')) {
            $lat = (float) $request->input('lat');
            $lng = (float) $request->input('lng');
            $radius = (float) $request->input('radius', 25);
            $query->nearby($lat, $lng, $radius);
        }

        // Verified filter
        if ($request->boolean('verified')) {
            $query->verified();
        }

        // Emergency service filter
        if ($request->boolean('emergency')) {
            $query->emergency();
        }

        // Available today filter
        if ($request->boolean('available_today')) {
            $query->availableToday();
        }

        // Home visit filter
        if ($request->boolean('home_visit')) {
            $query->where('home_visit', true);
        }

        // Rating filter
        if ($request->filled('min_rating')) {
            $minRating = (float) $request->input('min_rating');
            $query->where('average_rating', '>=', $minRating);
        }

        // Experience filter
        if ($request->filled('min_exp')) {
            $minExp = (int) $request->input('min_exp');
            $query->where('years_experience', '>=', $minExp);
        }

        // Max price filter
        if ($request->filled('max_price')) {
            $maxPrice = (float) $request->input('max_price');
            $query->where(function ($q) use ($maxPrice) {
                $q->whereNull('starting_price')
                  ->orWhere('starting_price', '<=', $maxPrice);
            });
        }

        // Sorting
        $sort = $request->input('sort', 'recommended');
        switch ($sort) {
            case 'rating':
                $query->orderByDesc('average_rating')->orderByDesc('review_count');
                break;
            case 'experience':
                $query->orderByDesc('years_experience');
                break;
            case 'price_low':
                $query->orderByRaw('CASE WHEN starting_price IS NULL THEN 9999999 ELSE starting_price END ASC');
                break;
            case 'price_high':
                $query->orderByDesc('starting_price');
                break;
            case 'nearest':
                if ($request->filled('lat') && $request->filled('lng')) {
                    $query->orderBy('distance');
                } else {
                    $query->latest();
                }
                break;
            case 'recommended':
            default:
                $query->orderByDesc('featured')
                      ->orderByRaw("CASE WHEN verification_status = 'verified' THEN 1 ELSE 0 END DESC")
                      ->orderByDesc('average_rating')
                      ->latest();
                break;
        }

        $professionals = $query->paginate(15)->withQueryString();

        // Popular cities with professionals
        $popularCities = Cache::remember('popular_professional_cities', 1800, function () {
            return Professional::approved()
                ->select('city', DB::raw('count(*) as count'))
                ->whereNotNull('city')
                ->where('city', '!=', '')
                ->groupBy('city')
                ->orderByDesc('count')
                ->take(12)
                ->get();
        });

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'data' => $professionals,
            ]);
        }

        return view('services.index', compact('professionals', 'categories', 'popularCities'));
    }

    /**
     * SEO Landing page for a specific Category (e.g. /services/electrician).
     */
    public function category(string $categorySlug, Request $request)
    {
        $category = ProfessionalCategory::where('slug', $categorySlug)->firstOrFail();
        
        $request->merge(['category' => $category->slug]);
        return $this->index($request);
    }

    /**
     * SEO Landing page for Category + City (e.g. /services/electrician/gurgaon).
     */
    public function city(string $categorySlug, string $citySlug, Request $request)
    {
        $category = ProfessionalCategory::where('slug', $categorySlug)->firstOrFail();
        $cityName = ucwords(str_replace('-', ' ', $citySlug));

        $request->merge([
            'category' => $category->slug,
            'location' => $cityName,
        ]);

        return $this->index($request);
    }

    /**
     * SEO Landing page for Category + City + Locality (e.g. /services/electrician/gurgaon/sector-14).
     */
    /**
     * SEO Landing page for Category + City + Locality (e.g. /services/electrician/gurgaon/sector-14).
     */
    public function locality(string $categorySlug, string $citySlug, string $localitySlug, Request $request)
    {
        // If a professional profile matches this slug, render their profile
        if (Professional::where('slug', $localitySlug)->exists()) {
            return $this->show($categorySlug, $citySlug, $localitySlug, $request);
        }

        $category = ProfessionalCategory::where('slug', $categorySlug)->firstOrFail();
        $localityName = ucwords(str_replace('-', ' ', $localitySlug));

        $request->merge([
            'category' => $category->slug,
            'location' => $localityName,
        ]);

        return $this->index($request);
    }

    /**
     * Display a single Professional profile page.
     * URL: /services/{category:slug}/{city:slug}/{professional:slug}
     */
    public function show(string $categorySlug, string $citySlug, string $slug, ?Request $request = null)
    {
        $professional = Professional::where('slug', $slug)
            ->with([
                'category',
                'services',
                'locations',
                'photos' => function ($q) { $q->ordered(); },
                'approvedReviews.user',
                'availability',
            ])
            ->first();

        if (!$professional) {
            return $this->locality($categorySlug, $citySlug, $slug, $request ?? request());
        }

        // Increment profile view counter (only once per IP per hour)
        $cacheKey = 'viewed_prof_' . $professional->id . '_' . request()->ip();
        if (!Cache::has($cacheKey)) {
            $professional->increment('views_count');
            Cache::put($cacheKey, true, 3600);

            ProfessionalClickLog::create([
                'professional_id' => $professional->id,
                'user_id' => Auth::id(),
                'click_type' => 'view',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'referrer' => request()->header('referer'),
            ]);
        }

        // Similar professionals in the same category & city
        $relatedProfessionals = Professional::approved()
            ->where('id', '!=', $professional->id)
            ->where('category_id', $professional->category_id)
            ->where(function ($q) use ($professional) {
                $q->where('city', $professional->city)
                  ->orWhere('state', $professional->state);
            })
            ->take(4)
            ->get();

        // Check if user has saved this professional
        $isFavorited = false;
        if (Auth::check()) {
            $isFavorited = Auth::user()->favoriteProfessionals()->where('professional_id', $professional->id)->exists();
        }

        // Schema.org Structured Data
        $schemaData = [
            '@context' => 'https://schema.org',
            '@type' => 'HomeAndConstructionBusiness',
            'name' => $professional->business_name,
            'description' => $professional->description,
            'image' => $professional->profile_photo_url,
            'telephone' => $professional->phone,
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => $professional->locality ?: $professional->city,
                'addressRegion' => $professional->state,
                'postalCode' => $professional->pincode,
                'addressCountry' => 'IN',
            ],
            'aggregateRating' => $professional->review_count > 0 ? [
                '@type' => 'AggregateRating',
                'ratingValue' => (string) $professional->average_rating,
                'reviewCount' => (string) $professional->review_count,
                'bestRating' => '5',
                'worstRating' => '1',
            ] : null,
        ];

        return view('services.show', compact('professional', 'relatedProfessionals', 'isFavorited', 'schemaData'));
    }

    /**
     * Track customer contact click (Call or WhatsApp) safely and redirect.
     */
    public function trackClick(Request $request, Professional $professional, string $type)
    {
        if (!in_array($type, ['call', 'whatsapp'])) {
            return response()->json(['error' => 'Invalid action'], 400);
        }

        // Increment clicks
        if ($type === 'call') {
            $professional->increment('call_clicks');
        } elseif ($type === 'whatsapp') {
            $professional->increment('whatsapp_clicks');
        }

        // Log event
        ProfessionalClickLog::create([
            'professional_id' => $professional->id,
            'user_id' => Auth::id(),
            'click_type' => $type,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referrer' => $request->header('referer'),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'type' => $type,
                'target_url' => $type === 'call' ? 'tel:' . $professional->phone : $professional->whatsapp_url,
            ]);
        }

        if ($type === 'call') {
            return redirect('tel:' . $professional->phone);
        }

        return redirect($professional->whatsapp_url);
    }

    /**
     * Submit customer review.
     */
    public function submitReview(CreateReviewRequest $request, Professional $professional)
    {
        $user = Auth::user();

        // Check if customer already submitted review for this professional
        $existing = ProfessionalReview::where('professional_id', $professional->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existing) {
            return back()->with('error', 'You have already submitted a review for this professional.');
        }

        // If service_request_id provided, verify ownership
        $serviceRequestId = $request->input('service_request_id');
        if ($serviceRequestId) {
            $serviceReq = ServiceRequest::where('id', $serviceRequestId)
                ->where('user_id', $user->id)
                ->first();
            if (!$serviceReq) {
                $serviceRequestId = null;
            }
        }

        $review = ProfessionalReview::create([
            'professional_id' => $professional->id,
            'user_id' => $user->id,
            'service_request_id' => $serviceRequestId,
            'rating' => $request->input('rating'),
            'title' => $request->input('title'),
            'review' => $request->input('review'),
            'status' => 'pending', // Requires admin moderation
        ]);

        return back()->with('success', 'Thank you! Your review has been submitted for moderation and will appear once approved.');
    }

    /**
     * Submit professional fraud / abuse report.
     */
    public function submitReport(ReportProfessionalRequest $request, Professional $professional)
    {
        ProfessionalReport::create([
            'professional_id' => $professional->id,
            'user_id' => Auth::id(),
            'reason' => $request->input('reason'),
            'description' => $request->input('description'),
            'status' => 'pending',
        ]);

        return back()->with('success', 'Report submitted successfully. Our trust and safety team will review this listing promptly.');
    }

    /**
     * Toggle bookmark/favorite for a professional.
     */
    public function toggleFavorite(Professional $professional): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Please login to save professionals.'], 401);
        }

        $user = Auth::user();
        $attached = $user->favoriteProfessionals()->toggle($professional->id);
        $isFavorited = count($attached['attached']) > 0;

        return response()->json([
            'success' => true,
            'favorited' => $isFavorited,
            'message' => $isFavorited ? 'Professional saved to your favorites!' : 'Professional removed from favorites.',
        ]);
    }

    /**
     * AJAX endpoint to fetch services for a selected category.
     */
    public function getServicesByCategory(ProfessionalCategory $category): JsonResponse
    {
        $services = $category->services()->active()->ordered()->get(['id', 'name', 'slug']);
        return response()->json($services);
    }
}
