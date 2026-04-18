<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\UserType;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
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

        if ($url = $this->getRedirect($request->user())) {
            return redirect()->to($url);
        }

        throw new AuthorizationException(self::ERROR_MESSAGE);
    }

    private function getRedirect(?Authenticatable $user): ?string
    {
        if (is_null($user)) {
            return null;
        }

        if ($user->type === UserType::admin || $user->type === UserType::admin) {
            return route('admin.dashboard', absolute: true);
        }

        return null;
    }

    private function isCustomer(?Authenticatable $user): bool
    {
        return $user instanceof Authenticatable && $user->type === UserType::user;
    }
}
