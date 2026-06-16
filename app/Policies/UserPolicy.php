<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Policies\Concerns\SuperPolicyBefore;

class UserPolicy
{
    use SuperPolicyBefore;

    public function viewAny(User $user): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->companies()->exists();
    }

    public function view(User $user, User $model): bool
    {
        return $user->belongsToCompany($model->currentCompany);
    }

    public function create(User $user): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->currentCompany && $user->isAdminOf($user->currentCompany);
    }

    public function update(User $user, User $model): bool
    {
        if ($user->isAdminOf($model->currentCompany)) {
            return true;
        }

        return $user->id === $model->id;
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
