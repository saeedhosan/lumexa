<?php

declare(strict_types=1);

use App\Models\Company;
use App\Models\Lead;
use App\Models\LeadList;
use App\Models\User;
use Livewire\Livewire;

test('lead lists displays search input', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $lead = Lead::factory()->create();
    $this->actingAs($user);

    Livewire::test('table.lead-lists', ['lead' => $lead])
        ->assertSee('Search leads...');
});

test('lead lists displays lead list table when records exist', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $lead = Lead::factory()->create();
    LeadList::factory()->create([
        'lead_id'    => $lead->id,
        'first_name' => 'John',
        'last_name'  => 'Doe',
    ]);
    $this->actingAs($user);

    Livewire::test('table.lead-lists', ['lead' => $lead])
        ->assertSee('John Doe');
});

test('lead lists displays empty state when no records', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $lead = Lead::factory()->create();
    $this->actingAs($user);

    Livewire::test('table.lead-lists', ['lead' => $lead])
        ->assertSee('No lead lists found.');
});

test('lead lists search filters by first name', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $lead = Lead::factory()->create();
    LeadList::factory()->create([
        'lead_id'    => $lead->id,
        'first_name' => 'John',
    ]);
    LeadList::factory()->create([
        'lead_id'    => $lead->id,
        'first_name' => 'Jane',
    ]);
    $this->actingAs($user);

    Livewire::test('table.lead-lists', ['lead' => $lead])
        ->set('search', 'John')
        ->assertSee('John')
        ->assertDontSee('Jane');
});

test('lead lists search filters by last name', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $lead = Lead::factory()->create();
    LeadList::factory()->create([
        'lead_id'   => $lead->id,
        'last_name' => 'Doe',
    ]);
    LeadList::factory()->create([
        'lead_id'   => $lead->id,
        'last_name' => 'Smith',
    ]);
    $this->actingAs($user);

    Livewire::test('table.lead-lists', ['lead' => $lead])
        ->set('search', 'Doe')
        ->assertSee('Doe')
        ->assertDontSee('Smith');
});

test('lead lists search filters by email', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $lead = Lead::factory()->create();
    LeadList::factory()->create([
        'lead_id' => $lead->id,
        'email'   => 'john@example.com',
    ]);
    LeadList::factory()->create([
        'lead_id' => $lead->id,
        'email'   => 'jane@example.com',
    ]);
    $this->actingAs($user);

    Livewire::test('table.lead-lists', ['lead' => $lead])
        ->set('search', 'john@example.com')
        ->assertSee('john@example.com')
        ->assertDontSee('jane@example.com');
});

test('lead lists search filters by phone', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $lead = Lead::factory()->create();
    LeadList::factory()->create([
        'lead_id' => $lead->id,
        'phone'   => '1234567890',
    ]);
    LeadList::factory()->create([
        'lead_id' => $lead->id,
        'phone'   => '0987654321',
    ]);
    $this->actingAs($user);

    Livewire::test('table.lead-lists', ['lead' => $lead])
        ->set('search', '123')
        ->assertSee('1234567890')
        ->assertDontSee('0987654321');
});

test('lead lists can sort by status', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $lead = Lead::factory()->create();
    $this->actingAs($user);

    Livewire::test('table.lead-lists', ['lead' => $lead])
        ->call('sort', 'status')
        ->assertSet('sortBy', 'status')
        ->assertSet('sortDirection', 'asc');
});

test('lead lists toggles sort direction on repeated sort', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $lead = Lead::factory()->create();
    $this->actingAs($user);

    Livewire::test('table.lead-lists', ['lead' => $lead])
        ->call('sort', 'status')
        ->assertSet('sortDirection', 'asc')
        ->call('sort', 'status')
        ->assertSet('sortDirection', 'desc');
});

test('lead lists delete removes lead list record', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $lead     = Lead::factory()->create();
    $leadList = LeadList::factory()->create([
        'lead_id'    => $lead->id,
        'first_name' => 'John',
    ]);
    $this->actingAs($user);

    Livewire::test('table.lead-lists', ['lead' => $lead])
        ->call('delete', $leadList)
        ->assertDontSee('John');

    expect(LeadList::find($leadList->id))->toBeNull();
});

test('lead lists search resets pagination', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $lead = Lead::factory()->create();
    LeadList::factory()->count(15)->create(['lead_id' => $lead->id]);
    $this->actingAs($user);

    Livewire::test('table.lead-lists', ['lead' => $lead])
        ->set('search', 'test')
        ->assertSet('search', 'test');
});
