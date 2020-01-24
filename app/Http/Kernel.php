<?php

namespace zenlix\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \zenlix\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \zenlix\Http\Middleware\VerifyCsrfToken::class,
        \zenlix\Http\Middleware\LocaleMiddleware::class,
        //\zenlix\Http\Middleware\CoreCheck::class,
        \zenlix\Http\Middleware\HttpsProtocol::class,
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \zenlix\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'guest' => \zenlix\Http\Middleware\RedirectIfAuthenticated::class,
        'TicketView' => \zenlix\Http\Middleware\TicketView::class,
        'TicketAction' => \zenlix\Http\Middleware\TicketAction::class,
        'TicketModify' => \zenlix\Http\Middleware\TicketModify::class,
        'RoleAdmin' => \zenlix\Http\Middleware\RoleAdmin::class,
        'RoleUser' => \zenlix\Http\Middleware\RoleUser::class,
        'RoleClient' => \zenlix\Http\Middleware\RoleClient::class,
        'RoleAdminOrUser' => \zenlix\Http\Middleware\RoleAdminOrUser::class,
        'CanInstall' => \zenlix\Http\Middleware\CanInstall::class,
        'ActiveAPI' => \zenlix\Http\Middleware\ActiveAPI::class,
        'IsInstall' => \zenlix\Http\Middleware\IsInstall::class,
        'jwt.auth' => \Tymon\JWTAuth\Middleware\GetUserFromToken::class,
        'jwt.refresh' => \Tymon\JWTAuth\Middleware\RefreshToken::class,
    ];

//$this->check();

}
