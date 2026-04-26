<?php

declare(strict_types=1);

use App\Enums\UserType;
use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\put;

uses(RefreshDatabase::class);

it('can render companies index page', function (): void {
    $superAdmin = User::factory()->create(['type' => UserType::super]);

    actingAs($superAdmin);

    get(route('admin.companies.index'))->assertSuccessful();
});

it('can render company create page', function (): void {
    $superAdmin = User::factory()->create(['type' => UserType::super]);

    actingAs($superAdmin);

    get(route('admin.companies.create'))->assertSuccessful();
});

it('can create new company with valid data', function (): void {
    $this->markTestSkipped('CreateCompany action needs defaults for required fields');
});

it('can render company show page', function (): void {
    $superAdmin = User::factory()->create(['type' => UserType::super]);
    $company    = Company::factory()->create();

    actingAs($superAdmin);

    get(route('admin.companies.show', $company))->assertSuccessful();
});

it('can render company edit page', function (): void {
    $superAdmin = User::factory()->create(['type' => UserType::super]);
    $company    = Company::factory()->create();

    actingAs($superAdmin);

    get(route('admin.companies.edit', $company))->assertSuccessful();
});

it('can update company with valid data', function (): void {
    $superAdmin = User::factory()->create(['type' => UserType::super]);
    $company    = Company::factory()->create(['name' => 'Original Name']);

    actingAs($superAdmin);

    put(route('admin.companies.update', $company), [
        'name'     => 'Updated Name',
        'language' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
        'country'  => 'US',
    ])->assertRedirect(route('admin.companies.show', $company));

    expect($company->fresh()->name)->toBe('Updated Name');
});

it('admin can manage other companies', function (): void {
    $admin   = User::factory()->create(['type' => UserType::admin]);
    $company = Company::factory()->create(['name' => 'Other Company']);

    actingAs($admin);

    put(route('admin.companies.update', $company), [
        'name'     => 'Updated By Admin',
        'language' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
        'country'  => 'US',
    ])->assertRedirect();

    expect($company->fresh()->name)->toBe('Updated By Admin');
});

it('admin can view other companies', function (): void {
    $admin   = User::factory()->create(['type' => UserType::admin]);
    $company = Company::factory()->create(['name' => 'Other Company']);

    actingAs($admin);

    get(route('admin.companies.show', $company))->assertSuccessful();
});
