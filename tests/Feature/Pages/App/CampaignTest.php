<?php

declare(strict_types=1);

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guests are redirected to the login page for campaigns index', function (): void {
    $response = $this->get(route('app.campaigns.index'));
    $response->assertRedirect(route('login'));
});

test('guests are redirected to the login page for campaigns create', function (): void {
    $response = $this->get(route('app.campaigns.create'));
    $response->assertRedirect(route('login'));
});

test('guests are redirected to the login page for campaigns show', function (): void {
    $response = $this->get(route('app.campaigns.show', 1));
    $response->assertRedirect(route('login'));
});

test('guests are redirected to the login page for campaigns edit', function (): void {
    $response = $this->get(route('app.campaigns.edit', 1));
    $response->assertRedirect(route('login'));
});

test('authenticated users can access campaigns index', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $this->actingAs($user);

    $response = $this->get(route('app.campaigns.index'));
    $response->assertOk();
});

test('authenticated users can access campaigns create', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $this->actingAs($user);

    $response = $this->get(route('app.campaigns.create'));
    $response->assertOk();
});

test('authenticated users can access campaigns show', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $this->actingAs($user);

    $response = $this->get(route('app.campaigns.show', 'test-campaign'));
    $response->assertOk();
});

test('authenticated users can access campaigns edit', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $this->actingAs($user);

    $response = $this->get(route('app.campaigns.edit', 'test-campaign'));
    $response->assertOk();
});

test('campaigns index displays page', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $this->actingAs($user);

    $response = $this->get(route('app.campaigns.index'));
    $response->assertOk();
});

test('campaigns create page displays form', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $this->actingAs($user);

    $response = $this->get(route('app.campaigns.create'));
    $response->assertSee('Create');
});
