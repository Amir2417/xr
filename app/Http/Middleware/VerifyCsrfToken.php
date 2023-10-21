<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'user/username/check',
        'user/check/email',
        '/send-remittance/sslcommerz/success',
        '/send-remittance/sslcommerz/cancel',
        '/send-remittance/sslcommerz/fail',
        
        '/api-send-remittance/sslcommerz/success',
        '/api-send-remittance/sslcommerz/cancel',
        '/api-send-remittance/sslcommerz/fail',
        '/api-send-remittance/sslcommerz/ipn'
    ];
}
