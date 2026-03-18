<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // Force a single default language across the app.
        app()->setLocale('en');

        return $next($request);
    }
}