<?php

namespace App\Providers;

// use App\Http\Responses\FilamentLoginResponse;
// use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Illuminate\Support\ServiceProvider;

class FilamentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Menggunakan default Filament login response
        // $this->app->bind(LoginResponse::class, FilamentLoginResponse::class);
    }

    public function boot(): void
    {
        //
    }
} 