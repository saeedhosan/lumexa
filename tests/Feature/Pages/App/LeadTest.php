<?php

declare(strict_types=1);

use App\Enums\LeadLestStatus;
use App\Models\Company;
use App\Models\Lead;
use App\Models\LeadList;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guests are redirected to the login page for leads index', function (): void {
    $response = $this->get(route('app.leads.index'));
    $response->assertRedirect(route('login'));
});

test('guests are redirected to the login page for leads create', function (): void {
    $response = $this->get(route('app.leads.create'));
    $response->assertRedirect(route('login'));
});

test('guests are redirected to the login page for leads show', function (): void {
    $response = $this->get(route('app.leads.show', 1));
    $response->assertRedirect(route('login'));
});

test('guests are redirected to the login page for leads edit', function (): void {
    $response = $this->get(route('app.leads.edit', 1));
    $response->assertRedirect(route('login'));
});

test('authenticated users can access leads index', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $this->actingAs($user);

    $response = $this->get(route('app.leads.index'));
    $response->assertOk();
});

test('authenticated users can access leads create', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $this->actingAs($user);

    $response = $this->get(route('app.leads.create'));
    $response->assertOk();
});

test('authenticated users can access leads show', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $this->actingAs($user);

    $lead = Lead::factory()->forCompany($company)->create();

    $response = $this->get(route('app.leads.show', $lead));
    $response->assertOk();
});

test('leads index displays search functionality', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $this->actingAs($user);

    $response = $this->get(route('app.leads.index'));
    $response->assertOk();
});

test('leads index displays leads', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $this->actingAs($user);

    $lead = Lead::factory()->forCompany($company)->create(['title' => 'Test Lead']);

    $response = $this->get(route('app.leads.index'));
    $response->assertSee('Test Lead');
});

test('leads index supports sorting by title', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $this->actingAs($user);

    $response = $this->get(route('app.leads.index', ['sort' => 'title', 'direction' => 'asc']));
    $response->assertOk();
});

test('leads show displays lead details page', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $this->actingAs($user);

    $lead = Lead::factory()->forCompany($company)->create(['title' => 'My Lead']);

    $response = $this->get(route('app.leads.show', $lead));
    $response->assertOk();
});

test('leads show displays lead lists', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $this->actingAs($user);

    $lead = Lead::factory()->forCompany($company)->create();
    LeadList::factory()->forLead($lead)->create([
        'first_name' => 'John',
        'last_name'  => 'Doe',
        'status'     => LeadLestStatus::pending,
    ]);

    $response = $this->get(route('app.leads.show', $lead));
    $response->assertOk();
});
