<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\Category;
use App\Http\Requests\StorePropertyRequest;
use App\Http\Requests\UpdatePropertyRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class PropertyController extends Controller
{
    /**
     * Display a listing of approved properties with search/filter.
     */
    public function index(Request $request)
    {
        $query = Property::approved()
            ->with(['primaryImage', 'category', 'owner']);

        // Filter by type (house/shop)
        if ($request->filled('type')) {
            $query->ofType($request->type);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by price range
        if ($request->filled('min_price') || $request->filled('max_price')) {
            $query->priceBetween($request->min_price, $request->max_price);
        }

        // Filter by location
        if ($request->filled('location')) {
            $query->inLocation($request->location);
        }

        if ($request->filled('state')) {
            $query->where('state', $request->state);
        }

        // Smart Search Home Page Filters:
        if ($request->filled('district')) {
            $districtName = str_replace('-', ' ', $request->district);
            $query->where('location', 'like', '%' . $districtName . '%');
        }

        // Filter by locality / area
        if ($request->filled('locality')) {
            $localityName = str_replace('-', ' ', $request->locality);
            $query->where(function ($q) use ($localityName) {
                $q->where('locality', 'like', '%' . $localityName . '%')
                  ->orWhere('location', 'like', '%' . $localityName . '%')
                  ->orWhere('address', 'like', '%' . $localityName . '%');
            });
        }

        // Handle string-based price range
        if ($request->filled('price') && $request->price !== 'any') {
            if ($request->price === '0-20000') {
                $query->where('price', '<=', 20000);
            } elseif ($request->price === '20000-50000') {
                $query->priceBetween(20000, 50000);
            } elseif ($request->price === '50000-plus') {
                $query->where('price', '>=', 50000);
            }
        }

        // Filter by UI rooms configuration
        if ($request->filled('rooms') && $request->rooms !== 'any') {
            if ($request->rooms === '1rk') {
                $query->where('bedrooms', 0); // RK is 0 bedrooms
            } elseif ($request->rooms === '1bhk') {
                $query->where('bedrooms', 1);
            } elseif ($request->rooms === '2bhk') {
                $query->where('bedrooms', 2);
            } elseif ($request->rooms === '3bhk') {
                $query->where('bedrooms', 3);
            } elseif ($request->rooms === '4bhk-plus') {
                $query->where('bedrooms', '>=', 4);
            }
        } elseif ($request->filled('bedrooms')) {
            // Fallback for older direct parameter
            $query->where('bedrooms', '>=', $request->bedrooms);
        }

        // Filter by furnishing
        if ($request->filled('furnishing')) {
            $query->where('furnishing', $request->furnishing);
        }

        // Search by keyword
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            default:
                $query->latest();
        }

        $properties = $query->paginate(12)->withQueryString();
        $categories = Cache::remember('all_categories', 3600, fn () => Category::all());

        // Get unique locations for filter dropdown (cached 30 min)
        $locations = Cache::remember('approved_locations', 1800, function () {
            return Property::approved()
                ->select('location')
                ->distinct()
                ->pluck('location');
        });

        return view('properties.index', compact('properties', 'categories', 'locations'));
    }

    /**
     * Display the specified property.
     */
    public function show(Property $property)
    {
        // Only show approved properties to public (owners/admins can see theirs)
        if (!$property->isApproved()) {
            if (!auth()->check() || (auth()->user()->id !== $property->user_id && !auth()->user()->isAdmin())) {
                abort(404);
            }
        }

        $property->load(['images', 'category', 'owner']);

        $similarProperties = Property::approved()
            ->where('id', '!=', $property->id)
            ->where(function ($query) use ($property) {
                $query->where('type', $property->type)
                      ->orWhere('category_id', $property->category_id)
                      ->orWhere('location', $property->location);
            })
            ->with(['primaryImage', 'category', 'owner'])
            ->take(4)
            ->get();

        return view('properties.show', compact('property', 'similarProperties'));
    }

    /**
     * Show the form for creating a new property.
     */
    public function create()
    {
        $categories = Category::all();
        return view('properties.create', compact('categories'));
    }

    /**
     * Store a newly created property.
     */
    public function store(StorePropertyRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $bypassApproval = \App\Models\Setting::get('bypass_property_approval', '0') == '1';
        $data['status'] = $bypassApproval ? 'approved' : 'pending';

        // If user is a tenant, upgrade to owner upon posting a property
        $user = auth()->user();
        if ($user->role === 'tenant') {
            $user->update(['role' => 'owner']);
        }

        // Remove images from the data array before creating
        unset($data['images'], $data['primary_image']);

        $property = Property::create($data);

        // Handle image uploads — save to disk for fast serving
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('properties/' . $property->id, 'public');

                PropertyImage::create([
                    'property_id' => $property->id,
                    'path'        => $path,
                    'image_data'  => null,
                    'is_primary'  => ($index === (int) $request->get('primary_image', 0)),
                    'sort_order'  => $index,
                ]);
            }
        }

        $successMsg = $bypassApproval 
            ? 'Property posted successfully! It is now live on the website.' 
            : 'Property submitted successfully! It will be visible after admin approval.';

        return redirect()->route('dashboard')
            ->with('success', $successMsg);
    }

    /**
     * Show the form for editing the specified property.
     */
    public function edit(Property $property)
    {
        $this->authorize('update', $property);

        $property->load('images');
        $categories = Category::all();
        return view('properties.edit', compact('property', 'categories'));
    }

    /**
     * Update the specified property.
     */
    public function update(UpdatePropertyRequest $request, Property $property)
    {
        $this->authorize('update', $property);

        $bypassApproval = \App\Models\Setting::get('bypass_property_approval', '0') == '1';
        $data['status'] = $bypassApproval ? 'approved' : 'pending';

        // Remove images from the data array before updating
        unset($data['images'], $data['primary_image'], $data['remove_images']);

        $property->update($data);

        // Handle removing selected images
        if ($request->filled('remove_images')) {
            $imagesToRemove = PropertyImage::whereIn('id', $request->remove_images)
                ->where('property_id', $property->id)
                ->get();

            foreach ($imagesToRemove as $image) {
                Storage::disk('public')->delete($image->path);
                $image->delete();
            }
        }

        // Handle new image uploads — save to disk
        if ($request->hasFile('images')) {
            $maxOrder = $property->images()->max('sort_order') ?? -1;
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('properties/' . $property->id, 'public');

                PropertyImage::create([
                    'property_id' => $property->id,
                    'path'        => $path,
                    'image_data'  => null,
                    'is_primary'  => false,
                    'sort_order'  => $maxOrder + $index + 1,
                ]);
            }
        }

        // Update primary image if specified
        if ($request->filled('primary_image')) {
            $property->images()->update(['is_primary' => false]);
            PropertyImage::where('id', $request->primary_image)
                ->where('property_id', $property->id)
                ->update(['is_primary' => true]);
        }

        $successMsg = $bypassApproval 
            ? 'Property updated successfully! Changes are live.' 
            : 'Property updated successfully! It will be reviewed by admin.';

        return redirect()->route('dashboard')
            ->with('success', $successMsg);
    }

    /**
     * Remove the specified property.
     */
    public function destroy(Property $property)
    {
        $this->authorize('delete', $property);

        // Delete all associated images from storage
        foreach ($property->images as $image) {
            Storage::disk('public')->delete($image->path);
        }

        // Delete the property directory
        Storage::disk('public')->deleteDirectory('properties/' . $property->id);

        $property->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Property deleted successfully.');
    }

    /**
     * Book a property visit.
     */
    public function bookVisit(Request $request, Property $property)
    {
        $user = auth()->user();

        // Check if user has an active plan, or if they are admin or owner of the property
        if (!$user->isAdmin() && $user->id !== $property->user_id && !$user->hasActivePlan()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'redirect' => route('plans.index'),
                    'message' => 'Please purchase a plan to book a visit.',
                ], 403);
            }
            return redirect()->route('plans.index')->with('error', 'Please purchase a plan to book a visit.');
        }

        $data = $request->validate([
            'preferred_date' => ['required', 'date', 'after_or_equal:today'],
            'preferred_time' => ['required', 'string', 'in:morning,afternoon,evening'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'message' => ['nullable', 'string', 'max:1000'],
        ]);

        // Prevent duplicate bookings for same property on same date
        $existing = \App\Models\VisitBooking::where('user_id', $user->id)
            ->where('property_id', $property->id)
            ->where('preferred_date', $data['preferred_date'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->first();

        if ($existing) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You already have a visit booked for this property on this date.',
                ], 422);
            }
            return redirect()->back()->with('error', 'You already have a visit booked for this property on this date.');
        }

        \App\Models\VisitBooking::create([
            'user_id' => $user->id,
            'property_id' => $property->id,
            'preferred_date' => $data['preferred_date'],
            'preferred_time' => $data['preferred_time'],
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'] ?? $user->email,
            'message' => $data['message'] ?? null,
            'status' => 'pending',
        ]);

        Cache::forget('admin_notifications');

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Visit booked successfully! The property owner will confirm your visit shortly.',
            ]);
        }

        return redirect()->back()->with('success', 'Visit booked successfully! The property owner will confirm your visit shortly.');
    }

    /**
     * Request a callback from the property agent/owner.
     */
    public function requestCallback(Request $request, Property $property)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
        ]);

        // Prevent spamming: max 1 callback request per property per day
        $existing = \App\Models\CallbackRequest::where('user_id', $user->id)
            ->where('property_id', $property->id)
            ->where('status', 'new')
            ->where('created_at', '>=', now()->subDay())
            ->first();

        if ($existing) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You already have a pending callback request for this property. Our agent will call you soon!',
                ], 422);
            }
            return redirect()->back()->with('error', 'You already have a pending callback request for this property.');
        }

        \App\Models\CallbackRequest::create([
            'user_id' => $user->id,
            'property_id' => $property->id,
            'session_id' => 'property_' . $property->id . '_' . $user->id,
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $user->email,
            'status' => 'new',
        ]);

        Cache::forget('admin_notifications');

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Callback request submitted! Our agent will call you shortly.',
            ]);
        }

        return redirect()->back()->with('success', 'Callback request submitted! Our agent will call you shortly.');
    }

    /**
     * Toggle the booked status of a property.
     */
    public function toggleBooked(Request $request, Property $property)
    {
        // Enforce owner / admin permission
        if (auth()->id() != $property->user_id && !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $property->is_booked = !$property->is_booked;
        $property->save();

        // Clear homepage cache to update watermark instantly
        \Illuminate\Support\Facades\Cache::forget('home_featured_rentals');

        return redirect()->back()->with('success', $property->is_booked 
            ? 'Property marked as Booked!' 
            : 'Property marked as Available!');
    }
}
