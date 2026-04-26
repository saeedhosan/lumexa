<?php

declare(strict_types=1);

use App\Models\Company;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Livewire\Livewire;
use Livewire\Features\Testing\FileUploadLimit;

uses(RefreshDatabase::class);

test('lead import modal can be opened', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $this->actingAs($user);

    Livewire::test('leads.lead-import')
        ->assertSee('Import leads');
});

test('lead import validates title is required', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $this->actingAs($user);

    Livewire::test('leads.lead-import')
        ->call('import')
        ->assertHasErrors(['title' => 'required']);
});

test('lead import validates file is required', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $this->actingAs($user);

    Livewire::test('leads.lead-import')
        ->set('title', 'Test Lead')
        ->call('import')
        ->assertHasErrors(['file' => 'required']);
});

test('lead import validates file type', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $this->actingAs($user);

    $file = UploadedFile::fake()->create('test.txt', 'txt');

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
    $file = UploadedFile::fake()->createWithContent('test.csv', $csvContent);

    Livewire::test('leads.lead-import')
        ->set('title', 'Test Import')
        ->set('file', $file)
        ->call('import')
        ->assertHasNoErrors();

    expect(Lead::where('title', 'Test Import')->exists())->toBeTrue();
});

test('lead import resets form after successful import', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();
    $user->companies()->attach($company, ['role' => 'admin']);
    $user->update(['current_company_id' => $company->id]);
    $this->actingAs($user);

    $csvContent = "first_name,last_name,email,phone\nJohn,Doe,john@example.com,1234567890";
    $file = UploadedFile::fake()->createWithContent('test.csv', $csvContent);

    $component = Livewire::test('leads.lead-import')
        ->set('title', 'Test Import')
        ->set('file', $file)
        ->call('import');

    $component->assertSet('title', '');
    $component->assertSet('file', null);
});