<?php

namespace App\Http\Middleware; // CORRECTED NAMESPACE

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; // It's good practice to import Auth facade

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Check if the user is authenticated and is an admin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Use the imported Auth facade for better readability
        // Check first if user is authenticated, then if they are admin
        if (!Auth::check() || !Auth::user()->is_admin) {
            // Deny access with a Forbidden (403) status code
            abort(403, 'Unauthorized Access.'); // English error message is standard
        }

        // User is authenticated and is an admin, proceed with the request
        return $next($request);
    }
}