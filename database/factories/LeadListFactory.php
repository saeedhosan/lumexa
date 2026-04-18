<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\LeadListStatus;
use App\Models\Lead;
use App\Models\LeadList;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;

#[UseModel(LeadList::class)]
class LeadListFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'lead_id'       => Lead::factory(),
            'first_name'    => fake()->firstName(),
            'last_name'     => fake()->lastName(),
            'phone'         => fake()->phoneNumber(),
            'email'         => fake()->unique()->safeEmail(),
            'address'       => fake()->streetAddress(),
            'city'          => fake()->city(),
            'state'         => fake()->stateAbbr(),
            'zip_code'      => fake()->postcode(),
            'birth_of_date' => fake()->date(),
            'status'        => fake()->randomElement(LeadListStatus::values()),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => LeadListStatus::pending,
        ]);
    }

    public function cleaned(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => LeadListStatus::cleaned,
        ]);
    }

    public function blocked(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => LeadListStatus::blocked,
        ]);
    }

    public function invalid(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => LeadListStatus::invalid,
        ]);
    }

    public function duplicate(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => LeadListStatus::duplicate,
        ]);
    }

    public function spam(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => LeadListStatus::spam,
        ]);
    }

    public function dnc(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => LeadListStatus::dnc,
        ]);
    }

    public function archived(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => LeadListStatus::archived,
        ]);
    }

    public function forLead(Lead $lead): static
    {
        return $this->state(fn (array $attributes): array => [
            'lead_id' => $lead->id,
        ]);
    }
}
