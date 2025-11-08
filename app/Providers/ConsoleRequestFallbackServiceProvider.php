<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;

class ConsoleRequestFallbackServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Only for CLI context
        if (! $this->app->runningInConsole()) {
            return;
        }

        // If "request" isn't a valid Illuminate\Http\Request, provide a minimal one
        $needsFallback = false;

        if (! $this->app->bound('request')) {
            $needsFallback = true;
        } else {
            $bound = $this->app->make('request');
            if (! $bound instanceof Request) {
                $needsFallback = true;
            }
        }

        if ($needsFallback) {
            // Use APP_URL (must have a scheme)
            $root = (string) config('app.url', 'http://localhost');
            if (! preg_match('~^https?://~i', $root)) {
                $root = 'http://localhost';
            }

            // Minimal GET request bound to container
            $this->app->instance('request', Request::create(rtrim($root, '/') ?: 'http://localhost', 'GET'));
        }
    }

    public function boot(): void
    {
        //
    }
}
