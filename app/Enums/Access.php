<?php

declare(strict_types=1);

namespace App\Enums;

enum Access: string
{
    case customer = 'access:customer';
    case system   = 'access:system';
    case client   = 'access:client';
    case manager  = 'access.manager';
    case admin    = 'access:admin';
    case msp      = 'access:msp';
    case ghl      = 'access:ghl';

    /**
     * Get all access values.
     *
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get all access values
     *
     * @return array<int, string>
     */
    public static function all(): array
    {
        return self::values();
    }

    /**
     * Admin / back-office access.
     *
     * @return array<int, string>
     */
    public static function admins(): array
    {
        return [
            self::msp->value,
            self::admin->value,
            self::client->value,
            self::manager->value,
        ];
    }
}
