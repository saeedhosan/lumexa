<?php

declare(strict_types=1);

use App\Models\Company;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('customer can access services index page', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => Company::ROLE_USER]);
    $user->update(['current_company_id' => $company->id]);
    $this->actingAs($user);

    $response = $this->get(route('app.services.index'));
    $response->assertOk();
});

test('customer can access services create page', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => Company::ROLE_USER]);
    $user->update(['current_company_id' => $company->id]);
    $this->actingAs($user);

    $response = $this->get(route('app.services.create'));
    $response->assertOk();
});

test('guests are redirected to the login page for services index', function (): void {
    $response = $this->get(route('app.services.index'));
    $response->assertRedirect(route('login'));
});

test('guests are redirected to the login page for services create', function (): void {
    $response = $this->get(route('app.services.create'));
    $response->assertRedirect(route('login'));
});

test('guests are redirected to the login page for services show', function (): void {
    $response = $this->get(route('app.services.show', 1));
    $response->assertRedirect(route('login'));
});

test('guests are redirected to the login page for services edit', function (): void {
    $response = $this->get(route('app.services.edit', 1));
    $response->assertRedirect(route('login'));
});

test('authenticated users can access services index', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => Company::ROLE_ADMIN]);
    $user->update(['current_company_id' => $company->id]);
    $this->actingAs($user);

    $response = $this->get(route('app.services.index'));
    $response->assertOk();
});

test('authenticated users can access services create', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => Company::ROLE_ADMIN]);
    $user->update(['current_company_id' => $company->id]);
    $this->actingAs($user);

    $response = $this->get(route('app.services.create'));
    $response->assertOk();
});

test('services index displays empty when no services', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => Company::ROLE_ADMIN]);
    $user->update(['current_company_id' => $company->id]);
    $this->actingAs($user);

    $response = $this->get(route('app.services.index'));
    $response->assertOk();
});

test('services index displays all services regardless of company', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => Company::ROLE_ADMIN]);
    $user->update(['current_company_id' => $company->id]);

    Service::factory()->create(['name' => 'Campaign Monitoring', 'version' => '0.0']);
    Service::factory()->create(['name' => 'Other Service', 'version' => '1.0.0']);

    $this->actingAs($user);

    $response = $this->get(route('app.services.index'));
    $response->assertSee('Campaign Monitoring');
    $response->assertSee('0.0');
    $response->assertSee('Other Service');
});
