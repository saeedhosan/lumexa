<?php

declare(strict_types=1);

use App\Livewire\Onboarding;
use App\Models\Company;
use App\Models\User;
use Livewire\Livewire;

test('onboarding screen requires authentication', function (): void {
    $response = $this->get(route('onboarding'));

    $response->assertRedirect(route('login'));
});

test('onboarding screen renders for unonboarded user', function (): void {
    $user = User::factory()->withCompany()->create(['onboarded_at' => null]);

    $response = $this->actingAs($user)->get(route('onboarding'));

    $response->assertOk();
});

test('onboarding redirects to dashboard if already onboarded', function (): void {
    $user = User::factory()->withCompany()->create(['onboarded_at' => now()]);

    $response = $this->actingAs($user)->get(route('onboarding'));

    $response->assertRedirect(route('dashboard'));
});

test('onboarding wizard goes through all steps', function (): void {
    $company = Company::factory()->create();
    $user    = User::factory()->create([
        'onboarded_at'       => null,
        'current_company_id' => $company->id,
    ]);
    $user->companies()->attach($company, ['role' => Company::ROLE_ADMIN]);

    Livewire::actingAs($user)
        ->test(Onboarding::class)
        ->assertSet('step', 1)
        ->call('nextStep')
        ->assertSet('step', 2)
        ->set('companyName', 'Updated Company')
        ->call('nextStep')
        ->assertSet('step', 3)
        ->set('name', 'Updated Name')
        ->call('complete')
        ->assertRedirect(route('dashboard'));

    $user->refresh();

    expect($user->onboarded_at)->not->toBeNull();
    expect($user->name)->toBe('Updated Name');
    expect($company->fresh()->name)->toBe('Updated Company');
});

test('registration creates company and sets current_company_id', function (): void {
    $this->post(route('register.store'), [
        'name'                  => 'Jane Doe',
        'email'                 => 'jane@example.com',
        'password'              => 'Password1',
        'password_confirmation' => 'Password1',
    ]);

    $user = User::query()->where('email', 'jane@example.com')->first();

    expect($user)->not->toBeNull();
    expect($user->current_company_id)->not->toBeNull();
    expect($user->onboarded_at)->toBeNull();

    $company = $user->currentCompany;

    expect($company)->not->toBeNull();
    expect($company->name)->toBe("Jane Doe's Company");
    expect($user->isAdminOf($company))->toBeTrue();
});
