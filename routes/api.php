<?php

use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\FeatureListController;
use App\Http\Controllers\API\UserConsentController;
use App\Http\Controllers\API\InterestController;
use App\Http\Controllers\API\ListController;
use App\Http\Controllers\Admin\PolicyController;
use App\Http\Controllers\API\ListItemController;
use App\Http\Controllers\API\ActionController;
use App\Http\Controllers\API\PasswordController;
use App\Http\Controllers\API\RecommenededItemsController;
use App\Http\Controllers\API\SearchController;
use App\Http\Controllers\API\SubcateegoryController;
use App\Http\Controllers\API\CampaignController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\UserDataExportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public auth routes
Route::post('/register', [AuthController::class, 'signup']);
Route::post('/check-user-status', [AuthController::class, 'checkUserStatus']);
Route::post('/request-otp', [AuthController::class, 'requestOtp']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);

Route::get('/login', function () {
    return response()->json(['success' => false, 'message' => 'Authentication token is require to access this api.'], 401);
})->name('login');

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
    Route::post('/remove_profile', [ProfileController::class, 'removeProfileImage']);

    /* =========================
       Lists (CRUD)
    ========================== */
    Route::get('/lists', [ListController::class, 'index']);          // My lists (owner + group)
    Route::post('/lists', [ListController::class, 'store']);         // Create list
    Route::get('/lists/{id}', [ListController::class, 'show']);      // Show list
    Route::put('/lists/{id}', [ListController::class, 'update']);    // Update list
    Route::delete('/delete/list/{id}', [ListController::class, 'destroy']); // Delete list
    Route::get('/catalog/categories', [CategoryController::class, 'categories']); // Categories list
    Route::get('/catalog/categories/{id}', [CategoryController::class, 'categoriesByInterest']); // Categories list
    Route::get('/search/list', [ListController::class, 'search']);
    Route::post('/lists/reorder', [ListController::class, 'reorderLists']);

    Route::get('/catalog/items/{id}', [CategoryController::class, 'items']); // Items list
    Route::post('lists/publish', [ListController::class, 'publishLists']);
    Route::get('all-published-list', [ListController::class, 'allPublishedList']);
    Route::post('current-published-list', [ListController::class, 'singlePublishedList']);

    /* =========================
       Group List – Members
    ========================== */
    Route::get('/users/invite-list', [ListController::class, 'inviteUserList']);    // User list for inviting members
    Route::post('/lists/{listId}/clone', [ListController::class, 'cloneList']);
    Route::post('/lists/{id}/invite', [ListController::class, 'inviteMembers']);   // Invite members
    Route::get('/lists/invitations', [ListController::class, 'myInvitations']);    // My invites
    Route::post('/lists/{id}/accept', [ListController::class, 'acceptInvite']);    // Accept invite
    Route::post('/lists/{id}/reject', [ListController::class, 'rejectInvite']);    // Reject invite
    Route::delete('/lists/{id}/members/{userId}', [ListController::class, 'removeMember']); // Remove member
    Route::post('/lists/{id}/leave', [ListController::class, 'leaveGroup']);       // Leave group
    Route::post('/list-invites/accept',  [ListController::class, 'accept']);
    Route::post('/list-invites/reject',  [ListController::class, 'reject']);
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/my/notifications', [NotificationController::class, 'myNotifications']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
    Route::delete('/notifications/delete/{id}', [NotificationController::class, 'deleteNotification']);
    Route::delete('/notifications/delete-all', [NotificationController::class, 'deleteAllNotifications']);


    /* =========================
       List Items
    ========================== */
    Route::get('lists/{list}/items', [ListItemController::class, 'index']);
    Route::put('/list-items/{itemId}/status', [ListItemController::class, 'updateStatus']);
    Route::post('lists/items', [ListItemController::class, 'store']);
    Route::put('lists/{list}/items/reorder', [ListItemController::class, 'reorder']);
    Route::put('lists/{list}/items/{item}', [ListItemController::class, 'update']);
    Route::delete('lists/{list}/items/{item}', [ListItemController::class, 'destroy']);

    // Fetch all featured lists (user ke interests) OR filter by specific interest
    Route::get('/featured-lists', [FeatureListController::class, 'index']);
    Route::get('/featured-lists/search', [FeatureListController::class, 'searchFeatureList']);
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

    Route::get(
        '/campaigns',
        [CampaignController::class, 'index']
    );
    
    
    // Like / Unlike Featured List Item
    Route::post(
        '/featured-items/{featuredListItem}/like',
        [ActionController::class, 'toggleLike']
    );

    // Save / Unsave (Bookmark) Featured List Item
    Route::post(
        '/featured-items/{featuredListItem}/bookmark',
        [ActionController::class, 'toggleBookmark']
    );

    Route::get('/featured-items/{id}/share-link', [
        ActionController::class,
        'generateShareLink'
    ]);

    // Share Featured List Item (log only)
    Route::post(
        '/featured-items/{featuredListItem}/share',
        [ActionController::class, 'share']
    );
    
    Route::delete('/featured-list-delete/{id}', [ActionController::class, 'removeBookmark']);
    Route::delete('/featured-list-like-delete/{id}', [ActionController::class, 'removeLike']);
    Route::delete('/user-list-like-delete/{id}', [ActionController::class, 'removeListLike']);

    
        // Toggle Like
    Route::post('/lists/{listId}/like', [ActionController::class, 'toggleListLike']);

    // Generate Share Link
    Route::get('/lists/{listId}/share-link', [ActionController::class, 'generateListShareLink']);
    
    Route::delete('/remove-interest', [ProfileController::class, 'removeInterest']);

    // Store Share Event
    Route::post('/lists/{listId}/share', [ActionController::class, 'shareList']);

    Route::get('/me/featured-lists/likes', [ActionController::class, 'myLikedFeaturedLists']);
    Route::get('/me/featured-lists/bookmarks', [ActionController::class, 'myBookmarkedFeaturedLists']);
    
        // Request data exports
    Route::post('/user/request-data-export', [UserDataExportController::class, 'request']);
    Route::get('/user/request-data-export', [UserDataExportController::class, 'getExports']);

    // NORMAL LISTS
    Route::get('/me/lists/likes', [ActionController::class, 'myLikedLists']);
    
    // Subcategory an items
    Route::get(
        'categories/{category}/sub-categories',
        [SubcateegoryController::class, 'subCategories']
    );
    
   Route::post('/account/delete', [ProfileController::class, 'deleteAccount']);

    Route::get(
        'sub-categories/{subCategory}/items',
        [SubcateegoryController::class, 'items']
    );
});

    // User Details
    Route::post('/user_profile', [ProfileController::class, 'store']);
    
    // Interests
    Route::get('/interest-list', [InterestController::class, 'getAllInterests']);
    Route::post('/add-interest', [InterestController::class, 'addUserInterests']);
    
    // Consent APIs
    Route::get('/termsAndPrivacy', [UserConsentController::class, 'index']);
        Route::get('getVersion', [UserConsentController::class, 'getVersion']);
    Route::get('/termsAndPrivacy/{slug}', [UserConsentController::class, 'show']);
    Route::post('/termsAndPrivacy', [UserConsentController::class, 'update']);
    Route::get('/termsandpolicy', [PolicyController::class, 'termsAndPolicy']);
    
    
    // Route::get('/account/export', [UserConsentController::class, 'exportUserData']);
    
    Route::get('/auth/google', [AuthController::class, 'googleRedirect']);
    Route::get('/auth/google/callback', [AuthController::class, 'googleCallback']);
    
    Route::get('/auth/apple', [AuthController::class, 'appleRedirect']);
    Route::post('/auth/apple/callback', [AuthController::class, 'appleCallback']);