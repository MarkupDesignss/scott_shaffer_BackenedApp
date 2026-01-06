<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Jenssegers\Agent\Agent;

class MobileOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $agent = new Agent();

        if (!$agent->isMobile()) {
            return response()->json([
                'success' => false,
                'message' => 'This link is accessible only on mobile devices',
                'error'   => 'Desktop/Laptop access is not allowed',
            ], 403);
        }

        return $next($request);
    }
}
