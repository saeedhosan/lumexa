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
            UserType::super => 'super.home',
            UserType::admin => 'admin.home',
            default         => 'dashboard',
        });
    }
}
