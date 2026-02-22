<?php

declare(strict_types=1);

namespace App\Enums;

enum UserStatus: string
{
    // ─────────────────────────────────────────────
    // Core workflow statuses
    // ─────────────────────────────────────────────
    case invited  = 'invited';
    case created  = 'created';
    case pending  = 'pending';
    case reviews  = 'reviews';
    case approved = 'approved';
    case rejected = 'rejected';
    case active   = 'active';
    case inactive = 'inactive';
    case blocked  = 'blocked';

    // Default status
    public static function default(): self
    {
        return self::active;
    }

    // Optional helper: values only
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
