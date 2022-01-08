<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        
        'is-super-admin' => \App\Http\Middleware\IsSuperAdmin::class,
        'is-re-login' => \App\Http\Middleware\IsReLogin::class,
        'is-not-super-admin' => \App\Http\Middleware\IsNotSuperAdmin::class,
        'is-check-role' => \App\Http\Middleware\IsCheckRole::class,

        // "menu-setting-announcement" => \App\Http\Middleware\Menu\Setting\Announcement::class,

        // "menu-master-data-account" =>  \App\Http\Middleware\Menu\MasterData\Account::class,
        // "menu-master-data-province" =>  \App\Http\Middleware\Menu\MasterData\Province::class,
        // "menu-master-data-city" =>  \App\Http\Middleware\Menu\MasterData\City::class,
        // "menu-master-data-district" =>  \App\Http\Middleware\Menu\MasterData\District::class,

        // "menu-activity-set-area-sales" =>  \App\Http\Middleware\Menu\Activity\SetAreaSales::class,
        // "menu-activity-set-target-customer" =>  \App\Http\Middleware\Menu\Activity\SetTargetCustomer::class,
        // "menu-activity-set-target-eksemplar" =>  \App\Http\Middleware\Menu\Activity\SetTargetEksemplar::class,
        // "menu-activity-set-target-telemarketing" =>  \App\Http\Middleware\Menu\Activity\SetTargetTelemarketing::class,
        // "menu-activity-input-visit" => \App\Http\Middleware\Menu\Activity\InputVisit::class,
        // "menu-activity-input-activity-telemarketing" => \App\Http\Middleware\Menu\Activity\InputActivityTelemarketing::class,
    ];
}
