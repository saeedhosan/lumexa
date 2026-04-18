<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Enums\UserType;

trait HasUserType
{
    /**
     * Determine whether the user is a admin.
     */
    public function isAdmin(): bool
    {
        return $this->type === UserType::admin;
    }

    /**
     * Determine whether the user is a super.
     */
    public function isSuper(): bool
    {
        return $this->type === UserType::super;
    }

    /**
     * Determine whether the user is a super.
     */
    public function isUser(): bool
    {
        return $this->type === UserType::user;
    }
}
