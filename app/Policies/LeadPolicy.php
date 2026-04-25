<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Lead;
use App\Models\User;

class LeadPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isSuper() || $user->isAdmin();
    }

    public function view(User $user, Lead $lead): bool
    {
        if ($user->isSuper()) {
            return true;
        }

        return $user->companies()->where('companies.id', $lead->company_id)->exists();
    }

    public function create(User $user): bool
    {
        return $user->isSuper() || $user->isAdmin();
    }

    public function update(User $user, Lead $lead): bool
    {
        if ($user->isSuper()) {
            return true;
        }

        if (! $user->companies()->where('companies.id', $lead->company_id)->exists()) {
            return false;
        }

        return $user->isAdminOf($lead->company);
    }

    public function delete(User $user, Lead $lead): bool
    {
        if ($user->isSuper()) {
            return true;
        }

        if (! $user->companies()->where('companies.id', $lead->company_id)->exists()) {
            return false;
        }

        return $user->isAdminOf($lead->company);
    }
}
