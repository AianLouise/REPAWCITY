<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    /**
     * Ensure the authenticated user has the given role (replaces the legacy
     * admin_guard.php redirect with a hard server-side 403).
     *
     * Usage: ->middleware('role:admin')
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if ($role === 'admin' && ! $request->user()?->isAdmin()) {
            abort(403, 'Forbidden.');
        }

        return $next($request);
    }
}
