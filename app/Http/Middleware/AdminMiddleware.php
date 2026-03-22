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
        if ($this->isAdmin($request->user())) {
            return $next($request);
        }

        if ($this->isCustomer($request->user())) {
            // redirect to customer dashboard
        }

        if ($this->isSuper($request->user())) {
            // redirect to super dashboard
        }

        abort(Response::HTTP_FORBIDDEN);
    }

    private function isAdmin(?Authenticatable $user)
    {
        return $user instanceof Authenticatable && $user->type === UserType::admin;
    }

    private function isCustomer(?Authenticatable $user)
    {
        return $user instanceof Authenticatable && $user->type === UserType::customer;
    }

    private function isSuper(?Authenticatable $user)
    {
        return $user instanceof Authenticatable && $user->type === UserType::super;
    }
}
