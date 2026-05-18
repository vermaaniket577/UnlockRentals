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
                ->take(12)
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

        $featuredRentals = $query->latest()->take(12)->get();
    }

    return view('welcome', compact('featuredRentals'));
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
    return response()->view('sitemap', [
        'properties' => $properties
    ])->header('Content-Type', 'text/xml');
});

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
    Route::post('/properties/{property}/unlock-contact', [PlanController::class, 'unlockContact'])->name('properties.unlock-contact');
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

    // Subscription management
    Route::get('/subscriptions', [AdminController::class, 'subscriptions'])->name('subscriptions');
    Route::post('/subscriptions/{userPlan}/approve', [AdminController::class, 'approveSubscription'])->name('subscriptions.approve');
    Route::post('/subscriptions/{userPlan}/reject', [AdminController::class, 'rejectSubscription'])->name('subscriptions.reject');
    Route::post('/subscriptions/{userPlan}/cancel', [AdminController::class, 'cancelSubscription'])->name('subscriptions.cancel');
    Route::post('/subscriptions/{userPlan}/update-plan', [AdminController::class, 'updateSubscriptionPlanTier'])->name('subscriptions.update-plan');
    Route::get('/users/{user}/activity', [AdminController::class, 'userActivity'])->name('users.activity');
});
