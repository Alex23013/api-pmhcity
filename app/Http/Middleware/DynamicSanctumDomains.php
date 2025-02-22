<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class DynamicSanctumDomains
{
    public function handle(Request $request, Closure $next)
    {
        $origin = $request->header('Origin');
        $allowedDomains = Config::get('sanctum.stateful', []);

        // If the origin is valid and not already in the list, add it
        if ($origin && !in_array($origin, $allowedDomains, true)) {
            $allowedDomains[] = $origin;
            Config::set('sanctum.stateful', $allowedDomains);
        }

        return $next($request);
    }
}