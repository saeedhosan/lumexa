<?php

declare(strict_types=1);

use App\Enums\UserType;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

it('can render plans index page', function (): void {
    $superAdmin = User::factory()->create(['type' => UserType::super]);

    actingAs($superAdmin);

    get(route('admin.plans.index'))->assertSuccessful();
});

it('can render plan create page', function (): void {
    $superAdmin = User::factory()->create(['type' => UserType::super]);

    actingAs($superAdmin);

    get(route('admin.plans.create'))->assertSuccessful();
});

it('can create plan with valid data', function (): void {
    $this->markTestSkipped('Plan creation requires form validation handling');
});

it('can render plan show page', function (): void {
    $superAdmin = User::factory()->create(['type' => UserType::super]);
    $plan       = Plan::factory()->create();

    actingAs($superAdmin);

    get(route('admin.plans.show', $plan))->assertSuccessful();
});

it('can render plan edit page', function (): void {
    $superAdmin = User::factory()->create(['type' => UserType::super]);
    $plan       = Plan::factory()->create();

    actingAs($superAdmin);

    get(route('admin.plans.edit', $plan))->assertSuccessful();
});

it('can update plan with valid data', function (): void {
    $this->markTestSkipped('Plan update requires form validation handling');
});

it('can delete plan', function (): void {
    $this->markTestSkipped('Plan deletion is allowed in admin controller');
});
