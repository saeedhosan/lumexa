<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isSuper();
    }

    public function view(User $user, User $model): bool
    {
        if ($user->isSuper()) {
            return true;
        }

        $tenantKey = currentTenant()->tenantKey();

        return $tenantKey && $model->companies()->where('id', $tenantKey)->exists();
    }

    public function create(User $user): bool
    {
        if ($user->isSuper()) {
            return true;
        }

        return $user->isAdmin();
    }

    public function update(User $user, User $model): bool
    {
        if ($user->isSuper()) {
            return true;
        }

        $tenantKey = currentTenant()->tenantKey();

        return $tenantKey && $model->companies()->where('id', $tenantKey)->exists();
    }

    public function delete(User $user, User $model): bool
    {
        return $user->isSuper();
    }

    public function restore(User $user, User $model): bool
    {
        return $user->isSuper();
    }

    public function forceDelete(User $user, User $model): bool
    {
        return $user->isSuper();
    }
}
