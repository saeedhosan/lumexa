<?php

declare(strict_types=1);

namespace App\Actions\Admin;

use App\Enums\UserType;
use App\Models\Company;
use App\Models\User;

class CreateUser
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(User $actor, array $data): User
    {
        $type = $actor->isSuper() && isset($data['type'])
            ? $data['type']
            : UserType::user;

        $companyId = $data['current_company_id'] ?? $actor->current_company_id;

        unset($data['type'], $data['current_company_id']);

        $user = User::query()->create([
            ...$data,
            'type' => $type,
        ]);

        if ($companyId) {
            $user->companies()->attach($companyId, ['role' => Company::ROLE_ADMIN]);
        }

        return $user;
    }
}
