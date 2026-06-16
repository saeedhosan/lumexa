<?php

declare(strict_types=1);

namespace App\Actions\Admin;

use App\Models\Company;
use App\Models\User;

class UpdateUser
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(User $actor, User $user, array $data): User
    {
        if (isset($data['password']) && empty($data['password'])) {
            unset($data['password']);
        }

        if ($actor->isAdmin() && isset($data['type'])) {
            unset($data['type']);
        }

        $user->update($data);

        $companyId = $data['current_company_id'] ?? $actor->current_company_id;

        if ($companyId) {
            $user->companies()->syncWithoutDetaching([
                $companyId => ['role' => Company::ROLE_ADMIN],
            ]);
        }

        return $user->fresh();
    }
}
