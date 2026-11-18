<?php

declare(strict_types=1);

namespace App\Tenancy;

use App\Enums\UserType;
use App\Models\Company;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use SaeedHosan\Tenancy\Contracts\TenantResolver as TenantResolverContract;

/**
 * Resolve the active company as the request tenant.
 *
 * @implements TenantResolverContract<Company>
 */
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
        if (! $user instanceof User || $user->type !== UserType::user) {
            return null;
        }

        $company = $user->currentCompany;

        return $company instanceof Company && $user->belongsToCompany($company)
            ? $company
            : null;
    }

    public function resolveRouteTenant(Request $request): ?Model
    {
        $host      = $request->getHost();
        $subdomain = explode('.', $host)[0] ?? null;

        if (! in_array($subdomain, [null, 'app', 'www'], true)) {
            $company = Company::query()->where('slug', $subdomain)->first();

            if ($company !== null) {
                return $company;
            }
        }

        $companyId = $request->route('company_id');

        return $companyId
            ? Company::query()->find($companyId)
            : null;
    }

    public function resolveAccessibleTenantKeys(?Authenticatable $user, ?Model $userTenant): array
    {
        if (! $user instanceof User || $user->type !== UserType::user || ! $userTenant instanceof Company) {
            return [];
        }

        // A user may belong to several companies, but each request is scoped
        // to the currently selected company only.
        return [$userTenant->getKey()];
    }

    public function shouldScope(?Authenticatable $user): bool
    {
        // Admin and super-admin routes are intentionally outside customer tenancy.
        return $user instanceof User && $user->type === UserType::user;
    }
}
