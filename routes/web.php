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
    $query = \App\Models\Property::approved()
        ->with(['primaryImage', 'owner']);
    
    // Apply Filter by state
    if ($request->filled('state')) {
        $query->where('state', $request->state);
    }

    // Apply Filter by district
    if ($request->filled('district')) {
        $districtName = str_replace('-', ' ', $request->district);
        $query->where('location', 'like', '%' . $districtName . '%');
    }

    // Apply Filter by locality
    if ($request->filled('locality')) {
        $localityName = str_replace('-', ' ', $request->locality);
        $query->where(function($q) use ($localityName) {
            $q->where('locality', 'like', '%' . $localityName . '%')
              ->orWhere('location', 'like', '%' . $localityName . '%')
              ->orWhere('address', 'like', '%' . $localityName . '%');
        });
    }

    // Filter by type
    if ($request->filled('type') && $request->type !== 'all') {
        $query->where('type', $request->type);
    }

    // Handle string-based price range
    if ($request->filled('price') && $request->price !== 'any') {
        if ($request->price === '0-20000') {
            $query->where('price', '<=', 20000);
        } elseif ($request->price === '20000-50000') {
            $query->whereBetween('price', [20000, 50000]);
        } elseif ($request->price === '50000-plus') {
            $query->where('price', '>=', 50000);
        }
    }

    // Filter by UI rooms configuration
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

    // Filter by purpose
    if ($request->filled('purpose')) {
        $query->where('purpose', $request->purpose);
    }

    $featuredRentals = $query->latest()->get();

    return view('welcome', compact('featuredRentals'));
})->name('home');

Route::post('/feedback', [\App\Http\Controllers\SupportController::class, 'storeFeedback'])->name('feedback.store');
Route::post('/chatbot/save', [\App\Http\Controllers\SupportController::class, 'saveChatMessage'])->name('chatbot.save');
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
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
    Route::get('/feedback', [AdminController::class, 'feedback'])->name('feedback');
    Route::get('/chats', [AdminController::class, 'chats'])->name('chats');
    Route::get('/callbacks', [AdminController::class, 'callbacks'])->name('callbacks');

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
