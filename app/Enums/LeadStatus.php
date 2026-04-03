<?php

declare(strict_types=1);

namespace App\Enums;

enum LeadStatus: string
{
    case pending  = 'pending';
    case process  = 'process';
    case cleaned  = 'cleaned';
    case blocked  = 'blocked';
    case approved = 'approved';
    case rejected = 'rejected';

    /** @return array<string|int, string> */
    public static function values(): array
    {
        return array_map(fn (LeadStatus $case) => $case->value, self::cases());
    }

    public static function default(): self
    {
        return self::pending;
    }

    public function label(): string
    {
        return match ($this) {
            self::pending  => 'Pending',
            self::process  => 'Process',
            self::cleaned  => 'Cleaned',
            self::blocked  => 'Blocked',
            self::approved => 'Approved',
            self::rejected => 'Rejected',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::pending  => 'gray',
            self::process  => 'yellow',
            self::cleaned  => 'blue',
            self::blocked  => 'red',
            self::approved => 'green',
            self::rejected => 'orange',
        };
    }
}
