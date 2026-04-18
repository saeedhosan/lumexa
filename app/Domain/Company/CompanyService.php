<?php

declare(strict_types=1);

namespace App\Domain\Company;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CompanyService
{
    public function getForUser(User $user): Collection
    {
        return $user->companies()->orderBy('name')->get();
    }

    public function getAll(): Collection
    {
        return Company::orderBy('name')->get();
    }

    public function getById(int $id): ?Company
    {
        return Company::find($id);
    }

    public function getByUuid(string $uuid): ?Company
    {
        return Company::where('uuid', $uuid)->first();
    }

    public function getBySlug(string $slug): ?Company
    {
        return Company::where('slug', $slug)->first();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Company::orderBy('name')->paginate($perPage);
    }

    public function create(array $data): Company
    {
        return Company::create($data);
    }

    public function update(Company $company, array $data): Company
    {
        $company->update($data);

        return $company->fresh();
    }

    public function delete(Company $company): int|bool
    {
        return $company->delete();
    }

    public function attachUser(Company $company, User $user, string $role = Company::ROLE_CUSTOMER): void
    {
        $company->users()->syncWithoutDetaching([
            $user->id => ['role' => $role],
        ]);
    }

    public function detachUser(Company $company, User $user): void
    {
        $company->users()->detach($user->id);
    }

    public function getUsers(Company $company): Collection
    {
        return $company->users()->get();
    }

    public function getAdmins(Company $company): Collection
    {
        return $company->admins()->get();
    }

    public function getCustomers(Company $company): Collection
    {
        return $company->customers()->get();
    }
}
