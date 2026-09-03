<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\OtpController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function(Illuminate\Http\Request $request) {
    $hasFilters = $request->hasAny(['state','district','locality','type','price','rooms','purpose','sort','availability','unbooked','media']);

    if (!$hasFilters) {
        // Cache unfiltered homepage listings for 5 minutes
        $featuredRentals = \Illuminate\Support\Facades\Cache::remember('home_featured_rentals', 300, function () {
            return \App\Models\Property::approved()
                ->with(['primaryImage', 'owner'])
                ->latest()
                ->take(24)
                ->get();
        });
    } else {
        $query = \App\Models\Property::approved()
            ->with(['primaryImage', 'owner']);

        if ($request->filled('state')) {
            $query->where('state', $request->state);
        }
        if ($request->filled('district')) {
            $districtName = str_replace('-', ' ', $request->district);
            $query->where('location', 'like', '%' . $districtName . '%');
        }
        if ($request->filled('locality')) {
            $localityName = str_replace('-', ' ', $request->locality);
            $query->where(function($q) use ($localityName) {
                $q->where('locality', 'like', '%' . $localityName . '%')
                  ->orWhere('location', 'like', '%' . $localityName . '%')
                  ->orWhere('address', 'like', '%' . $localityName . '%');
            });
        }
        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }
        if ($request->filled('price') && $request->price !== 'any') {
            if ($request->price === '0-20000') {
                $query->where('price', '<=', 20000);
            } elseif ($request->price === '20000-50000') {
                $query->whereBetween('price', [20000, 50000]);
            } elseif ($request->price === '50000-plus') {
                $query->where('price', '>=', 50000);
            }
        }
        if ($request->filled('rooms') && $request->rooms !== 'any') {
            if ($request->rooms === '1rk') {
                $query->where('bedrooms', 0);
            } elseif ($request->rooms === '1bhk') {
                $query->where('bedrooms', 1);
            } elseif ($request->rooms === '2bhk') {
                $query->where('bedrooms', 2);
            } elseif (in_array($request->rooms, ['3bhk', '3bhk-plus', '3plus', '3+'])) {
                $query->where('bedrooms', '>=', 3);
            } elseif ($request->rooms === '4bhk-plus') {
                $query->where('bedrooms', '>=', 4);
            }
        }
        if ($request->filled('purpose') && $request->purpose !== 'any') {
            $query->where('purpose', $request->purpose);
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

        $featuredRentals = $query->take(24)->get();
    }

    $feedbacks = \Illuminate\Support\Facades\Cache::remember('home_approved_feedbacks', 300, function () {
        return \App\Models\Feedback::with('user')
            ->where('status', 'approved')
            ->latest()
            ->take(3)
            ->get();
    });

    $userOffers = collect();
    if (auth()->check()) {
        $userOffers = \App\Models\PrivateUserOffer::where('user_id', auth()->id())
            ->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->get();
    }

    return view('welcome', compact('featuredRentals', 'feedbacks', 'userOffers'));
})->name('home');

// Serve a property image directly from binary DB data
Route::get('/property-image/{id}', function ($id) {
    $image = \App\Models\PropertyImage::withoutGlobalScope('withoutBlob')->findOrFail($id);

    if (empty($image->image_data)) {
        abort(404);
    }

    // Detect MIME type from the binary data
    $finfo = new \finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->buffer($image->image_data);
    if (!$mimeType || !str_starts_with($mimeType, 'image/')) {
        $mimeType = 'image/jpeg';
    }

    return response($image->image_data, 200)
        ->header('Content-Type', $mimeType)
        ->header('Cache-Control', 'public, max-age=604800, immutable');
})->name('property.image');

// Public CSRF Token Refresh Endpoint
Route::get('/csrf-token', function () {
    return response()->json(['csrf_token' => csrf_token()]);
})->name('csrf.token');

Route::post('/feedback', [\App\Http\Controllers\SupportController::class, 'storeFeedback'])->name('feedback.store');
Route::post('/chatbot/save', [\App\Http\Controllers\SupportController::class, 'saveChatMessage'])->name('chatbot.save');
Route::get('/chatbot/history/{session_id}', [\App\Http\Controllers\SupportController::class, 'getChatHistory'])->name('chatbot.history');
Route::post('/chatbot/callback', [\App\Http\Controllers\SupportController::class, 'requestCallback'])->name('chatbot.callback');

