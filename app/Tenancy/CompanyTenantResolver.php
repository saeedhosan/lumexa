<?php

declare(strict_types=1);

namespace App\Tenancy;

use App\Models\Company;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use SaeedHosan\Tenancy\Contracts\TenantResolver as TenantResolverContract;

class CompanyTenantResolver implements TenantResolverContract
{
    public function tenantModel(): string
    {
        return Company::class;
    }

    public function tenantKey(): string
    {
        return 'company_id';
    }

    public function resolveUserTenant(?Authenticatable $user): ?Model
    {
        if (! $user instanceof User) {
            return null;
        }

        return $user->currentCompany;
    }

    public function resolveRouteTenant(Request $request): ?Model
    {
        $companyId = $request->route('company_id');

        return $companyId
            ? $this->tenantModel()::query()->find($companyId)
            : null;
    }

    public function resolveAccessibleTenantKeys(?Authenticatable $user, ?Model $userTenant): array
    {
        if (! $user instanceof User) {
            return [];
        }

        return $userTenant instanceof Model ? [$userTenant->getKey()] : [];
    }

    public function shouldScope(?Authenticatable $user): bool
    {
        return $user instanceof User;
    }
}
