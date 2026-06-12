<?php

declare(strict_types=1);

namespace App\Actions\Company;

use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CreateCompanyForUser
{
    public function handle(User $user, string $companyName): Company
    {
        return DB::transaction(function () use ($user, $companyName): Company {
            $company = Company::query()->create([
                'name'       => $companyName,
                'is_active'  => true,
                'created_by' => $user->id,
            ]);

            $company->users()->attach($user->id, ['role' => Company::ROLE_ADMIN]);

            $user->update(['current_company_id' => $company->id]);

            return $company;
        });
    }
}
