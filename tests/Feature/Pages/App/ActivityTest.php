<?php

declare(strict_types=1);

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guests are redirected to the login page for index', function (): void {
    $response = $this->get(route('app.activities.index'));
    $response->assertRedirect(route('login'));
});

test('guests are redirected to the login page for create', function (): void {
    $response = $this->get(route('app.activities.create'));
    $response->assertRedirect(route('login'));
});

test('guests are redirected to the login page for show', function (): void {
    $response = $this->get(route('app.activities.show', 1));
    $response->assertRedirect(route('login'));
});

test('guests are redirected to the login page for edit', function (): void {
    $response = $this->get(route('app.activities.edit', 1));
    $response->assertRedirect(route('login'));
});

test('authenticated users can access index', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $this->actingAs($user);

    $response = $this->get(route('app.activities.index'));
    $response->assertOk();
});

test('authenticated users can access activities create', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $this->actingAs($user);

    $response = $this->get(route('app.activities.create'));
    $response->assertOk();
});

test('authenticated users can access activities show', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $this->actingAs($user);

    $response = $this->get(route('app.activities.show', 'test-activity'));
    $response->assertOk();
});

test('authenticated users can access activities edit', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $this->actingAs($user);

    $response = $this->get(route('app.activities.edit', 'test-activity'));
    $response->assertOk();
});

test('activities index displays page', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $this->actingAs($user);

    $response = $this->get(route('app.activities.index'));
    $response->assertOk();
});

test('activities create page displays form', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $this->actingAs($user);

    $response = $this->get(route('app.activities.create'));
    $response->assertSee('Create');
});
