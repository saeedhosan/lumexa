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
        return once(function (): string {
            switch (Auth::user()->type) {
                case UserType::administrator:
                    return 'super.home';
                case UserType::admin:
                    return 'admin.home';
                case UserType::customer:
                    return 'customer.home';
                default:
                    return 'dasbhoard';
            }
        });
    }
}
