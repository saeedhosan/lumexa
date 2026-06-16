<?php

declare(strict_types=1);

namespace App\Actions\Admin;

use App\Models\Company;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class AddExistingUserToCompany
{
    public function handle(User $actor, Company $company, string $email, string $role): User
    {
        abort_if(
            ! $actor->isSuper() && ! $actor->companies()->where('companies.id', $company->id)->exists(),
            403,
            'You do not have permission to invite users to this company.'
        );

        $user = User::query()->where('email', $email)->firstOrFail();

        if ($user->companies()->where('company_id', $company->id)->exists()) {
            throw ValidationException::withMessages([
                'email' => 'This user already has access to this company.',
            ]);
        }

        $user->companies()->attach($company->id, ['role' => $role]);

        return $user;
    }
}
