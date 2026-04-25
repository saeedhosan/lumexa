<?php

declare(strict_types=1);

use App\Enums\UserType;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

it('can render services page', function () {
    $superAdmin = User::factory()->create(['type' => UserType::super]);

    actingAs($superAdmin);

    get(route('admin.services.index'))->assertSuccessful();
});

it('shows services list', function () {
    $superAdmin = User::factory()->create(['type' => UserType::super]);
    Service::factory()->count(3)->create();

    actingAs($superAdmin);

    get(route('admin.services.index'))
        ->assertSuccessful()
        ->assertSee('v1.0.0');
});

it('shows service details in table', function () {
    $superAdmin = User::factory()->create(['type' => UserType::super]);
    $service    = Service::factory()->create([
        'name'      => 'Test Service',
        'code'      => 'TEST',
        'version'   => '2.0.0',
        'is_active' => true,
    ]);

    actingAs($superAdmin);

    get(route('admin.services.index'))
        ->assertSuccessful()
        ->assertSee('Test Service')
        ->assertSee('TEST')
        ->assertSee('2.0.0')
        ->assertSee('Active');
});
