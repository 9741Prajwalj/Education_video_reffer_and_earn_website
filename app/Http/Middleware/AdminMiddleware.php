<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Check if the user is authenticated
        if (!Auth::guard('admin')->check()) {
          return redirect()->route('admin.login')->with('error', 'You must be logged in to access this page.');
        }

        // Check if the authenticated user is an admin
        if (!Auth::user()->is_admin) { // Assuming 'is_admin' is a field in your 'users' table
            return redirect('/')->with('error', 'Unauthorized access.');
        }
        return $next($request);
    }
}
