<?php

declare(strict_types=1);

use App\Enums\UserType;
use App\Models\Log;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

it('can render logs index page', function (): void {
    $superAdmin = User::factory()->create(['type' => UserType::super]);

    actingAs($superAdmin);

    get(route('admin.logs.index'))->assertSuccessful();
});

it('logs display correctly', function (): void {
    $superAdmin = User::factory()->create(['type' => UserType::super]);
    Log::factory()->count(3)->create();

    actingAs($superAdmin);

    get(route('admin.logs.index'))
        ->assertSuccessful();
});

it('logs show user activity', function (): void {
    $superAdmin = User::factory()->create(['type' => UserType::super]);
    $user       = User::factory()->create();
    Log::factory()->for($user)->count(2)->create();

    actingAs($superAdmin);

    get(route('admin.logs.index'))
        ->assertSuccessful();
});
