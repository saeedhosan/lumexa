<?php

declare(strict_types=1);

use App\Enums\UserType;
use App\Http\Middleware\CustomerMiddleware;
use App\Http\Middleware\RedirectIfOnboarded;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use SaeedHosan\Tenancy\Http\Middleware\TenantMiddleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
            'customer'   => CustomerMiddleware::class,
            'onboarding' => RedirectIfOnboarded::class,
            'super'      => function ($request, $next) {
                abort_if($request->user()?->type !== UserType::super, 403);

                return $next($request);
            },
        ]);

        $middleware->group('customer', [
            EnsureEmailIsVerified::class,
            CustomerMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (AuthorizationException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => $e->getMessage()], 403);
            }

            return to_route('home')->with('error', $e->getMessage());
        });

        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Resource not found.'], 404);
            }

            return null;
        });
    })->create();
