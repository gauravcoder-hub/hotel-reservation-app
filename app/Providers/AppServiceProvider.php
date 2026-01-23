<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Artisan;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

public function boot(): void
{
    if (app()->environment('production')) {
        try {
            Artisan::call('migrate', ['--force' => true]);
        } catch (\Throwable $e) {
            // Log the error or handle it as needed
        }
    }
}
}
