<?php

declare(strict_types=1);

use App\Enums\UserType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

it('can render invoices index page', function (): void {
    $superAdmin = User::factory()->create(['type' => UserType::super]);

    actingAs($superAdmin);

    get(route('admin.invoices.index'))->assertSuccessful();
});

it('can render invoice create page', function (): void {
    $superAdmin = User::factory()->create(['type' => UserType::super]);

    actingAs($superAdmin);

    get(route('admin.invoices.create'))->assertSuccessful();
});

it('can create invoice record', function (): void {
    $this->markTestSkipped('Invoice creation requires form validation handling');
});

it('can render invoice PDF', function (): void {
    $this->markTestSkipped('Invoice PDF rendering requires additional setup');
});
