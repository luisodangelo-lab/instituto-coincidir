<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();
        if (!$user) abort(401);

        $role = $user->role ?? 'alumno';
        if (!in_array($role, $roles, true)) abort(403);

        return $next($request);
    }
}
