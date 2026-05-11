<?php

declare(strict_types=1);

namespace App\Policies\Concerns;

use App\Models\User;

trait SuperPolicyBefore
{
    public function before(User $user, string $ability): ?bool
    {
        return $user->isSuper() ? true : null;
    }
}
