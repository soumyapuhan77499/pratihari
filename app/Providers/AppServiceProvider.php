<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Do not bind UrlGenerator or Request here.
    }

    public function boot(): void
    {
        // Avoid forcing URLs during Artisan (no real request there)
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
