<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Company;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @template TModel of \App\Models\Product
 *
 * @extends Factory<TModel>
 */
class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<TModel>
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->words(3, true);

        return [
            'uuid'       => $this->faker->uuid(),
            'name'       => $name,
            'slug'       => Str::slug($name),
            'icon'       => $this->faker->imageUrl(64, 64, 'technology', true, 'icon'),
            'logo'       => $this->faker->imageUrl(200, 200, 'technology', true, 'logo'),
            'code'       => mb_strtoupper($this->faker->unique()->lexify('????')),
            'about'      => $this->faker->paragraph(),
            'is_active'  => $this->faker->boolean(80),
            'is_default' => false,
            'version'    => Product::DEFAULT_VERSION,
            'provider'   => $this->faker->randomElement(['internal', 'external', null]),
            'features'   => [
                'api_access'      => $this->faker->boolean(),
                'webhooks'        => $this->faker->boolean(),
                'custom_branding' => $this->faker->boolean(),
                'analytics'       => $this->faker->boolean(),
            ],
            'settings' => [
                'theme'         => $this->faker->randomElement(['light', 'dark', 'system']),
                'notifications' => [
                    'email' => $this->faker->boolean(),
                    'push'  => $this->faker->boolean(),
                ],
            ],

            'created_by' => null,
            'updated_by' => null,
        ];
    }

    /**
     * Indicate that the product is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the product is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the product is the default.
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
    public function configure(): static
    {
        return $this->afterMaking(function (Product $product): void {
            //
        })->afterCreating(function (Product $product): void {
            //
        });
    }

    /**
     * Attach a creator to the product.
     */
    public function withCreator(User|int|string|null $user = null): static
    {
        return $this->state(fn (array $attributes): array => [
            'created_by' => $user instanceof User ? $user->id : ($user ?? User::factory()),
        ]);
    }

    /**
     * Attach an updator to the product.
     */
    public function withUpdator(User|int|string|null $user = null): static
    {
        return $this->state(fn (array $attributes): array => [
            'updated_by' => $user instanceof User ? $user->id : ($user ?? User::factory()),
        ]);
    }

    /**
     * Attach companies to the product.
     */
    public function withCompanies(int $count = 1): static
    {
        return $this->afterCreating(function (Product $product) use ($count): void {
            $companies = Company::factory()->count($count)->create();
            $companies->each(fn (Company $company) => $product->companies()->attach($company));
        });
    }
}
