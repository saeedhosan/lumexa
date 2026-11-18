<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Override;

/**
 * @extends Factory<Company>
 */
class CompanyFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->company();

        return [
            'uuid'        => fake()->uuid(),
            'name'        => $name,
            'slug'        => Str::slug($name),
            'logo'        => fake()->imageUrl(200, 200, 'business', true, 'logo'),
            'title'       => fake()->catchPhrase(),
            'description' => fake()->paragraph(),
            'settings'    => [
                'theme'         => fake()->randomElement(['light', 'dark']),
                'notifications' => [
                    'email' => fake()->boolean(),
                    'push'  => fake()->boolean(),
                ],
            ],

            'language' => fake()->randomElement(['en', 'es', 'fr', 'de']),
            'timezone' => fake()->timezone(),
            'currency' => fake()->randomElement(['USD', 'EUR', 'GBP']),
            'country'  => fake()->countryCode(),

            'is_active' => fake()->boolean(80),

            'created_by' => null,
            'updated_by' => null,
        ];
    }

    public function status(bool $status): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_active' => $status,
        ]);
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_active' => true,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_active' => false,
        ]);
    }

    #[Override]
    public function configure(): static
    {
        return $this->afterMaking(function (Company $company): void {
            //
        })->afterCreating(function (Company $company): void {
            //
        });
    }

    public function withCreator(?User $user = null): static
    {
        return $this->state(fn (array $attributes): array => [
            'created_by' => $user?->id ?? User::factory(),
        ]);
    }

    public function withUpdator(?User $user = null): static
    {
        return $this->state(fn (array $attributes): array => [
            'updated_by' => $user?->id ?? User::factory(),
        ]);
    }

    public function withUsers(int $count = 1, ?string $role = null): static
    {
        return $this->afterCreating(function (Company $company) use ($count, $role): void {
            $users = User::factory()->count($count)->create();
            $users->each(fn (User $user) => $company->users()->attach($user, ['role' => $role ?? Company::ROLE_USER]));
        });
    }

    public function withAdmin(int $count = 1): static
    {
        return $this->withUsers($count, Company::ROLE_ADMIN);
    }

    public function withCustomers(int $count = 1): static
    {
        return $this->withUsers($count, Company::ROLE_USER);
    }
}
