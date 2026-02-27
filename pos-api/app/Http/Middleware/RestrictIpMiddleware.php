<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictIpMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $allowedIps = config('system.ip_restriction.allowed_ips');

        if (!in_array($request->ip(), $allowedIps)) {
            abort(403, 'Unauthorized Access.'); // Or redirect to an error page
        }

        return $next($request);
    }
}
