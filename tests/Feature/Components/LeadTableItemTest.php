<?php

declare(strict_types=1);

use App\Models\Company;
use App\Models\Lead;
use App\Models\User;
use Livewire\Livewire;

test('lead items displays search input', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $this->actingAs($user);

    Livewire::test('table.lead-items')
        ->assertSee('Search leads...');
});

test('lead items displays leads table when leads exist', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $this->actingAs($user);

    $lead = Lead::factory()->forCompany($company)->create(['title' => 'Test Lead']);

    Livewire::test('table.lead-items')
        ->assertSee('Test Lead');
});

test('lead items displays empty state when no leads', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $this->actingAs($user);

    Livewire::test('table.lead-items')
        ->assertSee('No leads found.');
});

test('lead items search filters results', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $this->actingAs($user);

    Lead::factory()->forCompany($company)->create(['title' => 'Test Lead One']);
    Lead::factory()->forCompany($company)->create(['title' => 'Test Lead Two']);

    Livewire::test('table.lead-items')
        ->set('search', 'One')
        ->assertSee('Test Lead One')
        ->assertDontSee('Test Lead Two');
});

test('lead items can sort by title', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $this->actingAs($user);

    Livewire::test('table.lead-items')
        ->call('sort', 'title')
        ->assertSet('sortBy', 'title')
        ->assertSet('sortDirection', 'asc');
});

test('lead items can sort by status', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $this->actingAs($user);

    Livewire::test('table.lead-items')
        ->call('sort', 'status')
        ->assertSet('sortBy', 'status')
        ->assertSet('sortDirection', 'asc');
});

test('lead items can sort by created_at', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $this->actingAs($user);

    Livewire::test('table.lead-items')
        ->call('sort', 'created_at')
        ->assertSet('sortBy', 'created_at')
        ->assertSet('sortDirection', 'asc');
});

test('lead items toggles sort direction on repeated sort', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $this->actingAs($user);

    Livewire::test('table.lead-items')
        ->call('sort', 'title')
        ->assertSet('sortDirection', 'asc')
        ->call('sort', 'title')
        ->assertSet('sortDirection', 'desc');
});

test('lead items delete removes lead', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $this->actingAs($user);

    $lead = Lead::factory()->forCompany($company)->create(['title' => 'Lead to Delete']);

    Livewire::test('table.lead-items')
        ->call('delete', $lead)
        ->assertDontSee('Lead to Delete');

    expect(Lead::query()->find($lead->id))->toBeNull();
});

test('lead items search resets pagination', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $this->actingAs($user);

    Lead::factory()->forCompany($company)->count(15)->create();

    Livewire::test('table.lead-items')
        ->set('search', 'test')
        ->assertSet('search', 'test');
});
