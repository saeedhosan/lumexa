<?php

declare(strict_types=1);

use App\Enums\UserType;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\CustomerMiddleware;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use SaeedHosan\Tenancy\Http\Middleware\TenantMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->api(prepend: [
            EnsureFrontendRequestsAreStateful::class,
        ]);

        $middleware->web(append: [
            TenantMiddleware::class,
        ]);

        $middleware->alias([
            'admin'    => AdminMiddleware::class,
            'customer' => CustomerMiddleware::class,
            'super'    => function ($request, $next) {
                abort_if($request->user()?->type !== UserType::super, 403);

                return $next($request);
            },
        ]);

        $middleware->group('admin', [
            AdminMiddleware::class,
        ]);

        $middleware->group('customer', [
            EnsureEmailIsVerified::class,
            CustomerMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
