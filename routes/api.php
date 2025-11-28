<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PPDBApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned to the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public API routes
Route::get('/health', [PPDBApiController::class, 'healthCheck']);

// OTP Routes (Public)
Route::post('/send-otp', [App\Http\Controllers\OtpController::class, 'sendOtp']);
Route::post('/verify-otp', [App\Http\Controllers\OtpController::class, 'verifyOtp']);

// Protected API routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('v1')->group(function () {
        // Dashboard & Analytics
        Route::get('/dashboard/summary', [PPDBApiController::class, 'dashboardSummary']);
        Route::get('/map/data', [PPDBApiController::class, 'mapData']);
        
        // Workflow API
        Route::post('/workflow/verify-applicant/{applicant}', [App\Http\Controllers\WorkflowController::class, 'verifyApplicant']);
        Route::post('/workflow/verify-payment/{payment}', [App\Http\Controllers\WorkflowController::class, 'verifyPayment']);
        Route::post('/workflow/final-decision/{applicant}', [App\Http\Controllers\WorkflowController::class, 'finalDecision']);
        Route::get('/workflow/status/{applicant}', [App\Http\Controllers\WorkflowController::class, 'getWorkflowStatus']);
    });
});