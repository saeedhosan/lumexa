<?php

declare(strict_types=1);

use App\Enums\UserType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

it('can render billing index page', function (): void {
    $superAdmin = User::factory()->create(['type' => UserType::super]);

    actingAs($superAdmin);

    get(route('admin.billing.index'))->assertSuccessful();
});

it('can render billing create page', function (): void {
    $superAdmin = User::factory()->create(['type' => UserType::super]);

    actingAs($superAdmin);

    get(route('admin.billing.create'))->assertSuccessful();
});

it('can create billing record', function (): void {
    $this->markTestSkipped('Billing creation requires form validation handling');
});
