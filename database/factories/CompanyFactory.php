<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->company();

        return [
            'uuid'        => $this->faker->uuid(),
            'name'        => $name,
            'slug'        => Str::slug($name),
            'logo'        => $this->faker->imageUrl(200, 200, 'business', true, 'logo'),
            'title'       => $this->faker->catchPhrase(),
            'description' => $this->faker->paragraph(),
            'config'      => [
                'theme'         => $this->faker->randomElement(['light', 'dark']),
                'notifications' => [
                    'email' => $this->faker->boolean(),
                    'push'  => $this->faker->boolean(),
                ],
            ],

            'language' => $this->faker->randomElement(['en', 'es', 'fr', 'de']),
            'timezone' => $this->faker->timezone(),
            'currency' => $this->faker->randomElement(['USD', 'EUR', 'GBP']),
            'country'  => $this->faker->countryCode(),

            'status'    => $this->faker->boolean(80),
            'is_public' => $this->faker->boolean(70),

            'created_by' => null,
            'updated_by' => null,
        ];
    }

    public function status(bool $status): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => $status,
        ]);
    }

    public function isPublic(bool $isPublic = true): static
    {
        return $this->state(fn (array $attributes) => [
            'is_public' => $isPublic,
        ]);
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => true,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => false,
        ]);
    }

    public function configure(): static
    {
        return $this->afterMaking(function (Company $company) {
            //
        })->afterCreating(function (Company $company) {
            //
        });
    }

    public function withCreator(?User $user = null): static
    {
        return $this->state(fn (array $attributes) => [
            'created_by' => $user?->id ?? User::factory(),
        ]);
    }

    public function withUpdator(?User $user = null): static
    {
        return $this->state(fn (array $attributes) => [
            'updated_by' => $user?->id ?? User::factory(),
        ]);
    }

    public function withUsers(int $count = 1, ?string $role = null): static
    {
        return $this->afterCreating(function (Company $company) use ($count, $role) {
            $users = User::factory()->count($count)->create();
            $users->each(fn (User $user) => $company->users()->attach($user, ['role' => $role ?? Company::ROLE_CUSTOMER]));
        });
    }

    public function withAdmin(int $count = 1): static
    {
        return $this->withUsers($count, Company::ROLE_ADMIN);
    }

    public function withCustomers(int $count = 1): static
    {
        return $this->withUsers($count, Company::ROLE_CUSTOMER);
    }
}
