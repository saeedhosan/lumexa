<?php

declare(strict_types=1);

namespace App\Tenancy;

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
        if (! $user instanceof User || ! $user->isUser()) {
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
        $subdomain = explode('.', $host)[0];

        if (! in_array($subdomain, ['app', 'www'], true)) {
            $company = Company::query()->where('slug', $subdomain)->first();

            if ($company !== null) {
                return $company;
            }
        }

        $companyId = $request->route('company_id');

        if (! is_numeric($companyId)) {
            return null;
        }

        return Company::query()->whereKey((int) $companyId)->first();
    }

    public function resolveAccessibleTenantKeys(?Authenticatable $user, ?Model $userTenant): array
    {
        if (! $user instanceof User || ! $user->isUser() || ! $userTenant instanceof Company) {
            return [];
        }

        // A user may belong to several companies, but each request is scoped
        // to the currently selected company only.
        return [(int) $userTenant->getKey()];
    }

    public function shouldScope(?Authenticatable $user): bool
    {
        // Admin and super-admin routes are intentionally outside customer tenancy.
        return $user instanceof User && $user->isUser();
    }
}
