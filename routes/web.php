<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CampaignController;
use App\Http\Controllers\Admin\InterestController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CatalogCategoryController;
use App\Http\Controllers\Admin\CatalogItemController;
use App\Http\Controllers\Admin\FeaturedListController;
use App\Http\Controllers\Admin\FeaturedListItemController;
use App\Http\Controllers\Admin\PolicyController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admin.login');
});

Route::get('/admin/login', [AdminController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.submit');


Route::prefix('admin')->name('admin.')->group(function () {

    // Login
    Route::get('/login', [AdminController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminController::class, 'login']);



    // Forgot Password
    Route::get('/forgot-password', [AdminController::class, 'forgotPasswordForm'])
        ->name('forgot-password.form');

    Route::post('/forgot-password', [AdminController::class, 'sendResetOtp'])
        ->name('forgot-password.send');

    // OTP Verify
    Route::get('/verify-otp', [AdminController::class, 'otpForm'])
        ->name('otp.form');

    Route::post('/verify-otp', [AdminController::class, 'verifyOtp'])
        ->name('otp.verify');

    // Reset Password
    Route::get('/reset-password', [AdminController::class, 'resetPasswordForm'])
        ->name('reset.form');

    Route::post('/reset-password', [AdminController::class, 'resetPassword'])
        ->name('reset.password');
});



Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard'])
        ->name('dashboard');

    // Route::get('/dashboard', function () {
    //     return view('admin.dashboard');
    // })->name('dashboard');

    // User
    Route::get('/users', [UserController::class, 'users'])->name('user.index');
    Route::post(
        'user/{id}/toggle-status',
        [UserController::class, 'toggleStatus']
    )->name('user.toggle-status');
    Route::get('/users/{id}/view', [UserController::class, 'viewUser'])
        ->name('users.view');

    // Interest
    Route::resource('interest', InterestController::class);
    Route::post(
        'interest/{interest}/toggle-status',
        [InterestController::class, 'toggleStatus']
    )->name('interest.toggle-status');

    //Catalog
    Route::resource('catalog-items', CatalogItemController::class);
    Route::patch(
        '/items/{id}/toggle-status',
        [CatalogItemController::class, 'toggleStatus']
    )->name('items.toggle-status');
    Route::resource('catalog-categories', CatalogCategoryController::class);
    Route::patch(
        '/catalog-categories/{id}/toggle-status',
        [CatalogCategoryController::class, 'toggleStatus']
    )->name('catalog-categories.toggle-status');

    // Policy
    Route::resource('policies', PolicyController::class);


    // Featured Lists
    Route::resource('featured-lists', FeaturedListController::class);

    Route::resource(
        'featured-list-items',
        FeaturedListItemController::class
    );

    Route::resource('campaigns', CampaignController::class);
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
});
