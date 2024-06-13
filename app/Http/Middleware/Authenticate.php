<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // If the request expects a JSON response, return null
        if ($request->expectsJson()) {
            return null;
        }

        // Check if the user is authenticated
        if (Auth::check()) {
            $user = Auth::user();
            // Check the user's role and redirect accordingly
            if ($user->role == 'admin') {
                return route('dashboard'); 
            } elseif ($user->role == 'user') {
                return route('/'); 
            }
        }

        // Default redirection to login route if not authenticated
        return route('login');
    }
}
