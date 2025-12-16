<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\InterestController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/login', [AdminController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.submit');


Route::prefix('admin')->name('admin.')->group(function () {

    // Login
    Route::get('/login', [AdminController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminController::class, 'login']);

    // Logout


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

    // Interest
    Route::resource('interest', InterestController::class);
    Route::post(
        'interest/{interest}/toggle-status',
        [InterestController::class, 'toggleStatus']
    )->name('interest.toggle-status');
});



Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('/users', [UserController::class, 'users'])->name('user.index');
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
});