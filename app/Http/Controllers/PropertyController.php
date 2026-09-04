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

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PropertyController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of approved properties with search/filter.
     */
    public function index(Request $request)
    {
        $query = Property::approved()
            ->with(['primaryImage', 'images', 'category', 'owner']);

        // Filter by type (house/shop)
        if ($request->filled('type') && $request->type !== 'all') {
            $query->ofType($request->type);
        }

        // Filter by purpose (rent vs buy/sell)
        if ($request->filled('purpose') && $request->purpose !== 'all' && $request->purpose !== 'any') {
            $purposeVal = ($request->purpose === 'sell') ? 'buy' : $request->purpose;
            $query->where('purpose', $purposeVal);
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
            $query->where(function ($q) use ($districtName) {
                $q->where('location', 'like', '%' . $districtName . '%')
                  ->orWhere('locality', 'like', '%' . $districtName . '%')
                  ->orWhere('address', 'like', '%' . $districtName . '%');
            });
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
            } elseif (in_array($request->rooms, ['3bhk', '3bhk-plus', '3plus', '3+'])) {
                $query->where('bedrooms', '>=', 3);
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

        // Availability / Unbooked Filter
        if (($request->filled('availability') && $request->availability === 'unbooked') || $request->filled('unbooked')) {
            $query->where('is_booked', false);
        }

        // Media Filter (with images or video)
        if ($request->filled('media') && $request->media !== 'all') {
            if ($request->media === 'video') {
                $query->whereNotNull('video_path')->where('video_path', '!=', '')->where('video_path', '!=', '[]');
            } elseif ($request->media === 'images' || $request->media === 'image') {
                $query->whereHas('images');
            }
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
            case 'unbooked':
                $query->orderBy('is_booked', 'asc')->latest();
                break;
            case 'old_to_new':
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'new_to_old':
            case 'newest':
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
        // Require authentication to access full property details and features
        if (!auth()->check()) {
            return redirect()->route('login')->with('info', 'Please sign in to view complete property details and contact verified owners.');
        }

        // Only show approved properties to public (owners/admins can see theirs)
        if (!$property->isApproved()) {
            if (!auth()->user()->isAdmin() && auth()->user()->id !== $property->user_id) {
                abort(404);
            }
        }

        // Automatically unlock contact for paid users with remaining contact views for this property type
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->canViewContact($property) && !$user->hasViewedContact($property)) {
                $user->viewContact($property);
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
        $categories = Cache::remember('active_categories', 86400, fn () => Category::all());
        return view('properties.create', compact('categories'));
    }

    /**
     * Store a newly created property with high performance batch insertion.
     */
    public function store(StorePropertyRequest $request)
    {
        $data = $request->validated();
        if (isset($data['purpose']) && $data['purpose'] === 'sell') {
            $data['purpose'] = 'buy';
        }
        $data['purpose'] = $data['purpose'] ?? 'rent';
        $data['user_id'] = auth()->id();
        $bypassApproval = \App\Models\Setting::get('bypass_property_approval', '0') == '1';
        $data['status'] = $bypassApproval ? 'approved' : 'pending';

        // If user is a tenant, upgrade to owner upon posting a property
        $user = auth()->user();
        if ($user->role === 'tenant') {
            $user->update(['role' => 'owner']);
        }

        if ($request->filled('contact_phone')) {
            if (empty($user->phone)) {
                $user->update(['phone' => $request->contact_phone]);
            }
        }

        // Remove media fields from the data array before creating
        unset($data['images'], $data['primary_image'], $data['video'], $data['videos'], $data['video_url'], $data['video_urls']);

        $property = \Illuminate\Support\Facades\DB::transaction(function () use ($data, $request) {
            $prop = Property::create($data);

            // Handle pre-uploaded async video paths, multi-video files, and video URLs
            $videoPaths = [];
            if ($request->filled('uploaded_video_paths')) {
                foreach ((array)$request->uploaded_video_paths as $uPath) {
                    if (!empty(trim((string)$uPath))) {
                        $videoPaths[] = trim((string)$uPath);
                    }
                }
            }
            if ($request->hasFile('videos')) {
                foreach ($request->file('videos') as $vFile) {
                    $videoPaths[] = $vFile->store('properties/' . $prop->id . '/videos', 'public');
                }
            }
            if ($request->hasFile('video')) {
                $videoPaths[] = $request->file('video')->store('properties/' . $prop->id . '/videos', 'public');
            }
            if ($request->filled('video_urls')) {
                foreach ((array)$request->video_urls as $vUrl) {
                    if (!empty(trim((string)$vUrl))) {
                        $videoPaths[] = trim((string)$vUrl);
                    }
                }
            }
            if ($request->filled('video_url')) {
                $videoPaths[] = trim((string)$request->video_url);
            }

            $videoPaths = array_values(array_unique(array_filter($videoPaths)));
            if (!empty($videoPaths)) {
                $prop->update([
                    'video_path' => count($videoPaths) === 1 ? $videoPaths[0] : json_encode($videoPaths)
                ]);
            }

            // Handle image uploads — save to disk for fast serving
            if ($request->hasFile('images')) {
                $imagesData = [];
                $primaryIndex = (int) $request->get('primary_image', 0);

                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('properties/' . $prop->id, 'public');

                    $imagesData[] = [
                        'property_id' => $prop->id,
                        'path'        => $path,
                        'image_data'  => null,
                        'is_primary'  => ($index === $primaryIndex),
                        'sort_order'  => $index,
                        'created_at'  => now(),
                        'updated_at'  => now(),
                    ];
                }

                if (!empty($imagesData)) {
                    PropertyImage::insert($imagesData);
                }
            }

            return $prop;
        });

        // Invalidate cached locations for search filters
        Cache::forget('approved_locations');

        $successMsg = $bypassApproval 
            ? 'Property posted successfully! It is now live on the website.' 
            : 'Property submitted successfully! It will be visible after admin approval.';

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $successMsg,
                'redirect_url' => route('dashboard'),
                'property_id' => $prop->id
            ]);
        }

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
        $categories = Cache::remember('active_categories', 86400, fn () => Category::all());
        return view('properties.edit', compact('property', 'categories'));
    }

    /**
     * Update the specified property.
     */
    public function update(UpdatePropertyRequest $request, Property $property)
    {
        $this->authorize('update', $property);

        $data = $request->validated();
        if (isset($data['purpose']) && $data['purpose'] === 'sell') {
            $data['purpose'] = 'buy';
        }
        $bypassApproval = \App\Models\Setting::get('bypass_property_approval', '0') == '1';
        $data['status'] = $bypassApproval ? 'approved' : 'pending';

        $owner = $property->owner;
        if ($request->filled('contact_phone') && $owner) {
            if (empty($owner->phone)) {
                $owner->update(['phone' => $request->contact_phone]);
            }
        }

        // Remove media fields from the data array before updating
        unset($data['images'], $data['primary_image'], $data['remove_images'], $data['video'], $data['videos'], $data['video_url'], $data['video_urls'], $data['remove_video'], $data['remove_video_indexes']);

        \Illuminate\Support\Facades\DB::transaction(function () use ($property, $data, $request) {
            $property->update($data);

            // Raw existing video list
            $decoded = json_decode($property->video_path ?? '', true);
            $rawExisting = is_array($decoded) ? $decoded : ($property->video_path ? [$property->video_path] : []);

            // Handle removal of specific video indexes
            if ($request->filled('remove_video_indexes')) {
                $indexesToRemove = (array)$request->remove_video_indexes;
                foreach ($indexesToRemove as $idx) {
                    if (isset($rawExisting[$idx])) {
                        $item = $rawExisting[$idx];
                        if (!filter_var($item, FILTER_VALIDATE_URL)) {
                            Storage::disk('public')->delete($item);
                        }
                        unset($rawExisting[$idx]);
                    }
                }
                $rawExisting = array_values($rawExisting);
            }

            // Handle remove entire video
            if ($request->filled('remove_video') && $request->remove_video) {
                foreach ($rawExisting as $item) {
                    if (!filter_var($item, FILTER_VALIDATE_URL)) {
                        Storage::disk('public')->delete($item);
                    }
                }
                $rawExisting = [];
            }

            // Append pre-uploaded async video paths
            if ($request->filled('uploaded_video_paths')) {
                foreach ((array)$request->uploaded_video_paths as $uPath) {
                    if (!empty(trim((string)$uPath))) {
                        $rawExisting[] = trim((string)$uPath);
                    }
                }
            }

            // Append new uploaded video files
            if ($request->hasFile('videos')) {
                foreach ($request->file('videos') as $vFile) {
                    $rawExisting[] = $vFile->store('properties/' . $property->id . '/videos', 'public');
                }
            }
            if ($request->hasFile('video')) {
                $rawExisting[] = $request->file('video')->store('properties/' . $property->id . '/videos', 'public');
            }

            // Append new video URLs
            if ($request->filled('video_urls')) {
                foreach ((array)$request->video_urls as $vUrl) {
                    if (!empty(trim((string)$vUrl))) {
                        $rawExisting[] = trim((string)$vUrl);
                    }
                }
            }
            if ($request->filled('video_url')) {
                $rawExisting[] = trim((string)$request->video_url);
            }

            $rawExisting = array_values(array_unique(array_filter($rawExisting)));
            $property->update([
                'video_path' => empty($rawExisting) ? null : (count($rawExisting) === 1 ? $rawExisting[0] : json_encode($rawExisting))
            ]);

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
                $newImagesData = [];

                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('properties/' . $property->id, 'public');

                    $newImagesData[] = [
                        'property_id' => $property->id,
                        'path'        => $path,
                        'image_data'  => null,
                        'is_primary'  => false,
                        'sort_order'  => $maxOrder + $index + 1,
                        'created_at'  => now(),
                        'updated_at'  => now(),
                    ];
                }

                if (!empty($newImagesData)) {
                    PropertyImage::insert($newImagesData);
                }
            }

            // Update primary image if specified
            if ($request->filled('primary_image')) {
                $property->images()->update(['is_primary' => false]);
                PropertyImage::where('id', $request->primary_image)
                    ->where('property_id', $property->id)
                    ->update(['is_primary' => true]);
            }
        });

        Cache::forget('approved_locations');

        $successMsg = $bypassApproval 
            ? 'Property updated successfully! Changes are live.' 
            : 'Property updated successfully! It will be reviewed by admin.';

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $successMsg,
                'redirect_url' => route('dashboard'),
                'property_id' => $property->id
            ]);
        }

        return redirect()->route('dashboard')
            ->with('success', $successMsg);
    }

    /**
     * Remove the specified property.
     */
    public function destroy(Property $property)
    {
        $this->authorize('delete', $property);

        // Delete associated video
        if ($property->video_path && !filter_var($property->video_path, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($property->video_path);
        }

        // Delete all associated images from storage
        foreach ($property->images as $image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
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

        if (empty($user->phone) && !empty($data['phone'])) {
            $user->update(['phone' => $data['phone']]);
        }

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

        if (empty($user->phone) && !empty($data['phone'])) {
            $user->update(['phone' => $data['phone']]);
        }

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

    /**
     * Direct high-speed async video upload endpoint.
     */
    public function uploadVideoDirect(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'video' => 'required|file|mimes:mp4,mov,ogg,qt,webm,m4v|max:256000',
        ]);

        $file = $request->file('video');
        $dateFolder = date('Y/m');
        $filename = 'clip_' . time() . '_' . \Illuminate\Support\Str::random(8) . '.' . ($file->getClientOriginalExtension() ?: 'webm');
        $path = $file->storeAs('properties/uploads/' . $dateFolder, $filename, 'public');

        return response()->json([
            'success' => true,
            'path' => $path,
            'url' => route('property.video.file', ['path' => $path])
        ]);
    }
}
