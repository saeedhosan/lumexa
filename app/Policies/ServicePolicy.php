<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Service;
use App\Models\User;
use App\Policies\Concerns\SuperPolicyBefore;

class ServicePolicy
{
    use SuperPolicyBefore;

    public function viewAny(User $user): bool
    {
        return $user->companies()->exists();
    }

    public function view(User $user, Service $service): bool
    {
        return $service->companies()
            ->whereKey($user->companies()->select('companies.id'))
            ->exists();
    }

    public function create(User $user): bool
    {
        return $this->belongsToAnyServiceCompany($user);
    }

    public function update(User $user, Service $service): bool
    {
        return $this->belongsToServiceCompany($user, $service);
    }

    public function delete(User $user, Service $service): bool
    {
        return $this->belongsToServiceCompany($user, $service);
    }

    /**
     * User must belong to at least one company that owns services
     */
    private function belongsToAnyServiceCompany(User $user): bool
    {
        return $user->companies()->exists();
    }

    /**
     * User must belong to the service's company
     */
    private function belongsToServiceCompany(User $user, Service $service): bool
    {
        return $service->companies()
            ->whereKey($user->companies()->select('companies.id'))
            ->exists();
    }
}
