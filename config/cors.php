<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | DORMANT (disengaja): seluruh lalu lintas aplikasi ini via origin yang sama
    | (first-party browser), endpoint publik memakai CSRF token (bukan API token),
    | jadi middleware CORS tidak punya kerja nyata SELAMA API/broadcast internal
    | diekaspor ke origin lain. Jangan longgarkan allowed_origins sebelum konsumen
    | API publik benar-benar dibuka; saat itu pindah juga ke autentikasi token
    | (Sanctum/Passport) + CSRF non-cookie untuk endpoint third-party.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['*'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];
