<?php

declare(strict_types=1);

use App\Enums\UserType;
use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    Notification::fake();
});

it('can render the invite list screen', function (): void {
    $superAdmin = User::factory()->create(['type' => UserType::super]);

    actingAs($superAdmin);

    get(route('admin.invites.index'))->assertSuccessful();
});

it('can render the send invite form', function (): void {
    $superAdmin = User::factory()->create(['type' => UserType::super]);

    actingAs($superAdmin);

    get(route('admin.invites.create'))->assertSuccessful();
});

it('can add existing user to company as super admin', function (): void {
    $superAdmin = User::factory()->create(['type' => UserType::super]);
    $company    = Company::factory()->create();
    $newUser    = User::factory()->create(['email' => 'newuser@example.com']);

    actingAs($superAdmin);

    post(route('admin.invites.store'), [
        'company_id' => $company->id,
        'email'      => 'newuser@example.com',
        'role'       => 'customer',
    ])
        ->assertRedirect(route('admin.invites.index'))
        ->assertSessionHas('toast', 'User added to company successfully.');

    expect($newUser->companies()->where('company_id', $company->id)->exists())->toBeTrue();
});

it('shows error for user not found', function (): void {
    $superAdmin = User::factory()->create(['type' => UserType::super]);
    $company    = Company::factory()->create();

    actingAs($superAdmin);

    post(route('admin.invites.store'), [
        'company_id' => $company->id,
        'email'      => 'notfound@example.com',
        'role'       => 'customer',
    ])
        ->assertSessionHasErrors(['email']);
});

it('can add user to company as admin with company access', function (): void {
    $admin   = User::factory()->create(['type' => UserType::admin]);
    $company = Company::factory()->create();
    $company->users()->attach($admin, ['role' => Company::ROLE_ADMIN]);
    $newUser = User::factory()->create(['email' => 'newuser@example.com']);

    actingAs($admin);

    post(route('admin.invites.store'), [
        'company_id' => $company->id,
        'email'      => 'newuser@example.com',
        'role'       => 'customer',
    ])
        ->assertRedirect(route('admin.invites.index'));

    expect($newUser->companies()->where('company_id', $company->id)->exists())->toBeTrue();
});

it('cannot add user to company without access', function (): void {
    $admin        = User::factory()->create(['type' => UserType::admin]);
    $otherCompany = Company::factory()->create();
    $existingUser = User::factory()->create(['email' => 'exists@example.com']);

    actingAs($admin);

    post(route('admin.invites.store'), [
        'company_id' => $otherCompany->id,
        'email'      => 'exists@example.com',
        'role'       => 'customer',
    ])
        ->assertForbidden();
});

it('validates required fields when sending invite', function (): void {
    $superAdmin = User::factory()->create(['type' => UserType::super]);
    actingAs($superAdmin);

    post(route('admin.invites.store'), [])
        ->assertSessionHasErrors(['company_id', 'email', 'role']);
});

it('validates email format', function (): void {
    $superAdmin = User::factory()->create(['type' => UserType::super]);
    $company    = Company::factory()->create();
    actingAs($superAdmin);

    post(route('admin.invites.store'), [
        'company_id' => $company->id,
        'email'      => 'invalid-email',
        'role'       => 'customer',
    ])
        ->assertSessionHasErrors(['email']);
});

it('adds existing user directly to company if they exist but have no access', function (): void {
    $superAdmin   = User::factory()->create(['type' => UserType::super]);
    $company      = Company::factory()->create();
    $existingUser = User::factory()->create(['email' => 'exists@example.com']);

    actingAs($superAdmin);

    post(route('admin.invites.store'), [
        'company_id' => $company->id,
        'email'      => 'exists@example.com',
        'role'       => 'admin',
    ])
        ->assertRedirect(route('admin.invites.index'))
        ->assertSessionHas('toast', 'User added to company successfully.');

    expect($existingUser->companies()->where('company_id', $company->id)->exists())->toBeTrue();
});

it('shows error for user already has access to company', function (): void {
    $superAdmin   = User::factory()->create(['type' => UserType::super]);
    $company      = Company::factory()->create();
    $existingUser = User::factory()->create(['email' => 'member@example.com']);
    $existingUser->companies()->attach($company->id, ['role' => 'customer']);

    actingAs($superAdmin);

    post(route('admin.invites.store'), [
        'company_id' => $company->id,
        'email'      => 'member@example.com',
        'role'       => 'admin',
    ])
        ->assertSessionHasErrors(['email']);
});

it('shows only accessible company users for admin', function (): void {
    $admin    = User::factory()->create(['type' => UserType::admin]);
    $company1 = Company::factory()->create();
    $company2 = Company::factory()->create();
    $company1->users()->attach($admin, ['role' => Company::ROLE_ADMIN]);

    $user1 = User::factory()->create(['email' => 'user1@example.com']);
    $user1->companies()->attach($company1->id, ['role' => Company::ROLE_CUSTOMER]);

    $user2 = User::factory()->create(['email' => 'user2@example.com']);
    $user2->companies()->attach($company2->id, ['role' => Company::ROLE_CUSTOMER]);

    actingAs($admin);

    get(route('admin.invites.index'))
        ->assertSuccessful()
        ->assertSee('user1@example.com')
        ->assertDontSee('user2@example.com');
});
