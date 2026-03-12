<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\Access;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class AdministratorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        return Gate::allows(Access::administrator) ? $next($request) : abort(Response::HTTP_FORBIDDEN);
    }
}
