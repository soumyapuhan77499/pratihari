<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        // For API routes, don't redirect to "login" at all,
        // just return null so Laravel responds with 401 JSON.
        if ($request->is('api/*') || $request->expectsJson()) {
            return null;
        }

        // If you have a web login page in the future, you can keep this.
        // Right now, if there is no route('login'), you can remove this line
        // or add a dummy login route.
        return route('login');
    }
}
