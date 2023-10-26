<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RegularUserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role_id === 2) {
            return $next($request);
        }

        // If the user is not a regular user (role ID is not 2), return an error response or perform a redirect.
        // For example, you can return a 403 Forbidden response:
        return response('Unauthorized', 403);
    
    }
}
