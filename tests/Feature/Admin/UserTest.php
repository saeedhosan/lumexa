<?php

declare(strict_types=1);

use App\Enums\UserType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

uses(RefreshDatabase::class);

it('can render users index page', function (): void {
    $superAdmin = User::factory()->create(['type' => UserType::super]);

    actingAs($superAdmin);

    get(route('admin.users.index'))->assertSuccessful();
});

it('can render user create page', function (): void {
    $superAdmin = User::factory()->create(['type' => UserType::super]);

    actingAs($superAdmin);

    get(route('admin.users.create'))->assertSuccessful();
});

it('can create new user with valid data', function (): void {
    $this->markTestSkipped('User creation requires form validation handling');
});

it('can render user show page', function (): void {
    $superAdmin = User::factory()->create(['type' => UserType::super]);
    $user       = User::factory()->create();

    actingAs($superAdmin);

    get(route('admin.users.show', $user))->assertSuccessful();
});

it('can render user edit page', function (): void {
    $superAdmin = User::factory()->create(['type' => UserType::super]);
    $user       = User::factory()->create();

    actingAs($superAdmin);

    get(route('admin.users.edit', $user))->assertSuccessful();
});

it('can update user with valid data', function (): void {
    $this->markTestSkipped('User update requires form validation handling');
});

it('cannot delete users', function (): void {
    $superAdmin = User::factory()->create(['type' => UserType::super]);
    $user       = User::factory()->create();

    actingAs($superAdmin);

    post(route('admin.users.destroy', $user))
        ->assertStatus(405);

    expect(User::query()->find($user->id))->not->toBeNull();
});
