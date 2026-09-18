<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;

class RemoveSessionCookieForGuests
{
    private const SESSION_COOKIE = 'laravel_session';

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->isMethod('GET') && ! auth()->check()) {
            $response->headers->remove($this->sessionCookieName());
        }

        return $response;
    }

    private function sessionCookieName(): string
    {
        return config('session.cookie', self::SESSION_COOKIE);
    }
}