// Property browsing & Post Free Advertise (public)
Route::get('/properties', [PropertyController::class, 'index'])->name('properties.index');
Route::get('/post-free-advertise', function () {
    return redirect()->route('properties.create');
})->name('post-free-advertise');
Route::get('/post-free-property', function () {
    return redirect()->route('properties.create');
})->name('post-free-property');
Route::get('/post-property-advertise', function () {
    return redirect()->route('properties.create');
});

// Plans browsing (public)
Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');

// Blog & Real Estate Guides (public)
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Process & How It Works (public)
Route::get('/how-it-works', function () {
    return view('how-it-works');
})->name('how-it-works');
Route::get('/process', function () {
    return redirect()->route('how-it-works');
})->name('process');

// Interactive HTML Site Map Page (User & Crawler Hub)
Route::get('/sitemap', function () {
    $properties = \App\Models\Property::approved()->with('primaryImage')->latest('updated_at')->take(48)->get();
    $blogs = \Illuminate\Support\Facades\Schema::hasTable('blogs') ? \App\Models\Blog::published()->latest('updated_at')->take(12)->get() : collect();
    $programmaticUrls = \App\Http\Controllers\SeoController::getProgrammaticUrls();

    return view('sitemap-html', compact('properties', 'blogs', 'programmaticUrls'));
})->name('sitemap.html');
Route::get('/site-map', function () {
    return redirect()->route('sitemap.html');
});

// Dynamic XML Sitemap Route (Search Engine Crawler Compliant)
Route::get('/sitemap.xml', function () {
    $properties = \App\Models\Property::approved()->with('primaryImage')->latest('updated_at')->get();
    $programmaticUrls = \App\Http\Controllers\SeoController::getProgrammaticUrls();
    
    // Curated blogs from database
    $blogs = \Illuminate\Support\Facades\Schema::hasTable('blogs') ? \App\Models\Blog::published()->latest('updated_at')->get() : collect();

    $baseUrl = rtrim(config('app.url', 'https://www.unlockrentals.com'), '/');

    return response()->view('sitemap', [
        'properties' => $properties,
        'programmaticUrls' => $programmaticUrls,
        'blogs' => $blogs,
        'baseUrl' => $baseUrl
    ])->header('Content-Type', 'application/xml; charset=UTF-8')
      ->header('Cache-Control', 'public, max-age=3600');
})->name('sitemap.xml');

// LLMs.txt routes (Standard for LLM/AI crawlers and knowledge discovery)
Route::get('/llms.txt', function () {
    $filePath = public_path('llms.txt');
    if (file_exists($filePath)) {
        return response(file_get_contents($filePath), 200)
            ->header('Content-Type', 'text/markdown; charset=UTF-8')
            ->header('Cache-Control', 'public, max-age=3600');
    }
    abort(404);
})->name('llms.txt');

Route::get('/.well-known/llms.txt', function () {
    return redirect()->route('llms.txt');
});

Route::get('/llms-full.txt', function () {
    $filePath = public_path('llms-full.txt');
    if (file_exists($filePath)) {
        return response(file_get_contents($filePath), 200)
            ->header('Content-Type', 'text/markdown; charset=UTF-8')
            ->header('Cache-Control', 'public, max-age=3600');
    }
    abort(404);
})->name('llms.full.txt');

// App Download Landing Page
Route::get('/app', function () {
    return view('app.download');
})->name('app.download');

