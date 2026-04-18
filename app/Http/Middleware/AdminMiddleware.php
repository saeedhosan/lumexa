<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\UserType;
use Closure;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($this->isAdmin($request->user()) || $this->isSuper($request->user())) {
            return $next($request);
        }

        abort(Response::HTTP_FORBIDDEN);
    }

    private function isAdmin(?Authenticatable $user): bool
    {
        return $user instanceof Authenticatable && $user->type === UserType::admin;
    }

    private function isSuper(?Authenticatable $user): bool
    {
        return $user instanceof Authenticatable && $user->type === UserType::super;
    }
}
