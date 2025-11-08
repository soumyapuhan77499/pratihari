<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class ConsoleRequestFallbackServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if ($this->app->runningInConsole()) {
            $this->app->singleton('request', function ($app) {
                $root = config('app.url', 'http://localhost');
                // Create a minimal Request so UrlGenerator has something to attach to.
                $req = Request::create($root, 'GET');
                // Make sure URL generator knows the app root (also helps assets/route() in CLI)
                URL::forceRootUrl($root);

                // If you always use https in prod, you can also force the scheme:
                if (str_starts_with($root, 'https://')) {
                    URL::forceScheme('https');
                }

                return $req;
            });
        }
    }

    public function boot(): void
    {
        // nothing to do
    }
}
