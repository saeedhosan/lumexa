<?php

declare(strict_types=1);

namespace App\Enums;

enum LeadLestStatus: string
{
    case pending   = 'pending';
    case cleaned   = 'cleaned';
    case blocked   = 'blocked';
    case invalid   = 'invalid';
    case duplicate = 'duplicate';
    case spam      = 'spam';
    case dnc       = 'dnc'; // Do Not Contact
    case archived  = 'archived';

    /** @return array<string|int, string> */
    public static function values(): array
    {
        return array_map(fn (LeadLestStatus $case) => $case->value, self::cases());
    }

    public function label(): string
    {
        return match ($this) {
            self::pending   => 'Pending',
            self::cleaned   => 'Cleaned',
            self::blocked   => 'Blocked',
            self::invalid   => 'Invalid',
            self::duplicate => 'Duplicate',
            self::spam      => 'Spam',
            self::dnc       => 'Do Not Contact',
            self::archived  => 'Archived',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::pending   => 'gray',
            self::cleaned   => 'blue',
            self::blocked   => 'red',
            self::invalid   => 'orange',
            self::duplicate => 'purple',
            self::spam      => 'red',
            self::dnc       => 'black',
            self::archived  => 'slate',
        };
    }

    /**
     * Optional: terminal states (no further processing)
     */
    public function isFinal(): bool
    {
        return in_array($this, [
            self::blocked,
            self::invalid,
            self::duplicate,
            self::spam,
            self::dnc,
            self::archived,
        ], true);
    }
}
