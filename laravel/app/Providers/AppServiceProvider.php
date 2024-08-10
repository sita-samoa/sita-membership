<?php

namespace App\Providers;

use App\Services\SitaOnlineService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(SitaOnlineService::class, function ($app) {
            return new SitaOnlineService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Needed for gitpod.
        if (Str::contains(Config::get('app.url'), 'https://')) {
            URL::forceScheme('https');
        }
    }
}
