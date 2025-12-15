<?php

use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserConsentController;
use App\Http\Controllers\API\InterestController;
use App\Http\Controllers\API\PasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public auth routes
Route::post('/auth/register', [AuthController::class, 'signup']);
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
Route::get('/account/export', [UserConsentController::class, 'exportUserData']);


// User Intrests
Route::get('/interests', [InterestController::class, 'getAllInterests']);
Route::post('/user/interests', [InterestController::class, 'saveUserInterests']);
Route::get('/user/interests', [InterestController::class, 'getUserInterests']);
Route::post('/auth/logout', [AuthController::class, 'logout']);
Route::delete('/account/delete', [AuthController::class, 'deleteAccount']);

// Progressive Profiling
Route::put('/user_profile', [ProfileController::class, 'update']);
