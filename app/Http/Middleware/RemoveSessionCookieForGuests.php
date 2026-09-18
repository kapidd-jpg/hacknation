<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;

class RemoveSessionCookieForGuests
{
    /**
     * Halaman publik tanpa form/session — satu-satunya yang aman dibuang cookie-nya
     * supaya bisa di-edge-cache di CDN. Halaman ber-form (login/daftar/kontak) WAJIB
     * tetap mempertahankan session cookie karena CSRF token terikat pada session.
     */
    protected array $noSessionRoutes = [
        'home',
        'about',
        'classes',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->isMethod('GET') && ! auth()->check()) {
            $route = $request->route()?->getName();
            if ($route && in_array($route, $this->noSessionRoutes, true)) {
                $response->headers->remove('Set-Cookie');
            }
        }

        return $response;
    }
}