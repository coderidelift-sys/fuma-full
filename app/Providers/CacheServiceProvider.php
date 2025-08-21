<?php

namespace App\Providers;

use App\Services\CacheService;
use Illuminate\Support\ServiceProvider;

class CacheServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(CacheService::class, function ($app) {
            return new CacheService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register CacheService as a global helper
        if (!function_exists('cache_service')) {
            function cache_service() {
                return app(CacheService::class);
            }
        }
    }
}
