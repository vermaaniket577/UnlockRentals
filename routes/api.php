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

/*
|--------------------------------------------------------------------------
| External Leads Dispatcher API
|--------------------------------------------------------------------------
*/
Route::match(['get', 'post'], '/leads/send-all', [\App\Http\Controllers\ExternalLeadApiController::class, 'sendAll']);
Route::match(['get', 'post'], '/leads/send/{id}', [\App\Http\Controllers\ExternalLeadApiController::class, 'sendSingle']);

/*
|--------------------------------------------------------------------------
| Incoming Leads & Admission Enquiries Intake API
|--------------------------------------------------------------------------
*/
Route::post('/leads', [\App\Http\Controllers\LeadController::class, 'store']);
Route::match(['post', 'put'], '/general-enquiry/create', [\App\Http\Controllers\LeadController::class, 'storeAdmissionLead']);
Route::match(['post', 'put'], '/v1/api/general-enquiry/create', [\App\Http\Controllers\LeadController::class, 'storeAdmissionLead']);
Route::match(['post', 'put'], '/leads/admission', [\App\Http\Controllers\LeadController::class, 'storeAdmissionLead']);
Route::match(['post', 'put'], '/admission-leads', [\App\Http\Controllers\LeadController::class, 'storeAdmissionLead']);


