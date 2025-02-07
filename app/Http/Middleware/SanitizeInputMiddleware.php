<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SanitizeInputMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $input = $request->all();
        array_walk_recursive($input, function (&$item) { // Changed $input to $item for clarity
            $item = strip_tags($item); // Directly modify the $item (passed by reference)
        });
        $request->merge($input); // Now this will merge the sanitized input
        return $next($request);
    }
}
