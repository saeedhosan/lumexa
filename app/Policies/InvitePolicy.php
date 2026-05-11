<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Invite;
use App\Models\User;
use App\Policies\Concerns\SuperPolicyBefore;

class InvitePolicy
{
    use SuperPolicyBefore;

    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, Invite $invite): bool
    {
        return $user->belongsToCompany($invite->company_id);
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Invite $invite): bool
    {
        return $user->isAdminOf($invite->company_id);
    }

    public function delete(User $user, Invite $invite): bool
    {
        return $user->isAdminOf($invite->company_id);
    }
}