// Direct APK Download (Loop-Protected & Self-Healing with Multi-Location Candidate Fallbacks)
$serveApkResponse = function () {
    $apkSetting = \App\Models\Setting::where('key', 'app_apk_download_url')->value('value');
    
    // Check if external non-local URL (e.g. GitHub Release, Google Drive, S3)
    if ($apkSetting && str_starts_with($apkSetting, 'http')) {
        $parsed = parse_url($apkSetting);
        $currentHost = request()->getHost();
        $isSameHost = !isset($parsed['host']) || $parsed['host'] === $currentHost || str_contains($parsed['host'], 'unlockrentals.com');

        if (!$isSameHost && !str_contains($apkSetting, '/download/apk')) {
            return redirect()->away($apkSetting);
        }
    }

    // Comprehensive search across all possible production & local deployment locations
    $candidates = array_filter([
        public_path('downloads/UnlockRentals.apk'),
        public_path('UnlockRentals.apk'),
        base_path('UnlockRentals-app.apk'),
        base_path('public/downloads/UnlockRentals.apk'),
        base_path('public/UnlockRentals.apk'),
        storage_path('app/public/UnlockRentals.apk'),
        public_path('downloads/UnlockRentals-v4.apk'),
        public_path('downloads/UnlockRentals-v3.apk'),
        public_path('downloads/UnlockRentals-v2.apk'),
        base_path('release_builds/UnlockRentals-release.apk'),
        base_path('android-app/app/build/outputs/apk/release/app-release.apk'),
    ]);

    foreach ($candidates as $filePath) {
        if ($filePath && file_exists($filePath) && is_file($filePath)) {
            return response()->download($filePath, 'UnlockRentals.apk', [
                'Content-Type' => 'application/vnd.android.package-archive',
                'Content-Disposition' => 'attachment; filename="UnlockRentals.apk"',
                'Content-Length' => filesize($filePath),
                'Cache-Control' => 'no-cache, must-revalidate',
            ]);
        }
    }

    // If no local file is found on the server disk, fallback seamlessly to GitHub repository release
    return redirect()->away('https://github.com/vermaaniket577/UnlockRentals/raw/main/public/downloads/UnlockRentals.apk');
};

Route::get('/download/apk', $serveApkResponse)->name('app.download.apk');
Route::get('/UnlockRentals.apk', $serveApkResponse);
Route::get('/downloads/{filename}', function ($filename) use ($serveApkResponse) {
    if (str_ends_with(strtolower($filename), '.apk')) {
        return $serveApkResponse();
    }
    abort(404);
})->where('filename', '.*');

// Fresh CSRF Token Endpoint for cached/stale pages
Route::get('/csrf-token', function () {
    return response()->json([
        'csrf_token' => csrf_token(),
    ])->header('Cache-Control', 'no-cache, no-store, must-revalidate');
})->name('csrf.token');

// Offline Fallback Route
Route::view('/offline', 'errors.offline')->name('offline');

