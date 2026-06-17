<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function(Illuminate\Http\Request $request) {
    $hasFilters = $request->hasAny(['state','district','locality','type','price','rooms','purpose']);

    if (!$hasFilters) {
        // Cache unfiltered homepage listings for 5 minutes
        $featuredRentals = \Illuminate\Support\Facades\Cache::remember('home_featured_rentals', 300, function () {
            return \App\Models\Property::approved()
                ->with(['primaryImage', 'owner'])
                ->latest()
                ->take(18)
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
            } elseif ($request->rooms === '3bhk') {
                $query->where('bedrooms', 3);
            } elseif ($request->rooms === '4bhk-plus') {
                $query->where('bedrooms', '>=', 4);
            }
        }
        if ($request->filled('purpose')) {
            $query->where('purpose', $request->purpose);
        }

        $featuredRentals = $query->latest()->take(18)->get();
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
            ->get()
            ->keyBy('plan_id');
    }

    return view('welcome', compact('featuredRentals', 'feedbacks', 'userOffers'));
})->name('home');

// Serve a property image directly from binary DB data
Route::get('/property-image/{id}', function ($id) {
    $image = \App\Models\PropertyImage::findOrFail($id);

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

Route::post('/feedback', [\App\Http\Controllers\SupportController::class, 'storeFeedback'])->name('feedback.store');
Route::post('/chatbot/save', [\App\Http\Controllers\SupportController::class, 'saveChatMessage'])->name('chatbot.save');
Route::get('/chatbot/history/{session_id}', [\App\Http\Controllers\SupportController::class, 'getChatHistory'])->name('chatbot.history');
Route::post('/chatbot/callback', [\App\Http\Controllers\SupportController::class, 'requestCallback'])->name('chatbot.callback');

// Property browsing (public)
Route::get('/properties', [PropertyController::class, 'index'])->name('properties.index');

// Plans browsing (public)
Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');

// Dynamic Sitemap Route
Route::get('/sitemap.xml', function () {
    $properties = \App\Models\Property::approved()->latest('updated_at')->get();
    $programmaticUrls = \App\Http\Controllers\SeoController::getProgrammaticUrls();
    return response()->view('sitemap', [
        'properties' => $properties,
        'programmaticUrls' => $programmaticUrls
    ])->header('Content-Type', 'text/xml');
});

// App Download Landing Page
Route::get('/app', function () {
    return view('app.download');
})->name('app.download');

// Direct APK Download
Route::get('/download/apk', function () {
    $apkUrl = \App\Models\Setting::where('key', 'app_apk_download_url')->value('value');
    
    if (!$apkUrl || $apkUrl === '#') {
        abort(404, 'APK download is not available yet.');
    }

    // If it's an external URL, redirect to it
    if (str_starts_with($apkUrl, 'http')) {
        return redirect($apkUrl);
    }

    // If it's a local file path
    $filePath = public_path($apkUrl);
    if (!file_exists($filePath)) {
        abort(404, 'APK file not found.');
    }

    return response()->download($filePath, 'UnlockRentals.apk', [
        'Content-Type' => 'application/vnd.android.package-archive'
    ]);
})->name('app.download.apk');

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
    Route::get('auth/{provider}/callback', [AuthController::class, 'handleProviderCallback'])->name('social.callback');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
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
    Route::get('/callbacks', [AdminController::class, 'callbacks'])->name('callbacks');
    Route::get('/resets', [AdminController::class, 'resets'])->name('resets');
    Route::post('/resets/{email}/delete', [AdminController::class, 'deleteReset'])->name('resets.delete');
    Route::post('/resets/send', [AdminController::class, 'sendReset'])->name('resets.send');

    // Plan management
    Route::get('/plans', [AdminController::class, 'plans'])->name('plans');
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

// Fallback route to serve images directly from storage/app/public without a symlink
Route::get('/property-image-file/{path}', function ($path) {
    $fullPath = storage_path('app/public/' . $path);
    if (!file_exists($fullPath)) {
        abort(404);
    }
    return response()->file($fullPath);
})->where('path', '.*')->name('property.image.file');

// Dynamic Catch-All Route for Programmatic SEO Pages
Route::get('/{seo_slug}', [\App\Http\Controllers\SeoController::class, 'handle'])->name('seo.landing');
