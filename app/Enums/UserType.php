<?php

declare(strict_types=1);

namespace App\Enums;

enum UserType: string
{
    case customer = 'customer';
    case admin    = 'admin';
    case super    = 'super';

    // Default status
    public static function default(): self
    {
        return self::customer;
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
