<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {

        if (Auth::check()) {
            $user = Auth::user();
            
            // Compare the user's jenisUser_id with the role passed in
            if ($user->jenisUser_id == $role) {
                return $next($request);
            }
        }

        // If the user is not authenticated or doesn't have the required role, deny access
        abort(403, 'Unauthorized action.');

    }
}
