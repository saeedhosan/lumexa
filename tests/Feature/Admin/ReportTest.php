<?php

declare(strict_types=1);

use App\Enums\UserType;
use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

it('can render reports page', function (): void {
    $superAdmin = User::factory()->create(['type' => UserType::super]);

    actingAs($superAdmin);

    get(route('admin.reports.index'))->assertSuccessful();
});

it('shows user and company totals', function (): void {
    $superAdmin = User::factory()->create(['type' => UserType::super]);
    User::factory()->count(2)->create(['type' => UserType::user]);
    Company::factory()->count(3)->create();

    actingAs($superAdmin);

    get(route('admin.reports.index'))
        ->assertSuccessful()
        ->assertSee('3')
        ->assertSee('5');
});

it('shows users by type breakdown', function (): void {
    $superAdmin = User::factory()->create(['type' => UserType::super]);
    $company    = Company::factory()->create();

    $superAdmin->companies()->attach($company->id);

    $user1 = User::factory()->create(['type' => UserType::user]);
    $user1->companies()->attach($company->id);

    $user2 = User::factory()->create(['type' => UserType::admin]);
    $user2->companies()->attach($company->id);

    actingAs($superAdmin);

    get(route('admin.reports.index'))
        ->assertSuccessful()
        ->assertSee('Super')
        ->assertSee('User')
        ->assertSee('Admin');
});

it('admin only sees their company data', function (): void {
    $admin        = User::factory()->create(['type' => UserType::admin]);
    $company      = Company::factory()->create();
    $otherCompany = Company::factory()->create();

    $admin->companies()->attach($company->id, ['role' => Company::ROLE_ADMIN]);

    $userInCompany = User::factory()->create();
    $userInCompany->companies()->attach($company->id, ['role' => Company::ROLE_CUSTOMER]);

    $userInOtherCompany = User::factory()->create(['email' => 'other@example.com']);
    $userInOtherCompany->companies()->attach($otherCompany->id, ['role' => Company::ROLE_CUSTOMER]);

    actingAs($admin);

    get(route('admin.reports.index'))
        ->assertSuccessful()
        ->assertSee($company->name)
        ->assertDontSee($otherCompany->name);
});
