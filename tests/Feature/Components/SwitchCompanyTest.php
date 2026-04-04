<?php

declare(strict_types=1);

use App\Models\Company;
use App\Models\User;
use Livewire\Livewire;

test('company switch displays current company name', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $this->actingAs($user);

    Livewire::test('company.switch')
        ->assertSee($company->name);
});

test('company switch displays select company text when no company selected', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test('company.switch')
        ->assertSee(__('Select Company'));
});

test('company switch shows all user companies', function (): void {
    $user     = User::factory()->create();
    $company1 = Company::factory()->create(['name' => 'Company One']);
    $company2 = Company::factory()->create(['name' => 'Company Two']);
    $user->companies()->attach($company1, ['role' => 'admin']);
    $user->companies()->attach($company2, ['role' => 'admin']);
    $user->update(['current_company_id' => $company1->id]);
    $this->actingAs($user);

    Livewire::test('company.switch')
        ->assertSee('Company One')
        ->assertSee('Company Two');
});

test('company switch shows check icon on current company', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $this->actingAs($user);

    $html = Livewire::test('company.switch')->html();

    expect($html)->toContain('check');
});

test('company switch can switch to another company', function (): void {
    $user     = User::factory()->create();
    $company1 = Company::factory()->create(['name' => 'Company One']);
    $company2 = Company::factory()->create(['name' => 'Company Two']);
    $user->companies()->attach($company1, ['role' => 'admin']);
    $user->companies()->attach($company2, ['role' => 'admin']);
    $user->update(['current_company_id' => $company1->id]);
    $this->actingAs($user);

    Livewire::test('company.switch')
        ->call('switchCompany', $company2, '/dashboard')
        ->assertRedirect('/dashboard');

    $user->refresh();
    expect($user->current_company_id)->toBe($company2->id);
});

test('company switch does not switch if company not associated with user', function (): void {
    $user     = User::factory()->create();
    $company1 = Company::factory()->create(['name' => 'Company One']);
    $company2 = Company::factory()->create(['name' => 'Company Two']);
    $user->companies()->attach($company1, ['role' => 'admin']);
    $user->update(['current_company_id' => $company1->id]);
    $this->actingAs($user);

    Livewire::test('company.switch')
        ->call('switchCompany', $company2, '/dashboard')
        ->assertNoRedirect();

    $user->refresh();
    expect($user->current_company_id)->toBe($company1->id);
});
