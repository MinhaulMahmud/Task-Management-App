<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleManager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role  The required role to access the route
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Check if the user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login'); // Redirect to login if not authenticated
        }

        // Get the authenticated user's role
        $userRole = Auth::user()->role;

        // Check if the user's role matches the required role for this route
        if ($userRole !== $role) {
            return abort(403, 'Unauthorized action.'); // Abort if user role doesn't match
        }

        // Allow the request to proceed
        return $next($request);
    }
}
