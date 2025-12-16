<?php

use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserConsentController;
use App\Http\Controllers\API\InterestController;
use App\Http\Controllers\API\PasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public auth routes
Route::post('/register', [AuthController::class, 'signup']);
Route::post('/request-otp', [AuthController::class, 'requestOtp']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);

// Routes that require authentication
Route::middleware('auth:sanctum')->group(function () {

    // Profile APIs (authenticated)
    Route::post('/logout', [AuthController::class, 'logout']);


    // Logout and delete account
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::delete('/account/delete', [AuthController::class, 'deleteAccount']);

    // Profile
    Route::get('/profile', [ProfileController::class, 'getProfile']);
    Route::put('/update_profile', [ProfileController::class, 'updateProfile']);

    // User intrest
    Route::get('/user/interests', [InterestController::class, 'getUserInterests']);
});

// User Details
Route::post('/user_profile', [ProfileController::class, 'store']);

// Interests
Route::get('/interests', [InterestController::class, 'getAllInterests']);
Route::post('/user/interests', [InterestController::class, 'saveUserInterests']);

// Consent APIs
Route::get('/termsAndPrivacy', [UserConsentController::class, 'index']);
Route::get('/termsAndPrivacy/{slug}', [UserConsentController::class, 'show']);
Route::post('/termsAndPrivacy', [UserConsentController::class, 'update']);
// Route::get('/account/export', [UserConsentController::class, 'exportUserData']);

Route::get('/auth/google', [AuthController::class, 'googleRedirect']);
Route::get('/auth/google/callback', [AuthController::class, 'googleCallback']);

Route::get('/auth/apple', [AuthController::class, 'appleRedirect']);
Route::post('/auth/apple/callback', [AuthController::class, 'appleCallback']);