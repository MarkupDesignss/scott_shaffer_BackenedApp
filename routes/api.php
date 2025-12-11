<?php

use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserConsentController;
use Illuminate\Support\Facades\Route;

// Public auth routes
Route::post('/auth/signup', [AuthController::class, 'signup']);
Route::post('/auth/request-otp', [AuthController::class, 'requestOtp']);
Route::post('/auth/verify-otp', [AuthController::class, 'verifyOtp']);

// Routes that require authentication
Route::middleware('auth:sanctum')->group(function () {

    // Profile APIs (authenticated)
    Route::get('/profile', [ProfileController::class, 'getProfile']);
    Route::put('/profile', [ProfileController::class, 'updateProfile']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
});

    // Consent APIs
    Route::get('/consent', [UserConsentController::class, 'show']);
    Route::post('/consent', [UserConsentController::class, 'update']);
    Route::get('/user/export', [UserConsentController::class, 'exportUserData']);
    Route::delete('/user/delete', [UserConsentController::class, 'deleteAccount']);

    // Progressive Profiling
    Route::put('/user_profile', [ProfileController::class, 'update']);