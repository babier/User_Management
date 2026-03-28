<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
        //
        Gate::define('admin-only', function (User $user) {
            // Gunakan strtolower untuk cari aman jika input database tidak konsisten
            return strtolower($user->role) === 'admin';
        });
    }
}
