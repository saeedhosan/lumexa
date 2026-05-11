<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Company;
use App\Models\User;
use App\Policies\Concerns\SuperPolicyBefore;

class CompanyPolicy
{
    use SuperPolicyBefore;

    public function viewAny(User $user): bool
    {
        return $user->companies()->exists();
    }

    public function view(User $user, Company $company): bool
    {
        return $user->belongsToCompany($company);
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() && $user->belongsToCompany($user->currentCompany);
    }

    public function update(User $user, Company $company): bool
    {
        return $user->isAdmin() && $user->belongsToCompany($company);
    }

    public function delete(User $user, Company $company): bool
    {
        return false;
    }
}
