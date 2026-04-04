<?php

declare(strict_types=1);

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guests are redirected to the login page for companies index', function (): void {
    $response = $this->get(route('app.companies.index'));
    $response->assertRedirect(route('login'));
});

test('guests are redirected to the login page for companies create', function (): void {
    $response = $this->get(route('app.companies.create'));
    $response->assertRedirect(route('login'));
});

test('guests are redirected to the login page for companies show', function (): void {
    $response = $this->get(route('app.companies.show', 1));
    $response->assertRedirect(route('login'));
});

test('guests are redirected to the login page for companies edit', function (): void {
    $response = $this->get(route('app.companies.edit', 1));
    $response->assertRedirect(route('login'));
});

test('authenticated users can access companies index', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $this->actingAs($user);

    $response = $this->get(route('app.companies.index'));
    $response->assertOk();
});

test('authenticated users can access companies create', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $this->actingAs($user);

    $response = $this->get(route('app.companies.create'));
    $response->assertOk();
});

test('authenticated users can access companies show', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $this->actingAs($user);

    $response = $this->get(route('app.companies.show', $company));
    $response->assertOk();
});

test('authenticated users can access companies edit', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $this->actingAs($user);

    $response = $this->get(route('app.companies.edit', $company));
    $response->assertOk();
});

test('companies index displays user companies', function (): void {
    $user     = User::factory()->create();
    $company1 = Company::factory()->create(['name' => 'Company One']);
    $company2 = Company::factory()->create(['name' => 'Company Two']);
    $user->companies()->attach($company1, ['role' => 'admin']);
    $user->companies()->attach($company2, ['role' => 'admin']);
    $user->update(['current_company_id' => $company1->id]);
    $this->actingAs($user);

    $response = $this->get(route('app.companies.index'));
    $response->assertSee('Company One');
    $response->assertSee('Company Two');
});

test('companies show displays company details', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create(['name' => 'Test Company']);
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $this->actingAs($user);

    $response = $this->get(route('app.companies.show', $company));
    $response->assertSee('Test Company');
});
