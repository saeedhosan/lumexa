<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Company;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Override;

/**
 * @template TModel of \App\Models\Service
 *
 * @extends Factory<TModel>
 */
class ServiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<TModel>
     */
    protected $model = Service::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        return [
            'uuid'       => fake()->uuid(),
            'name'       => $name,
            'slug'       => Str::slug($name),
            'icon'       => fake()->imageUrl(64, 64, 'technology', true, 'icon'),
            'logo'       => fake()->imageUrl(200, 200, 'technology', true, 'logo'),
            'code'       => mb_strtoupper(fake()->unique()->lexify('????')),
            'about'      => fake()->paragraph(),
            'is_active'  => fake()->boolean(80),
            'is_default' => false,
            'version'    => Service::DEFAULT_VERSION,
            'provider'   => fake()->randomElement(['internal', 'external', null]),
            'features'   => [
                'api_access'      => fake()->boolean(),
                'webhooks'        => fake()->boolean(),
                'custom_branding' => fake()->boolean(),
                'analytics'       => fake()->boolean(),
            ],
            'settings' => [
                'theme'         => fake()->randomElement(['light', 'dark', 'system']),
                'notifications' => [
                    'email' => fake()->boolean(),
                    'push'  => fake()->boolean(),
                ],
            ],

            'created_by' => null,
            'updated_by' => null,
        ];
    }

    /**
     * Indicate that the service is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the service is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the service is the default.
     */
    public function default(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_default' => true,
        ]);
    }

    /**
     * Configure the factory.
     */
    #[Override]
    public function configure(): static
    {
        return $this->afterMaking(function (Service $service): void {
            //
        })->afterCreating(function (Service $service): void {
            //
        });
    }

    /**
     * Attach a creator to the service.
     */
    public function withCreator(User|int|string|null $user = null): static
    {
        return $this->state(fn (array $attributes): array => [
            'created_by' => $user instanceof User ? $user->id : ($user ?? User::factory()),
        ]);
    }

    /**
     * Attach an updator to the service.
     */
    public function withUpdator(User|int|string|null $user = null): static
    {
        return $this->state(fn (array $attributes): array => [
            'updated_by' => $user instanceof User ? $user->id : ($user ?? User::factory()),
        ]);
    }

    /**
     * Attach companies to the service.
     */
    public function withCompanies(int $count = 1): static
    {
        return $this->afterCreating(function (Service $service) use ($count): void {
            $companies = Company::factory()->count($count)->create();
            $companies->each(fn (Company $company) => $service->companies()->attach($company));
        });
    }
}
