<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class OperatorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::user()->hasRole(UserRole::OPERATOR->value)){
            return $next($request);
        }
        return redirect()->back()->with('status', 'شما اجازه دسترسی به این صفحه را ندارید');
    }
}
