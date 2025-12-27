<?php

use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\FeatureListController;
use App\Http\Controllers\API\UserConsentController;
use App\Http\Controllers\API\InterestController;
use App\Http\Controllers\API\ListController;
use App\Http\Controllers\API\ListItemController;
use App\Http\Controllers\API\PasswordController;
use App\Http\Controllers\API\RecommenededItemsController;
use App\Http\Controllers\Api\SearchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public auth routes
Route::post('/register', [AuthController::class, 'signup']);
Route::post('/check-user-status', [AuthController::class, 'checkUserStatus']);
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
    Route::post('/update_profile', [ProfileController::class, 'updateProfile']);

    /* =========================
       Lists (CRUD)
    ========================== */
    Route::get('/lists', [ListController::class, 'index']);          // My lists (owner + group)
    Route::post('/lists', [ListController::class, 'store']);         // Create list
    Route::get('/lists/{id}', [ListController::class, 'show']);      // Show list
    Route::put('/lists/{id}', [ListController::class, 'update']);    // Update list
    Route::delete('/lists/{id}', [ListController::class, 'destroy']); // Delete list


    /* =========================
       Group List – Members
    ========================== */
    Route::get('/users/invite-list', [ListController::class, 'inviteUserList']);    // User list for inviting members
    Route::post('/lists/{id}/invite', [ListController::class, 'inviteMembers']);   // Invite members
    Route::get('/lists/invitations', [ListController::class, 'myInvitations']);    // My invites
    Route::post('/lists/{id}/accept', [ListController::class, 'acceptInvite']);    // Accept invite
    Route::post('/lists/{id}/reject', [ListController::class, 'rejectInvite']);    // Reject invite
    Route::delete('/lists/{id}/members/{userId}', [ListController::class, 'removeMember']); // Remove member
    Route::post('/lists/{id}/leave', [ListController::class, 'leaveGroup']);       // Leave group

    Route::post('/lists/{list}/items', [ListItemController::class, 'store']);
    Route::delete('/lists/{list}/items/{item}', [ListItemController::class, 'destroy']);

    // Fetch all featured lists (user ke interests) OR filter by specific interest
    Route::get('/featured-lists', [FeatureListController::class, 'index']);
    Route::get('/featured-lists/{id}', [FeatureListController::class, 'show']);

    // Fetch items for a specific featured list
    Route::get('/featured-lists/{listId}/items', [FeatureListController::class, 'items']);

    // Recommended items
    Route::get('/recommeditems', [RecommenededItemsController::class, 'recommendedList']);


    Route::get(
        '/global-search',
        [SearchController::class, 'globalSearch']
    );

    // Get authenticated user's interests
    Route::get('/user-interests', [InterestController::class, 'getUserInterests']);
});

// User Details
Route::post('/user_profile', [ProfileController::class, 'store']);

// Interests
Route::get('/interest-list', [InterestController::class, 'getAllInterests']);
Route::post('/add-interest', [InterestController::class, 'addUserInterests']);

// Consent APIs
Route::get('/termsAndPrivacy', [UserConsentController::class, 'index']);
Route::get('/termsAndPrivacy/{slug}', [UserConsentController::class, 'show']);
Route::post('/termsAndPrivacy', [UserConsentController::class, 'update']);
// Route::get('/account/export', [UserConsentController::class, 'exportUserData']);

Route::get('/auth/google', [AuthController::class, 'googleRedirect']);
Route::get('/auth/google/callback', [AuthController::class, 'googleCallback']);

Route::get('/auth/apple', [AuthController::class, 'appleRedirect']);
Route::post('/auth/apple/callback', [AuthController::class, 'appleCallback']);