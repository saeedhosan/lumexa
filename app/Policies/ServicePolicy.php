<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Service;
use App\Models\User;

class ServicePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Service $service): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isSuper();
    }

    public function update(User $user, Service $service): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isSuper();
    }

    public function delete(User $user, Service $service): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isSuper();
    }
}
