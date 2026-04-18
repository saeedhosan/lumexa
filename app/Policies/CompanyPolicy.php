<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\UserType;
use App\Models\Company;
use App\Models\User;

class CompanyPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Company $company): bool
    {
        if ($user->type === UserType::super) {
            return true;
        }

        return $user->companies()->where('companies.id', $company->id)->exists();
    }

    public function create(User $user): bool
    {
        return $user->type === UserType::super;
    }

    public function update(User $user, Company $company): bool
    {
        if ($user->type === UserType::super) {
            return true;
        }

        return $user->isAdminOf($company);
    }

    public function delete(User $user, Company $company): bool
    {
        return $user->type === UserType::super;
    }
}
