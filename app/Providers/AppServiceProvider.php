<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Intentionally empty. Let the framework bind UrlGenerator & Request.
    }

    public function boot(): void
    {
        // Only force URLs in HTTP context (not during Artisan)
        if ($this->app->runningInConsole()) {
            return;
        }

        if ($appUrl = config('app.url')) {
            URL::forceRootUrl($appUrl);
            $scheme = parse_url($appUrl, PHP_URL_SCHEME) ?: 'http';
            URL::forceScheme($scheme);
        }
    }
}
