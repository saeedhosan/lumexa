<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Lead;
use App\Models\User;

class LeadPolicy
{
    public function viewAny(User $user): bool
    {
        if ($user->isSuper()) {
            return true;
        }

        return $user->isAdmin();
    }

    public function view(User $user, Lead $lead): bool
    {
        if ($user->isSuper()) {
            return true;
        }

        $tenantKey = currentTenant()->tenantKey();

        return $tenantKey && $lead->company_id === $tenantKey;
    }

    public function create(User $user): bool
    {
        if ($user->isSuper()) {
            return true;
        }

        return $user->isAdmin();
    }

    public function update(User $user, Lead $lead): bool
    {
        if ($user->isSuper()) {
            return true;
        }

        $tenantKey = currentTenant()->tenantKey();

        if (! $tenantKey || $lead->company_id !== $tenantKey) {
            return false;
        }

        return $user->isAdminOf($lead->company);
    }

    public function delete(User $user, Lead $lead): bool
    {
        if ($user->isSuper()) {
            return true;
        }

        $tenantKey = currentTenant()->tenantKey();

        if (! $tenantKey || $lead->company_id !== $tenantKey) {
            return false;
        }

        return $user->isAdminOf($lead->company);
    }
}
