<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the landing page with featured properties.
     */
    public function index()
    {
        $featuredProperties = Property::approved()
            ->featured()
            ->with(['primaryImage', 'category', 'owner'])
            ->latest()
            ->take(6)
            ->get();

        $latestProperties = Property::approved()
            ->with(['primaryImage', 'category', 'owner'])
            ->latest()
            ->take(8)
            ->get();

        $categories = Category::withCount(['properties' => function ($query) {
            $query->where('status', 'approved');
        }])->get();

        $stats = [
            'total_properties' => Property::approved()->count(),
            'total_houses' => Property::approved()->ofType('house')->count(),
            'total_shops' => Property::approved()->ofType('shop')->count(),
            'total_locations' => Property::approved()->distinct('location')->count('location'),
        ];

        return view('home', compact('featuredProperties', 'latestProperties', 'categories', 'stats'));
    }
}
