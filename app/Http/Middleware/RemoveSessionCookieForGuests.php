<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;

class RemoveSessionCookieForGuests
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->isMethod('GET') && ! auth()->check()) {
            $response->headers->remove('Set-Cookie');
        }

        return $response;
    }
}