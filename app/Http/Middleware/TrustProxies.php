<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * Dipakai sebagai nilai awal; bisa di-override per-deployment lewat
     * config `app.trusted_proxies` (env `TRUSTED_PROXIES`).
     *
     * Default '*' aman di Vercel: semua request lewat edge Vercel yang
     * menimpa header X-Forwarded-For dengan IP nyata, jadi spoof client
     * tidak berdampak. Pada deployment NON-Vercel, set TRUSTED_PROXIES ke
     * CIDR proxy/load-balancer asli (mis. "1.2.3.4/32,5.6.7.0/24") —
     * tanpa itu, rate-limiter berbasis IP bisa di-bypass via header spoof.
     *
     * @var array<int, string>|string|null
     */
    protected $proxies = '*';

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers =
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_AWS_ELB;

    /**
     * Baca override dari config aplikasi (env TRUSTED_PROXIES) supaya
     * deployment non-Vercel bisa mengunci proxy riil.
     */
    public function __construct()
    {
        $trusted = config('app.trusted_proxies');

        if (is_string($trusted) && $trusted !== '') {
            $this->proxies = $trusted;
        }
    }
}
