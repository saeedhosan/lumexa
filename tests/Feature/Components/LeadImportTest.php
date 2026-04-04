<?php

declare(strict_types=1);

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Testing\FileFactory;
use Livewire\Livewire;

test('lead import modal can be opened', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test('leads.lead-import')
        ->assertSee('Import leads')
        ->assertSee('Import leads');
});

test('lead import validates required fields', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test('leads.lead-import')
        ->call('import')
        ->assertHasErrors(['title', 'file']);
});

test('lead import validates title is required', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test('leads.lead-import')
        ->set('title', '')
        ->call('import')
        ->assertHasErrors(['title' => 'required']);
});

test('lead import validates file is required', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test('leads.lead-import')
        ->set('title', 'Test Lead')
        ->set('file', null)
        ->call('import')
        ->assertHasErrors(['file' => 'required']);
});

test('lead import validates file type', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    $file = (new FileFactory())->create('test.txt', 100, 'text/plain');

    Livewire::test('leads.lead-import')
        ->set('title', 'Test Lead')
        ->set('file', $file)
        ->call('import')
        ->assertHasErrors(['file' => 'mimes']);
});

test('lead import accepts valid csv file', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $this->actingAs($user);

    $csvContent = "first_name,last_name,email,phone\nJohn,Doe,john@example.com,1234567890";
    $file       = (new FileFactory())->createWithContent('test.csv', $csvContent);

    Livewire::test('leads.lead-import')
        ->set('title', 'Test Lead')
        ->set('file', $file)
        ->call('import')
        ->assertHasNoErrors();
});

test('lead import resets form after successful import', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $this->actingAs($user);

    $csvContent = "first_name,last_name,email,phone\nJohn,Doe,john@example.com,1234567890";
    $file       = (new FileFactory())->createWithContent('test.csv', $csvContent);

    Livewire::test('leads.lead-import')
        ->set('title', 'Test Lead')
        ->set('file', $file)
        ->call('import')
        ->assertSet('title', '')
        ->assertSet('file', null);
});
