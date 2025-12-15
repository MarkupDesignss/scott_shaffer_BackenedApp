<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Auth\Middleware\Authenticate as AuthenticateMiddleware;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        // Redirect unauthenticated admin requests to the admin login route
        AuthenticateMiddleware::redirectUsing(function ($request) {
            if ($request->expectsJson()) {
                return null;
            }

            if ($request->is('admin') || $request->is('admin/*')) {
                if (Route::has('admin.login')) {
                    return route('admin.login');
                }

                return url('/admin/login');
            }

            if (Route::has('login')) {
                return route('login');
            }

            return url('/login');
        });
    }
}
