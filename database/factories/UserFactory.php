<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\UserStatus;
use App\Enums\UserType;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name'                      => fake()->name(),
            'email'                     => fake()->unique()->safeEmail(),
            'email_verified_at'         => now(),
            'password'                  => static::$password ??= Hash::make('password'),
            'remember_token'            => Str::random(10),
            'status'                    => UserStatus::default(),
            'type'                      => UserType::default(),
            'two_factor_secret'         => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at'   => null,
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes): array => [
            'email_verified_at' => null,
        ]);
    }

    public function withTwoFactor(): static
    {
        return $this->state(fn (array $attributes): array => [
            'two_factor_secret'         => encrypt('secret'),
            'two_factor_recovery_codes' => encrypt(json_encode(['recovery-code-1'])),
            'two_factor_confirmed_at'   => now(),
        ]);
    }

    public function status(UserStatus $status): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => $status,
        ]);
    }

    public function type(UserType $type): static
    {
        return $this->state(fn (array $attributes): array => [
            'type' => $type,
        ]);
    }

    public function super(): static
    {
        return $this->type(UserType::super);
    }

    public function customer(): static
    {
        return $this->type(UserType::user);
    }

    public function admin(): static
    {
        return $this->type(UserType::admin);
    }

    public function active(): static
    {
        return $this->status(UserStatus::active);
    }

    public function inactive(): static
    {
        return $this->status(UserStatus::inactive);
    }

    public function blocked(): static
    {
        return $this->status(UserStatus::blocked);
    }

    public function invited(): static
    {
        return $this->status(UserStatus::invited);
    }

    public function configure(): static
    {
        return $this->afterMaking(function (User $user): void {
            //
        })->afterCreating(function (User $user): void {
            //
        });
    }

    public function withCompany(?Company $company = null, ?string $role = null): static
    {
        return $this->afterCreating(function (User $user) use ($company, $role): void {
            $company ??= Company::factory()->create();
            $user->companies()->attach($company, ['role' => $role ?? Company::ROLE_CUSTOMER]);
        });
    }

    public function withCompanies(int $count = 1, ?string $role = null): static
    {
        return $this->afterCreating(function (User $user) use ($count, $role): void {
            $companies = Company::factory()->count($count)->create();
            $companies->each(fn (Company $company) => $user->companies()->attach($company, ['role' => $role ?? Company::ROLE_CUSTOMER]));
        });
    }
}
