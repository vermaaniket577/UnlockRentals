<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Category;
use App\Services\SeoKeywordService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SeoController extends Controller
{
    /**
     * Handle programmatic SEO landing pages dynamically.
     */
    public function handle(Request $request, $seo_slug)
    {
        // 1. Check if slug matches curated SEO keywords dataset
        $keywordItem = SeoKeywordService::findBySlug($seo_slug);

        if ($keywordItem) {
            $recommendedPage = $keywordItem['recommended_page'] ?? 'Category / Landing Page';
            $metaTitle = $keywordItem['seo_title'];
            $metaDescription = $keywordItem['meta_description'];

            // Branch A: Owner Listing Page
            if ($recommendedPage === 'Owner Listing Page') {
                $schemas = $this->generateOwnerSchemas($seo_slug, $metaTitle, $metaDescription);
                return view('seo.owner-landing', [
                    'keywordItem' => $keywordItem,
                    'meta_title' => $metaTitle,
                    'meta_description' => $metaDescription,
                    'schemas' => $schemas,
                ]);
            }

            // Branch B: Rental Guide / Blog Page
            if ($recommendedPage === 'Blog / Guide') {
                $schemas = $this->generateGuideSchemas($seo_slug, $metaTitle, $metaDescription, $keywordItem['keyword']);
                $recommendations = Property::approved()->with(['primaryImage', 'category', 'owner'])->latest()->limit(4)->get();
                return view('seo.guide', [
                    'keywordItem' => $keywordItem,
                    'meta_title' => $metaTitle,
                    'meta_description' => $metaDescription,
                    'recommendations' => $recommendations,
                    'schemas' => $schemas,
                ]);
            }

            // Branch C: Category, City, Locality, and Commercial Landing Pages
            return $this->handleCuratedLandingPage($request, $keywordItem);
        }

        // 2. Fallback to existing dynamic regex pattern matching
        $params = $this->parseSlug($seo_slug);

        if (!$params) {
            abort(404);
        }

        // Resolve location name and locality
        $resolvedLoc = [];
        if (isset($params['location_slug'])) {
            $resolvedLoc = $this->resolveLocation($params['location_slug']);
        } elseif (isset($params['landmark_slug'])) {
            $resolvedLoc = $this->resolveLandmark($params['landmark_slug']);
        }

        $city = $resolvedLoc['city'] ?? null;
        $locality = $resolvedLoc['locality'] ?? null;
        $landmark = $resolvedLoc['landmark'] ?? null;
        $gender = $params['gender'] ?? null;
        $budget = $params['budget'] ?? null;
        $type = $params['type'] ?? 'property';
        $isNearMe = $params['is_near_me'] ?? false;

        // Dynamic query-time overrides for near-me landing pages
        if ($request->filled('district')) {
            $city = ucwords(str_replace('-', ' ', $request->district));
        } elseif ($request->filled('city')) {
            $city = ucwords(str_replace('-', ' ', $request->city));
        }
        if ($request->filled('locality')) {
            $locality = ucwords(str_replace('-', ' ', $request->locality));
        }

        // Map type slug to display name
        $typeDisplay = 'Property';
        if ($type === 'room') {
            $typeDisplay = 'Room';
        } elseif ($type === 'pg') {
            $typeDisplay = 'PG & Co-Living';
        } elseif ($type === 'flat') {
            $typeDisplay = 'Flat';
        } elseif ($type === 'apartment') {
            $typeDisplay = 'Apartment';
        } elseif ($type === 'house') {
            $typeDisplay = 'House';
        }

        // Build database query
        $query = Property::approved()->with(['primaryImage', 'category', 'owner']);

        // 1. Filter by category / type
        if ($type === 'room') {
            $query->where(function ($q) {
                $q->where('bedrooms', 1)
                  ->orWhere('bedrooms', 0)
                  ->orWhere('title', 'like', '%room%')
                  ->orWhere('title', 'like', '%1rk%')
                  ->orWhere('title', 'like', '%1 rk%')
                  ->orWhere('description', 'like', '%room%')
                  ->orWhere('description', 'like', '%1rk%');
            });
        } elseif ($type === 'pg') {
            $query->where(function ($q) {
                $q->where('type', 'pg-hostel')
                  ->orWhere('title', 'like', '%pg%')
                  ->orWhere('title', 'like', '%hostel%')
                  ->orWhere('description', 'like', '%pg%')
                  ->orWhere('description', 'like', '%hostel%')
                  ->orWhere('description', 'like', '%co-living%')
                  ->orWhere('description', 'like', '%coliving%');
            });
        } elseif ($type === 'flat' || $type === 'apartment') {
            $query->where(function ($q) {
                $q->whereHas('category', function ($catQ) {
                    $catQ->where('name', 'like', '%apartment%')
                         ->orWhere('name', 'like', '%flat%');
                })->orWhere('title', 'like', '%flat%')
                  ->orWhere('title', 'like', '%apartment%')
                  ->orWhere('description', 'like', '%flat%')
                  ->orWhere('description', 'like', '%apartment%');
            });
        } elseif ($type === 'house') {
            $query->where(function ($q) {
                $q->where('type', 'house')
                  ->orWhereHas('category', function ($catQ) {
                      $catQ->where('name', 'like', '%house%')
                           ->orWhere('name', 'like', '%villa%');
                  })->orWhere('title', 'like', '%house%')
                    ->orWhere('title', 'like', '%villa%')
                    ->orWhere('description', 'like', '%house%')
                    ->orWhere('description', 'like', '%villa%');
            });
        }

        // 2. Filter by location / locality
        if ($city) {
            $query->where(function ($q) use ($city) {
                $q->where('location', 'like', '%' . $city . '%')
                  ->orWhere('address', 'like', '%' . $city . '%');
            });
        }

        if ($locality) {
            $query->where(function ($q) use ($locality) {
                $q->where('locality', 'like', '%' . $locality . '%')
                  ->orWhere('location', 'like', '%' . $locality . '%')
                  ->orWhere('address', 'like', '%' . $locality . '%');
            });
        }

        // 3. Filter by landmark
        if ($landmark) {
            $query->where(function ($q) use ($landmark) {
                $q->where('address', 'like', '%' . $landmark . '%')
                  ->orWhere('description', 'like', '%' . $landmark . '%')
                  ->orWhere('locality', 'like', '%' . $landmark . '%');
            });
        }

        // 4. Filter by budget
        if ($budget) {
            $query->where('price', '<=', $budget);
        }

        // 5. Filter by gender
        if ($gender) {
            $query->where(function ($q) use ($gender) {
                $q->where('title', 'like', '%' . $gender . '%')
                  ->orWhere('description', 'like', '%' . $gender . '%');

                if ($gender === 'boys') {
                    $q->orWhere('title', 'like', '%male%')
                      ->orWhere('description', 'like', '%male%')
                      ->orWhere('title', 'like', '%gents%')
                      ->orWhere('description', 'like', '%gents%');
                } elseif ($gender === 'girls') {
                    $q->orWhere('title', 'like', '%female%')
                      ->orWhere('description', 'like', '%female%')
                      ->orWhere('title', 'like', '%ladies%')
                      ->orWhere('description', 'like', '%ladies%');
                }
            });
        }

        // Execute query with pagination
        $properties = $query->latest()->paginate(9)->withQueryString();

        // Fallback recommendations if zero properties found
        $recommendations = collect();
        if ($properties->isEmpty()) {
            $recommendations = Property::approved()
                ->with(['primaryImage', 'category', 'owner'])
                ->when($city, function ($q) use ($city) {
                    $q->where('location', 'like', '%' . $city . '%');
                })
                ->latest()
                ->limit(4)
                ->get();

            // If still empty, get any featured or latest properties
            if ($recommendations->isEmpty()) {
                $recommendations = Property::approved()
                    ->with(['primaryImage', 'category', 'owner'])
                    ->latest()
                    ->limit(4)
                    ->get();
            }
        }

        // Construct SEO tags and titles dynamically
        $seoTitleStr = $this->buildSeoTitle($typeDisplay, $city, $locality, $landmark, $gender, $budget, $isNearMe);
        $metaDescription = $this->buildMetaDescription($typeDisplay, $city, $locality, $landmark, $gender, $budget, $properties->total(), $isNearMe);

        // Generate JSON-LD Schemas
        $schemas = $this->generateSchemas($seo_slug, $seoTitleStr, $metaDescription, $typeDisplay, $city, $locality, $landmark, $gender, $budget, $properties, $isNearMe);

        return view('seo.landing', [
            'properties' => $properties,
            'recommendations' => $recommendations,
            'meta_title' => $seoTitleStr,
            'meta_description' => $metaDescription,
            'h1_title' => $seoTitleStr,
            'seo_slug' => $seo_slug,
            'city' => $city,
            'locality' => $locality,
            'landmark' => $landmark,
            'type' => $type,
            'typeDisplay' => $typeDisplay,
            'gender' => $gender,
            'budget' => $budget,
            'isNearMe' => $isNearMe,
            'schemas' => $schemas,
        ]);
    }

    /**
     * Parse slug against standard programmatic SEO patterns.
     */
    protected function parseSlug($slug)
    {
        $cleanSlug = strtolower(trim($slug));

        // Pattern 0: High-Priority "Near Me" / "Near My Location" Slugs
        $nearMeMap = [
            'room-near-my-location' => 'room',
            'rooms-near-my-location' => 'room',
            'room-for-rent-near-me' => 'room',
            'rooms-for-rent-near-me' => 'room',
            'room-near-me' => 'room',
            'rooms-near-me' => 'room',
            'single-room-for-rent-near-me' => 'room',
            'single-room-near-me' => 'room',
            '1rk-near-my-location' => 'room',
            '1rk-room-near-me' => 'room',
            'pg-near-me' => 'pg',
            'pg-near-my-location' => 'pg',
            'hostel-near-me' => 'pg',
            'flat-for-rent-near-me' => 'flat',
            'flats-for-rent-near-me' => 'flat',
            'flats-near-my-location' => 'flat',
            'apartment-for-rent-near-me' => 'apartment',
            'apartments-near-my-location' => 'apartment',
            'house-for-rent-near-me' => 'house',
            'houses-near-my-location' => 'house',
            'houses-near-me' => 'house',
        ];

        if (isset($nearMeMap[$cleanSlug])) {
            return [
                'type' => $nearMeMap[$cleanSlug],
                'is_near_me' => true,
            ];
        }

        // Pattern 0b: Regex for arbitrary "type-for-rent-near-me" or "type-near-my-location"
        if (preg_match('/^(single-room|room|rooms|pg|flat|flats|house|houses|apartment|apartments)-(near-my-location|for-rent-near-me|near-me|for-rent-near-my-location)$/i', $cleanSlug, $matches)) {
            $rawType = strtolower($matches[1]);
            $type = match($rawType) {
                'single-room', 'room', 'rooms' => 'room',
                'pg' => 'pg',
                'flat', 'flats', 'apartment', 'apartments' => 'flat',
                'house', 'houses' => 'house',
                default => 'room'
            };

            return [
                'type' => $type,
                'is_near_me' => true,
            ];
        }

        // Pattern 4: pg-for-(boys|girls|students|professionals)-in-([a-z0-9\-]+)
        if (preg_match('/^pg-for-(boys|girls|students|professionals)-in-([a-z0-9\-]+)$/i', $slug, $matches)) {
            return [
                'type' => 'pg',
                'gender' => strtolower($matches[1]),
                'location_slug' => $matches[2],
            ];
        }

        // Pattern 5: pg-near-([a-z0-9\-]+)
        if (preg_match('/^pg-near-([a-z0-9\-]+)$/i', $slug, $matches)) {
            $loc = $matches[1];
            if ($loc === 'me' || $loc === 'my-location') {
                return ['type' => 'pg', 'is_near_me' => true];
            }
            return [
                'type' => 'pg',
                'landmark_slug' => $loc,
            ];
        }

        // Patterns 1, 2, 3: (room|pg|flat|house|apartment)-for-rent-in-([a-z0-9\-]+)
        if (preg_match('/^(room|pg|flat|house|apartment)-for-rent-in-([a-z0-9\-]+)$/i', $slug, $matches)) {
            $type = strtolower($matches[1]);
            $remaining = $matches[2];

            $budget = null;
            if (preg_match('/-under-(\d+)$/i', $remaining, $subMatches)) {
                $budget = (int)$subMatches[1];
                $remaining = preg_replace('/-under-\d+$/i', '', $remaining);
            }

            return [
                'type' => $type,
                'budget' => $budget,
                'location_slug' => $remaining,
            ];
        }

        // Pattern 6: (room|pg|flat|house|apartment)-for-rent-near-([a-z0-9\-]+)
        if (preg_match('/^(room|pg|flat|house|apartment)-for-rent-near-([a-z0-9\-]+)$/i', $slug, $matches)) {
            $type = strtolower($matches[1]);
            $remaining = $matches[2];

            if ($remaining === 'me' || $remaining === 'my-location') {
                return ['type' => $type, 'is_near_me' => true];
            }

            return [
                'type' => $type,
                'landmark_slug' => $remaining,
            ];
        }

        return null;
    }

    /**
     * Resolve city and locality from location slug.
     */
    protected function resolveLocation($locationSlug)
    {
        $targetCities = [
            'mumbai', 'delhi', 'noida', 'gurgaon', 'bangalore', 'bengaluru', 'pune',
            'chennai', 'kolkata', 'hyderabad', 'ahmedabad', 'jaipur', 'lucknow',
            'chandigarh', 'navi-mumbai', 'thane', 'ghaziabad', 'faridabad', 'alibaug',
            'powai', 'bandra', 'andheri', 'juhu', 'borivali', 'worli', 'vashi', 'chembur', 'colaba'
        ];

        $locationSlug = strtolower($locationSlug);
        $city = null;
        $locality = null;

        // Sort by length descending to match longer strings first (e.g. navi-mumbai over mumbai)
        usort($targetCities, function ($a, $b) {
            return strlen($b) - strlen($a);
        });

        foreach ($targetCities as $targetCity) {
            if ($locationSlug === $targetCity) {
                $city = $targetCity;
                break;
            } elseif (str_ends_with($locationSlug, '-' . $targetCity)) {
                $city = $targetCity;
                $locality = substr($locationSlug, 0, -strlen($targetCity) - 1);
                break;
            }
        }

        if (!$city) {
            $city = $locationSlug;
        }

        return [
            'city' => ucwords(str_replace('-', ' ', $city)),
            'locality' => $locality ? ucwords(str_replace('-', ' ', $locality)) : null,
        ];
    }

    /**
     * Resolve landmark and city from landmark slug.
     */
    protected function resolveLandmark($landmarkSlug)
    {
        $targetCities = [
            'mumbai', 'delhi', 'noida', 'gurgaon', 'bangalore', 'bengaluru', 'pune',
            'chennai', 'kolkata', 'hyderabad', 'ahmedabad', 'jaipur', 'lucknow',
            'chandigarh', 'navi-mumbai', 'thane', 'ghaziabad', 'faridabad', 'alibaug',
            'powai', 'bandra', 'andheri', 'juhu', 'borivali', 'worli', 'vashi', 'chembur', 'colaba'
        ];

        $landmarkSlug = strtolower($landmarkSlug);
        $city = null;
        $landmark = $landmarkSlug;

        usort($targetCities, function ($a, $b) {
            return strlen($b) - strlen($a);
        });

        foreach ($targetCities as $targetCity) {
            if (str_ends_with($landmarkSlug, '-' . $targetCity)) {
                $city = $targetCity;
                $landmark = substr($landmarkSlug, 0, -strlen($targetCity) - 1);
                break;
            }
        }

        return [
            'city' => $city ? ucwords(str_replace('-', ' ', $city)) : null,
            'landmark' => ucwords(str_replace('-', ' ', $landmark)),
        ];
    }

    /**
     * Build SEO Optimized Page Title.
     */
    protected function buildSeoTitle($typeDisplay, $city, $locality, $landmark, $gender, $budget, $isNearMe = false)
    {
        if ($isNearMe && !$city && !$locality && !$landmark) {
            if ($typeDisplay === 'Room') {
                return 'Room Near My Location | Rooms For Rent Near Me (Zero Brokerage) - UnlockRentals';
            } elseif ($typeDisplay === 'PG & Co-Living') {
                return 'PG Near My Location | Screened PG & Co-Living Near Me - UnlockRentals';
            } elseif ($typeDisplay === 'Flat' || $typeDisplay === 'Apartment') {
                return 'Flats For Rent Near My Location | 1BHK, 2BHK Near Me - UnlockRentals';
            } elseif ($typeDisplay === 'House') {
                return 'House For Rent Near My Location | Verified Homes Near Me - UnlockRentals';
            }
            return 'Rental Properties Near My Location | Zero Brokerage - UnlockRentals';
        }

        $parts = [];

        if ($gender) {
            $parts[] = ucwords($gender) . ' PG';
        } else {
            // Pluralize type for readability
            $parts[] = Str::plural($typeDisplay);
        }

        $parts[] = 'for Rent';

        if ($isNearMe) {
            $parts[] = 'Near My Location';
        } elseif ($locality && $city) {
            $parts[] = 'in ' . $locality . ', ' . $city;
        } elseif ($city) {
            $parts[] = 'in ' . $city;
        } elseif ($landmark) {
            $parts[] = 'near ' . $landmark;
        }

        if ($budget) {
            $parts[] = 'under ₹' . number_format($budget);
        }

        return implode(' ', $parts) . ' | UnlockRentals';
    }

    /**
     * Build dynamic Meta Description.
     */
    protected function buildMetaDescription($typeDisplay, $city, $locality, $landmark, $gender, $budget, $total, $isNearMe = false)
    {
        if ($isNearMe && !$city && !$locality && !$landmark) {
            return "Searching for " . strtolower($typeDisplay) . " near your location? Find 100% verified single rooms, 1RK, 1BHK flats & PGs for rent near you with zero brokerage. Direct owner contact, transparent pricing & instant visit booking.";
        }

        $locationStr = '';
        if ($isNearMe) {
            $locationStr = "near your location";
        } elseif ($locality && $city) {
            $locationStr = "in $locality, $city";
        } elseif ($city) {
            $locationStr = "in $city";
        } elseif ($landmark) {
            $locationStr = "near $landmark";
        }

        $budgetStr = $budget ? " under ₹" . number_format($budget) : "";
        $genderStr = $gender ? " specifically for " . $gender : "";
        $propertyCountStr = $total > 0 ? "Choose from $total verified listings." : "Find verified listing options.";

        return "Looking for " . Str::plural(strtolower($typeDisplay)) . " for rent $locationStr$budgetStr$genderStr? $propertyCountStr Amenities include modern kitchen, 24/7 security, power backup, and nearby metro connectivity.";
    }

    /**
     * Generate JSON-LD Schema structures.
     */
    protected function generateSchemas($slug, $title, $description, $typeDisplay, $city, $locality, $landmark, $gender, $budget, $properties, $isNearMe = false)
    {
        $baseUrl = url('/');
        $pageUrl = url($slug);

        // 1. Breadcrumb Schema
        $breadcrumbs = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => 'Home',
                    'item' => $baseUrl,
                ],
            ]
        ];

        $currentPos = 2;
        if ($city) {
            $citySlug = Str::slug($typeDisplay . '-for-rent-in-' . $city);
            $breadcrumbs['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => $currentPos++,
                'name' => "$typeDisplay in $city",
                'item' => url($citySlug),
            ];
        }

        if ($locality) {
            $breadcrumbs['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => $currentPos++,
                'name' => "$locality",
                'item' => $pageUrl,
            ];
        } else {
            $breadcrumbs['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => $currentPos++,
                'name' => $isNearMe ? "$typeDisplay Near My Location" : $title,
                'item' => $pageUrl,
            ];
        }

        // 2. LocalBusiness / RealEstateAgent Schema
        $localBusiness = [
            '@context' => 'https://schema.org',
            '@type' => 'RealEstateAgent',
            'name' => 'UnlockRentals ' . ($city ?? 'India'),
            'description' => $description,
            'url' => $pageUrl,
            'telephone' => '+91-94254-55499',
            'logo' => asset('images/logo.png'),
            'image' => asset('images/hero-bg.jpg'),
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => $locality ?? $city ?? 'Gurgaon',
                'addressRegion' => $city ?? 'Haryana',
                'addressCountry' => 'IN',
            ],
            'priceRange' => '₹₹'
        ];

        // 3. FAQ Schema with High-Intent "Near Me" Q&A
        $minPrice = $properties->isNotEmpty() ? $properties->min('price') : 3000;
        $maxPrice = $properties->isNotEmpty() ? $properties->max('price') : 25000;
        $avgPrice = $properties->isNotEmpty() ? round($properties->avg('price')) : 8000;

        $locationName = $isNearMe ? 'your location' : ($locality ?? $city ?? $landmark ?? 'this location');

        $faqsList = [
            [
                '@type' => 'Question',
                'name' => "How do I find a $typeDisplay near my location with zero brokerage?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => "On UnlockRentals, you can search and discover verified " . Str::plural(strtolower($typeDisplay)) . " near your current location directly by owners. Use our interactive location filter to view 100% genuine listings without paying any broker commission."
                ]
            ],
            [
                '@type' => 'Question',
                'name' => "What is the average rent for $typeDisplay in $locationName?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => "The average rent of verified " . Str::plural(strtolower($typeDisplay)) . " in $locationName is approximately ₹" . number_format($avgPrice) . " per month, with budget options starting from ₹" . number_format($minPrice) . " and premium units up to ₹" . number_format($maxPrice) . "."
                ]
            ],
            [
                '@type' => 'Question',
                'name' => "Can I schedule a visit to inspect rooms near my location before booking?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => "Yes! You can use our 'Book a Visit' feature on any listing to schedule a private walkthrough at a convenient time slot directly with the property owner."
                ]
            ],
            [
                '@type' => 'Question',
                'name' => "What security amenities are provided in PGs and flats near my location?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => "Properties listed on UnlockRentals typically include essential security amenities such as 24/7 CCTV surveillance, gated community security, power backup, and digital biometric access."
                ]
            ]
        ];

        $faqs = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $faqsList
        ];

        return [
            'breadcrumbs' => json_encode($breadcrumbs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
            'localBusiness' => json_encode($localBusiness, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
            'faqs' => json_encode($faqs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
        ];
    }

    /**
     * Handle curated category, city, locality, and commercial pages from seo_keywords.xlsx.
     */
    protected function handleCuratedLandingPage(Request $request, array $keywordItem)
    {
        $city = !empty($keywordItem['city']) ? $keywordItem['city'] : null;
        $locality = !empty($keywordItem['locality']) ? $keywordItem['locality'] : null;
        $landmark = null;
        $category = $keywordItem['category'] ?? '';
        $keyword = strtolower($keywordItem['keyword'] ?? '');
        $seoSlug = ltrim($keywordItem['url_slug'] ?? '', '/');
        $metaTitle = $keywordItem['seo_title'];
        $metaDescription = $keywordItem['meta_description'];
        $isNearMe = str_contains($keyword, 'near me') || str_contains($seoSlug, 'near-me') || str_contains($seoSlug, 'near-my-location');

        // Dynamic query-time overrides
        if ($request->filled('district')) {
            $city = ucwords(str_replace('-', ' ', $request->district));
        } elseif ($request->filled('city')) {
            $city = ucwords(str_replace('-', ' ', $request->city));
        }
        if ($request->filled('locality')) {
            $locality = ucwords(str_replace('-', ' ', $request->locality));
        }

        // Determine type display and filter logic
        $type = 'property';
        $typeDisplay = 'Property';
        $budget = null;
        $gender = null;

        if (str_contains($keyword, 'house') || str_contains($keyword, 'home') || str_contains($keyword, 'villa')) {
            $type = 'house';
            $typeDisplay = 'House';
        } elseif (str_contains($keyword, 'pg') || str_contains($keyword, 'hostel') || str_contains($keyword, 'co-living')) {
            $type = 'pg';
            $typeDisplay = 'PG & Co-Living';
        } elseif (str_contains($keyword, 'room') || str_contains($keyword, '1rk')) {
            $type = 'room';
            $typeDisplay = 'Room';
        } elseif (str_contains($keyword, 'flat') || str_contains($keyword, 'apartment') || str_contains($keyword, 'bhk')) {
            $type = 'flat';
            $typeDisplay = 'Flat';
        } elseif (str_contains($keyword, 'commercial') || str_contains($keyword, 'office') || str_contains($keyword, 'shop') || str_contains($keyword, 'warehouse')) {
            $type = 'commercial';
            $typeDisplay = 'Commercial';
        }

        // Check gender
        if (str_contains($keyword, 'boys') || str_contains($keyword, 'male') || str_contains($keyword, 'gents')) {
            $gender = 'boys';
        } elseif (str_contains($keyword, 'girls') || str_contains($keyword, 'female') || str_contains($keyword, 'ladies')) {
            $gender = 'girls';
        }

        // Check budget in keyword (e.g. under 5000, under 10000, under 20000)
        if (preg_match('/under\s*(\d+)/i', $keyword, $bMatch)) {
            $budget = (int)$bMatch[1];
        }

        // Build database query
        $query = Property::approved()->with(['primaryImage', 'category', 'owner']);

        // Type filtering
        if ($type === 'room') {
            $query->where(function ($q) {
                $q->where('bedrooms', 1)
                  ->orWhere('bedrooms', 0)
                  ->orWhere('title', 'like', '%room%')
                  ->orWhere('title', 'like', '%1rk%')
                  ->orWhere('description', 'like', '%room%');
            });
        } elseif ($type === 'pg') {
            $query->where(function ($q) {
                $q->where('type', 'pg-hostel')
                  ->orWhere('title', 'like', '%pg%')
                  ->orWhere('title', 'like', '%hostel%')
                  ->orWhere('description', 'like', '%pg%');
            });
        } elseif ($type === 'flat') {
            $query->where(function ($q) {
                $q->whereHas('category', function ($catQ) {
                    $catQ->where('name', 'like', '%flat%')
                         ->orWhere('name', 'like', '%apartment%');
                })->orWhere('title', 'like', '%flat%')
                  ->orWhere('title', 'like', '%apartment%')
                  ->orWhere('description', 'like', '%flat%')
                  ->orWhere('description', 'like', '%apartment%');
            });
        } elseif ($type === 'house') {
            $query->where(function ($q) {
                $q->where('type', 'house')
                  ->orWhereHas('category', function ($catQ) {
                      $catQ->where('name', 'like', '%house%')
                           ->orWhere('name', 'like', '%villa%');
                  })->orWhere('title', 'like', '%house%')
                    ->orWhere('title', 'like', '%villa%');
            });
        } elseif ($type === 'commercial') {
            $query->where(function ($q) {
                $q->where('type', 'commercial')
                  ->orWhere('title', 'like', '%commercial%')
                  ->orWhere('title', 'like', '%office%')
                  ->orWhere('title', 'like', '%shop%')
                  ->orWhere('description', 'like', '%commercial%');
            });
        }

        // Bedroom category filters
        if ($category === '1 BHK Rental' || str_contains($keyword, '1 bhk') || str_contains($keyword, '1bhk')) {
            $query->where('bedrooms', 1);
        } elseif ($category === '2 BHK Rental' || str_contains($keyword, '2 bhk') || str_contains($keyword, '2bhk')) {
            $query->where('bedrooms', 2);
        } elseif ($category === 'Large Homes & Villas' || str_contains($keyword, '3 bhk') || str_contains($keyword, '4 bhk')) {
            $query->where('bedrooms', '>=', 3);
        }

        // Furnishing filter
        if (str_contains($keyword, 'furnished')) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%furnished%')
                  ->orWhere('description', 'like', '%furnished%');
            });
        }

        // Location / Locality filtering
        if ($city) {
            $query->where(function ($q) use ($city) {
                $q->where('location', 'like', '%' . $city . '%')
                  ->orWhere('address', 'like', '%' . $city . '%');
            });
        }

        if ($locality) {
            $query->where(function ($q) use ($locality) {
                $q->where('locality', 'like', '%' . $locality . '%')
                  ->orWhere('location', 'like', '%' . $locality . '%')
                  ->orWhere('address', 'like', '%' . $locality . '%');
            });
        }

        // Budget filtering
        if ($budget) {
            $query->where('price', '<=', $budget);
        }

        // Gender filtering
        if ($gender) {
            $query->where(function ($q) use ($gender) {
                $q->where('title', 'like', '%' . $gender . '%')
                  ->orWhere('description', 'like', '%' . $gender . '%');
            });
        }

        $properties = $query->latest()->paginate(9)->withQueryString();

        // Fallback recommendations if zero properties found
        $recommendations = collect();
        if ($properties->isEmpty()) {
            $recommendations = Property::approved()
                ->with(['primaryImage', 'category', 'owner'])
                ->when($city, function ($q) use ($city) {
                    $q->where('location', 'like', '%' . $city . '%');
                })
                ->latest()
                ->limit(4)
                ->get();

            if ($recommendations->isEmpty()) {
                $recommendations = Property::approved()
                    ->with(['primaryImage', 'category', 'owner'])
                    ->latest()
                    ->limit(4)
                    ->get();
            }
        }

        $schemas = $this->generateSchemas($seoSlug, $metaTitle, $metaDescription, $typeDisplay, $city, $locality, $landmark, $gender, $budget, $properties, $isNearMe);

        return view('seo.landing', [
            'properties' => $properties,
            'recommendations' => $recommendations,
            'meta_title' => $metaTitle,
            'meta_description' => $metaDescription,
            'h1_title' => $metaTitle,
            'seo_slug' => $seoSlug,
            'city' => $city,
            'locality' => $locality,
            'landmark' => $landmark,
            'type' => $type,
            'typeDisplay' => $typeDisplay,
            'gender' => $gender,
            'budget' => $budget,
            'isNearMe' => $isNearMe,
            'schemas' => $schemas,
            'keywordItem' => $keywordItem,
        ]);
    }

    /**
     * Generate Schema for Owner Listing Landing Pages.
     */
    protected function generateOwnerSchemas($slug, $title, $description)
    {
        $pageUrl = url($slug);
        $breadcrumbs = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'List Property', 'item' => $pageUrl],
            ]
        ];

        $localBusiness = [
            '@context' => 'https://schema.org',
            '@type' => 'RealEstateAgent',
            'name' => 'UnlockRentals Property Listing Portal',
            'description' => $description,
            'url' => $pageUrl,
            'telephone' => '+91-94254-55499',
            'address' => [
                '@type' => 'PostalAddress',
                'addressCountry' => 'IN',
            ],
            'priceRange' => '₹0'
        ];

        $faqs = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => [
                [
                    '@type' => 'Question',
                    'name' => 'Is it free to list my rental property on UnlockRentals?',
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Yes, listing your property is 100% free with zero brokerage. Direct owner contact is provided to verified tenants.']
                ],
                [
                    '@type' => 'Question',
                    'name' => 'How quickly will I get tenant inquiries?',
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Most landlords receive verified tenant calls and WhatsApp inquiries within 24-48 hours.']
                ]
            ]
        ];

        return [
            'breadcrumbs' => json_encode($breadcrumbs, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT),
            'localBusiness' => json_encode($localBusiness, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT),
            'faqs' => json_encode($faqs, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT),
        ];
    }

    /**
     * Generate Schema for Rental Guide / Blog Landing Pages.
     */
    protected function generateGuideSchemas($slug, $title, $description, $keyword)
    {
        $pageUrl = url($slug);
        $breadcrumbs = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Rental Guides', 'item' => url('/blog')],
                ['@type' => 'ListItem', 'position' => 3, 'name' => $keyword, 'item' => $pageUrl],
            ]
        ];

        $faqs = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => [
                [
                    '@type' => 'Question',
                    'name' => 'How do I find a rental property without paying brokerage?',
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Search on UnlockRentals to view 100% direct owner rental houses, flats, and rooms with zero broker fees.']
                ],
                [
                    '@type' => 'Question',
                    'name' => 'What documents are required for rental agreement verification?',
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Aadhaar Card, PAN Card, permanent address proof, and passport size photographs of both tenant and owner.']
                ]
            ]
        ];

        $article = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => str_replace(' | UnlockRentals', '', $title),
            'description' => $description,
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => $pageUrl,
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'UnlockRentals',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('images/logo.png'),
                ],
            ],
            'datePublished' => '2026-01-01T00:00:00+05:30',
            'dateModified' => now()->toIso8601String(),
        ];

        return [
            'breadcrumbs' => json_encode($breadcrumbs, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT),
            'faqs' => json_encode($faqs, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT),
            'article' => json_encode($article, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT),
        ];
    }

    /**
     * Get list of all programmatic page URLs.
     */
    public static function getProgrammaticUrls()
    {
        // 1. Get all 1020 curated slugs
        $curatedSlugs = SeoKeywordService::getAllSlugs();
        $urls = array_map(fn($s) => '/' . ltrim($s, '/'), $curatedSlugs);

        // 2. High-Intent "Near Me" and dynamic URLs
        $urls = array_merge($urls, [
            '/room-near-my-location',
            '/rooms-near-my-location',
            '/room-for-rent-near-me',
            '/rooms-for-rent-near-me',
            '/single-room-for-rent-near-me',
            '/1rk-near-my-location',
            '/pg-near-my-location',
            '/pg-near-me',
            '/flat-for-rent-near-me',
            '/flats-near-my-location',
            '/house-for-rent-near-me',
            '/houses-near-my-location',
        ]);

        return array_values(array_unique($urls));
    }
}

