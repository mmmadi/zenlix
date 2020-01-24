<?php

namespace zenlix\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
        'ticket/*',
        'chat/toggle',
        'sidebar/toggle',
        'online',
        'chat/*',
        'api/*',
    ];
}
