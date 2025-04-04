<?php

namespace App\Http\Middleware;

use App\UserRole;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
       if (Auth::user()->hasRole(UserRole::SUPER_ADMIN->value) || Auth::user()->hasRole(UserRole::ADMIN->value)){
           return $next($request);
       }
       return redirect()->back()->with('status','شما اجازه دسترسی به این صفحه را ندارید');
    }
}
