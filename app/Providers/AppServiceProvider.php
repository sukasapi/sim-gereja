<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// use Filament\Http\Responses\Auth\Contracts\LoginResponse;
// use App\Http\Responses\FilamentLoginResponse;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Menggunakan default Filament login response
        // $this->app->singleton(LoginResponse::class, FilamentLoginResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register helper functions
        if (!function_exists('current_church')) {
            function current_church() {
                return \App\Helpers\ChurchHelper::getCurrentChurch();
            }
        }
        
        if (!function_exists('current_church_id')) {
            function current_church_id() {
                return \App\Helpers\ChurchHelper::getCurrentChurchId();
            }
        }
        
        if (!function_exists('can_access_church')) {
            function can_access_church(?int $churchId) {
                return \App\Helpers\ChurchHelper::canAccessChurch($churchId);
            }
        }
    }
}
