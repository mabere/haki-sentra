<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIncentiveFeatureEnabled
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!incentive_enabled()) {
            abort(404, 'Fitur pengajuan dan pencairan insentif sedang dinonaktifkan.');
        }

        return $next($request);
    }
}
