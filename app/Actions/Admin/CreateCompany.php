<?php

declare(strict_types=1);

namespace App\Actions\Admin;

use App\Models\Company;
use Illuminate\Support\Facades\DB;

class CreateCompany
{
    public function handle(array $data, int $userId, string $role = Company::ROLE_USER): Company
    {
        return DB::transaction(function () use ($data, $userId, $role) {
            $company = Company::query()->create([
                'name'        => $data['name'],
                'title'       => $data['title']       ?? null,
                'description' => $data['description'] ?? null,
                'logo'        => $data['logo']        ?? null,
                'country'     => $data['country']     ?? null,
                'language'    => $data['language']    ?? null,
                'timezone'    => $data['timezone']    ?? null,
                'currency'    => $data['currency']    ?? null,
                'is_active'   => true,
                'created_by'  => $userId,
            ]);

            $company->users()->attach($userId, ['role' => $role]);

            return $company;
        });
    }
}
