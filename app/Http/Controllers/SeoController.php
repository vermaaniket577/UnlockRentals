<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SeoController extends Controller
{
    /**
     * Handle programmatic SEO landing pages dynamically.
     */
    public function handle(Request $request, $seo_slug)
    {
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
                  ->orWhere('title', 'like', '%room%')
                  ->orWhere('description', 'like', '%room%');
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
                ->with(['primaryImage', 'category'])
                ->when($city, function ($q) use ($city) {
                    $q->where('location', 'like', '%' . $city . '%');
                })
                ->latest()
                ->limit(4)
                ->get();

            // If still empty, get any featured or latest properties
            if ($recommendations->isEmpty()) {
                $recommendations = Property::approved()
                    ->with(['primaryImage', 'category'])
                    ->latest()
                    ->limit(4)
                    ->get();
            }
        }

        // Construct SEO tags and titles dynamically
        $seoTitleStr = $this->buildSeoTitle($typeDisplay, $city, $locality, $landmark, $gender, $budget);
        $metaDescription = $this->buildMetaDescription($typeDisplay, $city, $locality, $landmark, $gender, $budget, $properties->total());

        // Generate JSON-LD Schemas
        $schemas = $this->generateSchemas($seo_slug, $seoTitleStr, $metaDescription, $typeDisplay, $city, $locality, $landmark, $gender, $budget, $properties);

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
            'schemas' => $schemas,
        ]);
    }

    /**
     * Parse slug against standard programmatic SEO patterns.
     */
    protected function parseSlug($slug)
    {
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
            return [
                'type' => 'pg',
                'landmark_slug' => $matches[1],
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
    protected function buildSeoTitle($typeDisplay, $city, $locality, $landmark, $gender, $budget)
    {
        $parts = [];

        if ($gender) {
            $parts[] = ucwords($gender) . ' PG';
        } else {
            // Pluralize type for readability
            $parts[] = Str::plural($typeDisplay);
        }

        $parts[] = 'for Rent';

        if ($locality && $city) {
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
    protected function buildMetaDescription($typeDisplay, $city, $locality, $landmark, $gender, $budget, $total)
    {
        $locationStr = '';
        if ($locality && $city) {
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
    protected function generateSchemas($slug, $title, $description, $typeDisplay, $city, $locality, $landmark, $gender, $budget, $properties)
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
            // Add budget/gender level to breadcrumbs if no locality
            if ($budget || $gender || $landmark) {
                $breadcrumbs['itemListElement'][] = [
                    '@type' => 'ListItem',
                    'position' => $currentPos++,
                    'name' => $title,
                    'item' => $pageUrl,
                ];
            }
        }

        // 2. LocalBusiness Schema
        $localBusiness = [
            '@context' => 'https://schema.org',
            '@type' => 'RealEstateAgent',
            'name' => 'UnlockRentals ' . ($city ?? 'India'),
            'description' => $description,
            'url' => $pageUrl,
            'telephone' => '+91-98765-43210',
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

        // 3. FAQ Schema
        $minPrice = $properties->isNotEmpty() ? $properties->min('price') : 3000;
        $maxPrice = $properties->isNotEmpty() ? $properties->max('price') : 25000;
        $avgPrice = $properties->isNotEmpty() ? round($properties->avg('price')) : 8000;

        $locationName = $locality ?? $city ?? $landmark ?? 'this location';

        $faqs = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => [
                [
                    '@type' => 'Question',
                    'name' => "What is the average rent for $typeDisplay in $locationName?",
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => "The average rent of verified " . Str::plural(strtolower($typeDisplay)) . " in $locationName is approximately ₹" . number_format($avgPrice) . " per month, with options starting as low as ₹" . number_format($minPrice) . " and luxury units up to ₹" . number_format($maxPrice) . "."
                    ]
                ],
                [
                    '@type' => 'Question',
                    'name' => "Are there budget-friendly rental options available in $locationName?",
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => "Yes, there are several budget-friendly options available. You can filter by price limit or check listings under ₹" . number_format($budget ?? 10000) . " on our platform."
                    ]
                ],
                [
                    '@type' => 'Question',
                    'name' => "What security amenities are provided in PGs and flats listed in $locationName?",
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => "Most rental properties listed on UnlockRentals in $locationName come with security amenities such as 24/7 CCTV surveillance, security guards at gate, gated community access, power backup, and intercom facilities."
                    ]
                ]
            ]
        ];

        return [
            'breadcrumbs' => json_encode($breadcrumbs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
            'localBusiness' => json_encode($localBusiness, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
            'faqs' => json_encode($faqs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
        ];
    }

    /**
     * Get list of all programmatic page URLs.
     */
    public static function getProgrammaticUrls()
    {
        $urls = [];

        // Get unique cities
        $cities = Property::approved()
            ->whereNotNull('location')
            ->distinct()
            ->pluck('location')
            ->toArray();

        // Clean up city names
        $cleanCities = [];
        foreach ($cities as $city) {
            if (str_contains($city, ',')) {
                $parts = explode(',', $city);
                $cleanCity = trim(end($parts));
            } else {
                $cleanCity = trim($city);
            }
            $cleanCities[] = strtolower($cleanCity);
        }

        // Add standard target cities
        $cleanCities = array_merge($cleanCities, ['gurgaon', 'noida', 'delhi', 'mumbai', 'bangalore']);
        $cleanCities = array_unique(array_filter($cleanCities));

        // Get unique localities
        $localitiesData = Property::approved()
            ->whereNotNull('locality')
            ->where('locality', '!=', '')
            ->get(['locality', 'location']);

        $types = ['flat', 'room', 'pg', 'house'];

        foreach ($cleanCities as $city) {
            $citySlug = Str::slug($city);
            if (empty($citySlug)) continue;

            foreach ($types as $type) {
                $urls[] = "/{$type}-for-rent-in-{$citySlug}";
            }

            // Add budget variations
            $urls[] = "/flat-for-rent-in-{$citySlug}-under-20000";
            $urls[] = "/room-for-rent-in-{$citySlug}-under-10000";

            // Add gender variations
            $urls[] = "/pg-for-boys-in-{$citySlug}";
            $urls[] = "/pg-for-girls-in-{$citySlug}";
        }

        foreach ($localitiesData as $loc) {
            $city = $loc->location;
            if (str_contains($city, ',')) {
                $parts = explode(',', $city);
                $city = trim(end($parts));
            }
            $citySlug = Str::slug($city);
            $locSlug = Str::slug($loc->locality);

            if (empty($citySlug) || empty($locSlug)) continue;

            $urls[] = "/flat-for-rent-in-{$locSlug}-{$citySlug}";
            $urls[] = "/room-for-rent-in-{$locSlug}-{$citySlug}";
            $urls[] = "/pg-for-rent-in-{$locSlug}-{$citySlug}";
        }

        return array_values(array_unique($urls));
    }
}

