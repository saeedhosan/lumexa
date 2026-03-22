<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Plan>
 */
class PlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'                    => $this->faker->word(),
            'slug'                    => $this->faker->unique()->slug(),
            'description'             => $this->faker->sentence(),
            'is_active'               => true,
            'is_default'              => false,
            'trial_days'              => 14,
            'max_users'               => 5,
            'price_monthly'           => 29.00,
            'price_yearly'            => 290.00,
            'currency'                => 'USD',
            'stripe_price_id_monthly' => null,
            'stripe_price_id_yearly'  => null,
        ];
    }

    public function starter(): static
    {
        return $this->state(fn (array $attributes): array => [
            'name'          => 'Starter',
            'slug'          => 'starter',
            'description'   => 'Perfect for small teams',
            'max_users'     => 1,
            'price_monthly' => 9.00,
            'price_yearly'  => 90.00,
        ]);
    }

    public function professional(): static
    {
        return $this->state(fn (array $attributes): array => [
            'name'          => 'Professional',
            'slug'          => 'professional',
            'description'   => 'For growing businesses',
            'max_users'     => 5,
            'price_monthly' => 29.00,
            'price_yearly'  => 290.00,
        ]);
    }

    public function enterprise(): static
    {
        return $this->state(fn (array $attributes): array => [
            'name'          => 'Enterprise',
            'slug'          => 'enterprise',
            'description'   => 'Unlimited everything',
            'max_users'     => 0,
            'price_monthly' => 99.00,
            'price_yearly'  => 990.00,
        ]);
    }
}
