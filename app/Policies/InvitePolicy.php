<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Invite;
use App\Models\User;

class InvitePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isSuper() || $user->isAdmin();
    }

    public function view(User $user, Invite $invite): bool
    {
        if ($user->isSuper()) {
            return true;
        }

        return $user->companies()->where('companies.id', $invite->company_id)->exists();
    }

    public function create(User $user): bool
    {
        return $user->isSuper() || $user->isAdmin();
    }

    public function delete(User $user, Invite $invite): bool
    {
        if ($user->isSuper()) {
            return true;
        }

        return $user->companies()->where('companies.id', $invite->company_id)->exists();
    }
}
