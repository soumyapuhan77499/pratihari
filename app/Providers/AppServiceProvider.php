<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Do NOT bind UrlGenerator here. Let Laravel handle it.
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Only force URLs when handling HTTP, not in console (Artisan).
        if ($this->app->runningInConsole()) {
            return;
        }

        // If you want to force the app URL/scheme, read it from APP_URL.
        if ($appUrl = config('app.url')) {
            URL::forceRootUrl($appUrl);
            $scheme = parse_url($appUrl, PHP_URL_SCHEME) ?: 'http';
            URL::forceScheme($scheme);
        }
    }
}
