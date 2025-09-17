<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class NormalizeUrl
{
    public function handle(Request $request, Closure $next)
    {
        // 1) Strip trailing slash with 308 (preserves POST)
        $path = $request->getPathInfo();
        if ($path !== '/' && str_ends_with($path, '/')) {
            $new = rtrim($path, '/');
            $query = $request->getQueryString();
            $url = $new . ($query ? '?' . $query : '');
            return redirect($url, 308); // 308 keeps method & body
        }

        // 2) If you want to enforce HTTPS at app-level, do it with 308
        //    Only when you're truly behind HTTPS in production.
        if (app()->environment('production') && !$request->isSecure()) {
            $uri = 'https://' . $request->getHttpHost() . $request->getRequestUri();
            return redirect($uri, 308); // preserve POST on redirect
        }

        return $next($request);
    }
}
