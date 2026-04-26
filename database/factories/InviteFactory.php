<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Company;
use App\Models\Invite;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;

#[UseModel(Invite::class)]
class InviteFactory extends Factory
{
    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'invited_by' => User::factory(),
            'email'      => fake()->unique()->safeEmail(),
            'role'       => 'admin',
        ];
    }
}
