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
        array_walk_recursive($input, function (&$item) {
            // Strip HTML tags
            $item = strip_tags($item);
            // Replace multiple whitespace with single space and trim
            $item = preg_replace('/\s+/', ' ', trim($item));
        });
        $request->merge($input);
        return $next($request);
    }
}
