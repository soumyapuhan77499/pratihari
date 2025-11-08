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

        // If "request" isn't a valid Illuminate\Http\Request, provide a minimal one.
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
            $root = rtrim((string) config('app.url', '/'), '/') ?: '/';
            $this->app->instance('request', Request::create($root, 'GET'));
        }
    }

    public function boot(): void
    {
        //
    }
}
