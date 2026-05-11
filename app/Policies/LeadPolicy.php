<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Lead;
use App\Models\User;
use App\Policies\Concerns\SuperPolicyBefore;

class LeadPolicy
{
    use SuperPolicyBefore;

    public function viewAny(User $user): bool
    {
        return $user->companies()->exists();
    }

    public function view(User $user, Lead $lead): bool
    {
        return $user->belongsToCompany($lead->company);
    }

    public function create(User $user): bool
    {
        return $user->companies()->exists();
    }

    public function update(User $user, Lead $lead): bool
    {
        if ($user->isAdminOf($lead->company)) {
            return true;
        }

        return $lead->user_id === $user->id;
    }

    public function delete(User $user, Lead $lead): bool
    {
        if ($user->isAdminOf($lead->company)) {
            return true;
        }

        return $lead->user_id === $user->id;
    }
}
