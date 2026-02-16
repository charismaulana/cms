<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CanEditContract
{
    /**
     * Handle an incoming request.
     * Allow admin and user roles to edit, deny viewer.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->canEditContracts()) {
            abort(403, 'Access denied. You do not have permission to edit contracts.');
        }

        return $next($request);
    }
}
