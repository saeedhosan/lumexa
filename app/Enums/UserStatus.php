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

    public static function fallback(): self
    {
        return self::active;
    }

    // Optional helper: values only
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function color(): string
    {
        return match ($this) {
            self::invited  => 'yellow',
            self::created  => 'blue',
            self::pending  => 'yellow',
            self::reviews  => 'blue',
            self::approved => 'green',
            self::rejected => 'red',
            self::active   => 'green',
            self::inactive => 'zinc',
            self::blocked  => 'red',
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::invited  => 'Invited',
            self::created  => 'Created',
            self::pending  => 'Pending',
            self::reviews  => 'Reviews',
            self::approved => 'Approved',
            self::rejected => 'Rejected',
            self::active   => 'Active',
            self::inactive => 'Inactive',
            self::blocked  => 'Blocked',
        };
    }
}
