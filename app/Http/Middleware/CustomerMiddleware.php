<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\Access;
use App\Enums\UserType;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class CustomerMiddleware
{
    public const ERROR_MESSAGE = 'This account is not eligible to access the area.';

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($this->isCustomer($request->user())) {
            return $next($request);
        }

        if ($this->hasOtherAccess()) {
            // redirect to other dashboard
        }

        throw new AuthorizationException(self::ERROR_MESSAGE);
    }

    private function isCustomer(?Authenticatable $user)
    {
        return $user instanceof Authenticatable && $user->type === UserType::customer;
    }

    private function hasOtherAccess()
    {
        return Gate::any(Access::values());
    }
}
