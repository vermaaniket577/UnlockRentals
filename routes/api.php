<?php

use App\Http\Controllers\RazorpayPaymentController;
use Illuminate\Support\Facades\Route;

Route::post('/create-order', [RazorpayPaymentController::class, 'createOrder']);
Route::post('/verify-payment', [RazorpayPaymentController::class, 'verifyPayment']);

/*
|--------------------------------------------------------------------------
| Local Professionals Marketplace API v1
|--------------------------------------------------------------------------
*/
Route::prefix('v1')->group(function () {
    Route::get('/professional-categories', [\App\Http\Controllers\Api\ProfessionalApiController::class, 'categories']);
    Route::get('/professional-services', [\App\Http\Controllers\Api\ProfessionalApiController::class, 'services']);
    Route::get('/professionals', [\App\Http\Controllers\Api\ProfessionalApiController::class, 'index']);
    Route::get('/professionals/nearby', [\App\Http\Controllers\Api\ProfessionalApiController::class, 'nearby']);
    Route::get('/professionals/{slug}', [\App\Http\Controllers\Api\ProfessionalApiController::class, 'show']);
    Route::post('/service-requests', [\App\Http\Controllers\Api\ProfessionalApiController::class, 'createServiceRequest']);
});

