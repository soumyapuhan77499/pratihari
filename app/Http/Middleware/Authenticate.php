<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     * For API (Sanctum) we do NOT redirect to any "login" route.
     */
    protected function redirectTo($request): ?string
    {
        // Returning null tells Laravel not to try to redirect to route('login')
        return null;
    }
}
