<?php

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
});


// Show login page
Route::get('/admin/login', [AdminController::class, 'showLogin'])->name('admin.login');

// Handle login form
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.submit');