<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except =
        [
            '/api/odeme-al',
            '/papara-notify',
            '/gpay-notify',
            '/ozan/return',
            'https://epntest.epin.com.tr/*',
            '138.68.95.65',
            '138.68.107.148',
            '/api/epinNotify',
            '/siparis/basarili',
            '/siparis/basarisiz',
            '/twitch-streamlabs-notify',
            '/panel/resimYukle',
            '/panel/resimYukle2',
            '/guard',
            '/priceguard',
            '/carixxx/*',
            '/bulut/*',
        ];
}
