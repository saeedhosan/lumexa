<?php

declare(strict_types=1);

use App\Enums\UserType;
use Illuminate\Support\Facades\Auth;

if (! function_exists('home_route')) {
    /**
     * Get the home route based on user role.
     */
    function home_route(): string
    {
        return once(fn (): string => match (Auth::user()->type) {
            UserType::super => 'admin.home',
            UserType::admin => 'admin.home',
            default         => 'dashboard',
        });
    }
}

if (! function_exists('escape_like')) {
    /**
     * Escape LIKE wildcard characters for safe SQL LIKE queries.
     */
    function escape_like(string $value): string
    {
        return str_replace(['%', '_'], ['\\%', '\\_'], $value);
    }
}
