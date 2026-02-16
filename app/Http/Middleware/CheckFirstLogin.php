<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckFirstLogin
{
    /**
     * Handle an incoming request.
     * Redirect to password change if first login.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->must_change_password) {
            // Allow access to password change and logout routes
            if (!$request->routeIs('password.change', 'password.change.update', 'logout')) {
                return redirect()->route('password.change');
            }
        }

        return $next($request);
    }
}