/*
|--------------------------------------------------------------------------
| Guest Routes (not authenticated)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Password Reset Routes
    Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

    // Social Auth
    Route::get('auth/{provider}', [AuthController::class, 'redirectToProvider'])->name('social.redirect');
    Route::get('auth/{provider}/redirect', [AuthController::class, 'redirectToProvider']);
    Route::get('auth/{provider}/callback', [AuthController::class, 'handleProviderCallback'])->name('social.callback');
    Route::get('auth/token-login', [AuthController::class, 'loginWithToken'])->name('auth.token-login');

    // OTP Verification Routes
    Route::post('/otp/send', [OtpController::class, 'send'])->name('otp.send')->middleware('throttle:10,1');
    Route::post('/otp/verify', [OtpController::class, 'verify'])->name('otp.verify')->middleware('throttle:15,1');
    Route::post('/otp/login', [OtpController::class, 'loginWithOtp'])->name('otp.login')->middleware('throttle:10,1');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::match(['GET', 'POST'], '/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Inquiries - any authenticated user can send
    Route::post('/inquiries', [InquiryController::class, 'store'])->name('inquiries.store');

    // Plans - purchase & contact unlock
    Route::post('/plans/{plan}/purchase', [PlanController::class, 'purchase'])->name('plans.purchase');
    Route::get('/plans/{plan}/checkout', [PlanController::class, 'checkout'])->name('plans.checkout');
    Route::post('/plans/{plan}/purchase/process', [PlanController::class, 'processPayment'])->name('plans.purchase.process');
    Route::post('/plans/{plan}/razorpay-order', [PlanController::class, 'createRazorpayOrder'])->name('plans.razorpay.order');
    Route::post('/plans/{plan}/razorpay/order', [PlanController::class, 'createRazorpayOrder']); // Fallback to support cached views/clients
    Route::post('/plans/{plan}/check-order-status', [PlanController::class, 'checkOrderStatus'])->name('plans.check-order-status');
    Route::post('/properties/{property}/unlock-contact', [PlanController::class, 'unlockContact'])->name('properties.unlock-contact');
    Route::post('/properties/{property}/book-visit', [PropertyController::class, 'bookVisit'])->name('properties.book-visit');
    Route::post('/properties/{property}/request-callback', [PropertyController::class, 'requestCallback'])->name('properties.request-callback');

    // Billing & Invoices
    Route::get('/billing/history', [DashboardController::class, 'billingHistory'])->name('billing.history');
    Route::get('/billing/invoice/{userPlan}', [DashboardController::class, 'invoice'])->name('billing.invoice');
    Route::post('/profile/update', [DashboardController::class, 'updateProfile'])->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| Owner Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:owner,admin,tenant'])->group(function () {
    Route::get('/properties/create', [PropertyController::class, 'create'])->name('properties.create');
    Route::post('/properties', [PropertyController::class, 'store'])->name('properties.store');
    Route::get('/properties/{property}/edit', [PropertyController::class, 'edit'])->name('properties.edit');
    Route::put('/properties/{property}', [PropertyController::class, 'update'])->name('properties.update');
    Route::delete('/properties/{property}', [PropertyController::class, 'destroy'])->name('properties.destroy');
    Route::post('/properties/{property}/toggle-booked', [PropertyController::class, 'toggleBooked'])->name('properties.toggle-booked');

    // Owner inquiries
    Route::get('/inquiries', [InquiryController::class, 'index'])->name('inquiries.index');
    Route::get('/inquiries/{inquiry}', [InquiryController::class, 'show'])->name('inquiries.show');
});

// Property Details (Must be at the bottom so it doesn't shadow /create)
Route::get('/properties/{property}', [PropertyController::class, 'show'])->name('properties.show');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/properties', [AdminController::class, 'properties'])->name('properties');
    Route::post('/properties/{property}/approve', [AdminController::class, 'approve'])->name('properties.approve');
    Route::post('/properties/{property}/reject', [AdminController::class, 'reject'])->name('properties.reject');
    Route::post('/properties/toggle-bypass', [AdminController::class, 'toggleBypassApproval'])->name('properties.toggle-bypass');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
    Route::get('/feedback', [AdminController::class, 'feedback'])->name('feedback');
    Route::post('/feedback/{feedback}/approve', [AdminController::class, 'approveFeedback'])->name('feedback.approve');
    Route::post('/feedback/{feedback}/reject', [AdminController::class, 'rejectFeedback'])->name('feedback.reject');
    Route::delete('/feedback/{feedback}', [AdminController::class, 'destroyFeedback'])->name('feedback.delete');
    Route::get('/chats', [AdminController::class, 'chats'])->name('chats');
    Route::post('/chats/reply', [AdminController::class, 'replyChat'])->name('chats.reply');
    Route::get('/callbacks', [AdminController::class, 'callbacks'])->name('callbacks');
    Route::get('/resets', [AdminController::class, 'resets'])->name('resets');
    Route::post('/resets/{email}/delete', [AdminController::class, 'deleteReset'])->name('resets.delete');
    Route::post('/resets/send', [AdminController::class, 'sendReset'])->name('resets.send');

    // Plan management
    Route::get('/plans', [AdminController::class, 'plans'])->name('plans');
    Route::post('/plans/tax-settings', [AdminController::class, 'updateTaxSettings'])->name('plans.tax-settings.update');
    Route::get('/plans/create', [AdminController::class, 'createPlan'])->name('plans.create');
    Route::post('/plans', [AdminController::class, 'storePlan'])->name('plans.store');
    Route::get('/plans/{plan}/edit', [AdminController::class, 'editPlan'])->name('plans.edit');
    Route::put('/plans/{plan}', [AdminController::class, 'updatePlan'])->name('plans.update');
    Route::delete('/plans/{plan}', [AdminController::class, 'destroyPlan'])->name('plans.destroy');

    // Process steps management
    Route::get('/process-steps', [AdminController::class, 'processSteps'])->name('process-steps');
    Route::get('/process-steps/create', [AdminController::class, 'createProcessStep'])->name('process-steps.create');
    Route::post('/process-steps', [AdminController::class, 'storeProcessStep'])->name('process-steps.store');
    Route::get('/process-steps/{processStep}/edit', [AdminController::class, 'editProcessStep'])->name('process-steps.edit');
    Route::put('/process-steps/{processStep}', [AdminController::class, 'updateProcessStep'])->name('process-steps.update');
    Route::delete('/process-steps/{processStep}', [AdminController::class, 'destroyProcessStep'])->name('process-steps.destroy');

    // Locations management
    Route::get('/locations', [AdminController::class, 'locations'])->name('locations');
    Route::post('/locations/states', [AdminController::class, 'storeState'])->name('locations.states.store');
    Route::delete('/locations/states/{state}', [AdminController::class, 'destroyState'])->name('locations.states.destroy');
    Route::post('/locations/districts', [AdminController::class, 'storeDistrict'])->name('locations.districts.store');
    Route::delete('/locations/districts/{district}', [AdminController::class, 'destroyDistrict'])->name('locations.districts.destroy');
    Route::post('/locations/localities', [AdminController::class, 'storeLocality'])->name('locations.localities.store');
    Route::delete('/locations/localities/{locality}', [AdminController::class, 'destroyLocality'])->name('locations.localities.destroy');

    // Subscription management
    Route::get('/subscriptions', [AdminController::class, 'subscriptions'])->name('subscriptions');
    Route::get('/subscriptions/assign', [AdminController::class, 'showAssignSubscriptionForm'])->name('subscriptions.assign');
    Route::post('/subscriptions/assign', [AdminController::class, 'assignSubscription'])->name('subscriptions.store-assign');
    Route::post('/subscriptions/{userPlan}/approve', [AdminController::class, 'approveSubscription'])->name('subscriptions.approve');
    Route::post('/subscriptions/{userPlan}/reject', [AdminController::class, 'rejectSubscription'])->name('subscriptions.reject');
    Route::post('/subscriptions/{userPlan}/cancel', [AdminController::class, 'cancelSubscription'])->name('subscriptions.cancel');
    Route::post('/subscriptions/{userPlan}/update-plan', [AdminController::class, 'updateSubscriptionPlanTier'])->name('subscriptions.update-plan');
    Route::delete('/subscriptions/{userPlan}', [AdminController::class, 'destroySubscription'])->name('subscriptions.destroy');
    Route::get('/users/{user}/activity', [AdminController::class, 'userActivity'])->name('users.activity');

    // Blog management
    Route::get('/blogs', [AdminController::class, 'blogs'])->name('blogs.index');
    Route::get('/blogs/create', [AdminController::class, 'createBlog'])->name('blogs.create');
    Route::post('/blogs', [AdminController::class, 'storeBlog'])->name('blogs.store');
    Route::get('/blogs/{blog}/edit', [AdminController::class, 'editBlog'])->name('blogs.edit');
    Route::put('/blogs/{blog}', [AdminController::class, 'updateBlog'])->name('blogs.update');
    Route::post('/blogs/{blog}/upload-image', [AdminController::class, 'uploadBlogImage'])->name('blogs.upload-image');
    Route::delete('/blogs/{blog}', [AdminController::class, 'destroyBlog'])->name('blogs.destroy');
    Route::post('/blogs/{blog}/toggle-publish', [AdminController::class, 'togglePublishBlog'])->name('blogs.toggle-publish');
    Route::post('/blogs/{blog}/toggle-featured', [AdminController::class, 'toggleFeaturedBlog'])->name('blogs.toggle-featured');
});

// Database Migration & Seeding Route (Securely triggered via key)
Route::get('/run-migrations', function (\Illuminate\Http\Request $request) {
    $key = $request->query('key');
    $expectedKey = env('MIGRATION_KEY', 'UnlockRentalsSecureMigrateKey2026');

    if ($key !== $expectedKey) {
        abort(404);
    }

    // Reset OPcache if enabled to force loading updated files
    if (function_exists('opcache_reset')) {
        opcache_reset();
    }

    try {
        echo "<html><head><title>UnlockRentals Database Setup</title></head><body style='font-family: sans-serif; padding: 20px; line-height: 1.6;'>";
        echo "<h2>UnlockRentals Database Migrations & Seeding</h2>";

        // Canon list of migrations in current codebase
        $localMigrations = [
            '0001_01_01_000000_create_users_table.php',
            '0001_01_01_000001_create_cache_table.php',
            '0001_01_01_000002_create_jobs_table.php',
            '2024_01_01_000003_create_categories_table.php',
            '2024_01_01_000004_create_properties_table.php',
            '2024_01_01_000005_create_property_images_table.php',
            '2024_01_01_000006_create_inquiries_table.php',
            '2026_04_13_180505_create_settings_table.php',
            '2026_04_15_170000_create_plans_table.php',
            '2026_04_15_170001_create_user_plans_table.php',
            '2026_04_15_170002_create_contact_views_table.php',
            '2026_04_16_155846_add_locality_to_properties_table.php',
            '2026_04_16_161707_add_state_to_properties_table.php',
            '2026_04_16_173236_create_feedback_table.php',
            '2026_04_16_175034_create_chatbot_messages_table.php',
            '2026_04_16_180138_create_callback_requests_table.php',
            '2026_04_21_104155_add_binary_data_to_property_images_table.php',
            '2026_05_16_025451_add_purpose_to_properties_table.php',
            '2026_05_17_110254_add_is_read_to_chatbot_messages_table.php',
            '2026_05_20_044315_add_payment_fields_to_user_plans_table.php',
            '2026_05_20_044315_create_coupons_table.php',
            '2026_05_20_044316_add_wallet_to_users_table.php',
            '2026_05_20_044317_create_wallet_transactions_table.php',
            '2026_05_20_044318_create_activity_logs_table.php',
            '2026_05_20_044318_create_referrals_table.php',
            '2026_05_20_044319_create_payment_logs_table.php',
            '2026_05_20_052820_add_is_private_to_plans_table.php',
            '2026_05_20_052820_create_private_user_offers_table.php',
            '2026_05_20_054524_add_discounted_price_to_private_user_offers_table.php',
            '2026_05_20_064500_create_visit_bookings_table.php',
            '2026_05_20_064501_add_property_id_to_callback_requests_table.php',
            '2026_05_23_170001_add_billing_fields_to_user_plans_table.php',
            '2026_05_25_220600_create_locations_tables.php',
            '2026_06_10_184500_create_process_steps_table.php',
            '2026_06_11_110000_add_is_booked_to_properties_table.php',
            '2026_06_16_180000_add_contact_phone_to_properties_table.php',
            '2026_06_18_153728_add_billing_period_to_private_user_offers_table.php',
            '2026_06_19_120000_add_video_path_to_properties_table.php',
            '2026_06_20_100000_create_blogs_table.php',
        ];

        // Check for leftover duplicate migration files on the server
        $migrationPath = database_path('migrations');
        if (is_dir($migrationPath)) {
            $serverFiles = scandir($migrationPath);
            $leftoverFiles = [];
            foreach ($serverFiles as $file) {
                if ($file === '.' || $file === '..' || is_dir($migrationPath . '/' . $file)) {
                    continue;
                }
                if (!in_array($file, $localMigrations)) {
                    $leftoverFiles[] = $file;
                }
            }

            if (!empty($leftoverFiles)) {
                echo "<div style='background: #fee; border: 1px solid #f99; padding: 15px; border-radius: 5px; margin-bottom: 20px; color: #a11;'>";
                echo "<strong>⚠️ Warning: Leftover/Duplicate Migration Files Detected!</strong><br>";
                echo "The following files exist on your server but are NOT part of your current codebase. Please delete them from your server using cPanel File Manager, then refresh this page:<br><ul>";
                foreach ($leftoverFiles as $file) {
                    echo "<li><code>laravel_project/database/migrations/$file</code></li>";
                }
                echo "</ul></div>";
            }
        }

        // Debug: Check if the migration file on disk contains the new changes
        $migrationFile = database_path('migrations/2026_05_23_170001_add_billing_fields_to_user_plans_table.php');
        if (file_exists($migrationFile)) {
            $content = file_get_contents($migrationFile);
            $hasCheck = str_contains($content, 'Schema::hasColumn');
            echo "<div style='background: #eef; padding: 10px; border-radius: 5px; margin-bottom: 15px;'>";
            echo "<strong>File check:</strong> <code>2026_05_23_170001_add_billing_fields_to_user_plans_table.php</code><br>";
            echo "Contains safety check: " . ($hasCheck ? "<span style='color:green;font-weight:bold;'>YES</span>" : "<span style='color:red;font-weight:bold;'>NO</span>") . "<br>";
            echo "</div>";
        }

        // Debug: Check if the seeder file on disk contains the new changes
        $seederFile = database_path('seeders/DatabaseSeeder.php');
        if (file_exists($seederFile)) {
            $content = file_get_contents($seederFile);
            $hasCheck = str_contains($content, 'admin@unlockrentals.com') && str_contains($content, 'exists');
            echo "<div style='background: #eef; padding: 10px; border-radius: 5px; margin-bottom: 15px;'>";
            echo "<strong>File check:</strong> <code>DatabaseSeeder.php</code><br>";
            echo "Contains safety check: " . ($hasCheck ? "<span style='color:green;font-weight:bold;'>YES</span>" : "<span style='color:red;font-weight:bold;'>NO (Please copy and paste the updated DatabaseSeeder.php code into this file on cPanel)</span>") . "<br>";
            echo "</div>";
        } else {
            echo "<div style='background: #ffe; padding: 10px; border-radius: 5px; margin-bottom: 15px; color: red;'>";
            echo "<strong>Warning:</strong> DatabaseSeeder.php file not found at <code>$seederFile</code>";
            echo "</div>";
        }
        
        echo "<strong>Step 0: Cleaning duplicate plans...</strong><br>";
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        \Illuminate\Support\Facades\DB::table('plans')->truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
        echo "Plans table truncated.<br><br>";

        echo "<strong>Step 1: Running migrations...</strong><br>";
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        echo "Output:<br><pre style='background: #f4f4f4; padding: 10px; border-radius: 5px;'>";
        echo htmlspecialchars(\Illuminate\Support\Facades\Artisan::output());
        echo "</pre>";
        
        echo "<strong>Step 2: Running database seeder...</strong><br>";
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
        echo "Output:<br><pre style='background: #f4f4f4; padding: 10px; border-radius: 5px;'>";
        echo htmlspecialchars(\Illuminate\Support\Facades\Artisan::output());
        echo "</pre>";
        
        echo "<h3 style='color: green;'>✓ Database setup completed successfully!</h3>";
        echo "</body></html>";
    } catch (\Exception $e) {
        echo "<h3 style='color: red;'>✗ Error occurred:</h3>";
        echo "<pre style='background: #fff0f0; border: 1px solid red; padding: 10px; color: red;'>";
        echo htmlspecialchars($e->getMessage()) . "\n" . htmlspecialchars($e->getTraceAsString());
        echo "</pre>";
        echo "</body></html>";
    }
})->name('run-migrations');

// Universal route to serve images directly from storage/app/public without a symlink
Route::get('/property-image-file/{path}', function ($path) {
    $cleanPath = ltrim($path, '/');
    $basename = basename($cleanPath);

    $candidates = [
        storage_path('app/public/' . $cleanPath),
        public_path('storage/' . $cleanPath),
        public_path($cleanPath),
        storage_path('app/public/properties/' . $basename),
        storage_path('app/public/properties/images/' . $basename),
        public_path('storage/properties/' . $basename),
        public_path('storage/properties/images/' . $basename),
        public_path('images/' . $basename),
        public_path('blogs/' . $basename),
        public_path('blogs/authors/' . $basename),
        storage_path('app/public/blogs/' . $basename),
        storage_path('app/public/blogs/authors/' . $basename),
    ];

    $foundPath = null;
    foreach ($candidates as $cand) {
        if (file_exists($cand) && is_file($cand)) {
            $foundPath = $cand;
            break;
        }
    }

    if (!$foundPath) {
        $defaultFallback = public_path('images/luxury_sunlit.png');
        if (file_exists($defaultFallback)) {
            return response()->file($defaultFallback, [
                'Content-Type' => 'image/png',
                'Cache-Control' => 'public, max-age=604800',
            ]);
        }
        return redirect('https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=1200&q=80');
    }

    $ext = strtolower(pathinfo($foundPath, PATHINFO_EXTENSION));
    $mime = match($ext) {
        'png' => 'image/png',
        'gif' => 'image/gif',
        'webp' => 'image/webp',
        'svg' => 'image/svg+xml',
        'avif' => 'image/avif',
        default => 'image/jpeg'
    };

    return response()->file($foundPath, [
        'Content-Type' => $mime,
        'Cache-Control' => 'public, max-age=604800',
        'Access-Control-Allow-Origin' => '*',
    ]);
})->where('path', '.*')->name('property.image.file');

// Universal storage file serving route
Route::get('/storage/{path}', function ($path) {
    return redirect()->route('property.image.file', ['path' => $path]);
})->where('path', '.*')->name('storage.file');

// Direct blogs image file route
Route::get('/blogs/authors/{filename}', function ($filename) {
    return redirect()->route('property.image.file', ['path' => 'blogs/authors/' . $filename]);
});
Route::get('/blogs/{filename}', function ($filename) {
    return redirect()->route('property.image.file', ['path' => 'blogs/' . $filename]);
});

// Dedicated video streaming route with HTTP 206 Partial Content & Range header support
Route::get('/property-video-file/{path}', function ($path) {
    $cleanPath = ltrim($path, '/');
    $basename = basename($cleanPath);

    $candidates = [
        storage_path('app/public/' . $cleanPath),
        public_path('storage/' . $cleanPath),
        public_path($cleanPath),
        storage_path('app/public/properties/videos/' . $basename),
        storage_path('app/public/videos/' . $basename),
        public_path('storage/properties/videos/' . $basename),
        public_path('storage/videos/' . $basename),
    ];

    $fullPath = null;
    foreach ($candidates as $cand) {
        if (file_exists($cand) && is_file($cand)) {
            $fullPath = $cand;
            break;
        }
    }

    if (!$fullPath) {
        abort(404);
    }

    $ext = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
    $mime = match($ext) {
        'webm' => 'video/webm',
        'mp4'  => 'video/mp4',
        'mov'  => 'video/quicktime',
        'ogg'  => 'video/ogg',
        'm4v'  => 'video/x-m4v',
        default => 'video/mp4'
    };

    return response()->file($fullPath, [
        'Content-Type' => $mime,
        'Accept-Ranges' => 'bytes',
        'Cache-Control' => 'public, max-age=604800',
        'Access-Control-Allow-Origin' => '*',
    ]);
})->where('path', '.*')->name('property.video.file');

// Instant async video upload endpoint (WhatsApp/Instagram style background upload)
Route::post('/property-video-upload', [App\Http\Controllers\PropertyController::class, 'uploadVideoDirect'])->middleware('auth')->name('property.video.upload');

// Dynamic Location API routes for AJAX cascading
Route::get('/api/locations/districts', function(\Illuminate\Http\Request $request) {
    $stateInput = trim($request->get('state', ''));
    if (!$stateInput) {
        $districts = \App\Models\District::with('state')->orderBy('name')->get();
        return response()->json($districts);
    }
    
    $state = \App\Models\State::where('code', strtoupper($stateInput))
        ->orWhere('code', $stateInput)
        ->orWhere('name', 'like', $stateInput)
        ->orWhere('id', is_numeric($stateInput) ? (int)$stateInput : 0)
        ->first();
        
    if (!$state) {
        return response()->json([]);
    }
    
    $districts = \App\Models\District::where('state_id', $state->id)->orderBy('name')->get();
    return response()->json($districts);
})->name('api.locations.districts');

Route::get('/api/locations/localities', function(\Illuminate\Http\Request $request) {
    $districtInput = trim($request->get('district', ''));
    $stateInput = trim($request->get('state', ''));
    
    $query = \App\Models\Locality::with(['district.state']);
    
    if ($districtInput) {
        $districtName = str_replace('-', ' ', $districtInput);
        $query->whereHas('district', function($q) use ($districtName, $districtInput) {
            $q->where('name', 'like', $districtName)
              ->orWhere('id', is_numeric($districtInput) ? (int)$districtInput : 0);
        });
    } elseif ($stateInput) {
        $query->whereHas('district.state', function($q) use ($stateInput) {
            $q->where('code', strtoupper($stateInput))
              ->orWhere('name', 'like', $stateInput)
              ->orWhere('id', is_numeric($stateInput) ? (int)$stateInput : 0);
        });
    }
    
    $localities = $query->orderBy('name')->get();
    return response()->json($localities);
})->name('api.locations.localities');

// Legal & Compliance Pages
Route::view('/privacy-policy', 'privacy')->name('privacy');
Route::view('/privacy', 'privacy');
Route::view('/terms-and-conditions', 'terms')->name('terms');
Route::view('/terms', 'terms');

// Dynamic Catch-All Route for Programmatic SEO Pages
Route::get('/{seo_slug}', [\App\Http\Controllers\SeoController::class, 'handle'])->name('seo.landing');
