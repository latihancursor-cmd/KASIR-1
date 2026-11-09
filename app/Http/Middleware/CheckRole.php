<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $allowedRoles = collect($roles)
            ->flatMap(fn ($role) => explode(',', $role))
            ->map(fn ($role) => trim($role));

        $hasAllowedRole = $request->user() && $allowedRoles->contains(
            fn ($allowedRole) => $request->user()->hasRole($allowedRole)
        );

        if (! $hasAllowedRole) {
            abort(403, 'Forbidden');
        }
        return $next($request);
    }
}
