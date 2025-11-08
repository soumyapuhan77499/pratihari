<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Do NOT bind UrlGenerator or Request here.
    }

    public function boot(): void
{
    if ($this->app->runningInConsole() || $this->app->runningUnitTests()) {
        return;
    }
    if ($appUrl = config('app.url')) {
        \Illuminate\Support\Facades\URL::forceRootUrl($appUrl);
        \Illuminate\Support\Facades\URL::forceScheme(parse_url($appUrl, PHP_URL_SCHEME) ?: 'http');
    }
}

}
