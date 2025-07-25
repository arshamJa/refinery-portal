<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermissionAndRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $permission, $role)
    {
        $user = auth()->user();

        if (!$user) {
            abort(403, 'Unauthorized');
        }

        if ($user->hasRole(UserRole::SUPER_ADMIN->value)) {
            return $next($request);
        }

        if (!$user->hasPermissionTo($permission) || !$user->hasRole($role)) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
