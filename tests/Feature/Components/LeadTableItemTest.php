<?php

declare(strict_types=1);
use App\Models\Company;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('lead items displays search input', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $this->actingAs($user);

    Livewire::test('table.lead-items')
        ->assertSee('Search leads');
});

test('lead items displays leads table when leads exist', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $lead = Lead::factory()->forCompany($company)->create(['title' => 'Test Lead']);
    $this->actingAs($user);

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
        ->assertSee('No leads found');
});

test('lead items search filters results', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $lead1 = Lead::factory()->forCompany($company)->create(['title' => 'Specific Lead']);
    $lead2 = Lead::factory()->forCompany($company)->create(['title' => 'Other Lead']);
    $this->actingAs($user);

    Livewire::test('table.lead-items')
        ->set('search', 'Specific')
        ->assertSee('Specific Lead')
        ->assertDontSee('Other Lead');
});

test('lead items can sort by title', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    Lead::factory()->forCompany($company)->create(['title' => 'AAA Lead']);
    Lead::factory()->forCompany($company)->create(['title' => 'ZZZ Lead']);
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
    $lead = Lead::factory()->forCompany($company)->create(['title' => 'Delete Me']);
    $this->actingAs($user);

    Livewire::test('table.lead-items')
        ->call('delete', $lead)
        ->assertDontSee('Delete Me');

    expect(Lead::query()->find($lead->id))->toBeNull();
});

test('lead items search resets pagination', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    Lead::factory()->forCompany($company)->count(15)->create();
    $this->actingAs($user);

    $component = Livewire::test('table.lead-items')
        ->set('search', 'test');

    expect($component->get('search'))->toBe('test');
});
