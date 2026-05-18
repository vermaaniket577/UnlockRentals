<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Show the landing page with featured properties.
     */
    public function index()
    {
        $featuredProperties = Cache::remember('home_featured_properties', 300, function () {
            return Property::approved()
                ->featured()
                ->with(['primaryImage', 'category', 'owner'])
                ->latest()
                ->take(6)
                ->get();
        });

        $latestProperties = Cache::remember('home_latest_properties', 300, function () {
            return Property::approved()
                ->with(['primaryImage', 'category', 'owner'])
                ->latest()
                ->take(8)
                ->get();
        });

        $categories = Cache::remember('home_categories', 3600, function () {
            return Category::withCount(['properties' => function ($query) {
                $query->where('status', 'approved');
            }])->get();
        });

        $stats = Cache::remember('home_stats', 3600, function () {
            return [
                'total_properties' => Property::approved()->count(),
                'total_houses' => Property::approved()->ofType('house')->count(),
                'total_shops' => Property::approved()->ofType('shop')->count(),
                'total_locations' => Property::approved()->distinct('location')->count('location'),
            ];
        });

        return view('home', compact('featuredProperties', 'latestProperties', 'categories', 'stats'));
    }
}
