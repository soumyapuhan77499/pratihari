<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\UrlGenerator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
 public function register()
{
    $this->app->singleton(UrlGenerator::class, function ($app) {
        return new UrlGenerator(
            $app['router']->getRoutes(),
            Request::create($app['config']['app.url'])
        );
    });
}

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
