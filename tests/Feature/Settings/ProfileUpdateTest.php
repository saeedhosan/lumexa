<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('profile page is displayed', function (): void {
    $this->actingAs($user = User::factory()->create());

    $this->get(route('profile.edit'))->assertOk();
});

test('profile information can be updated', function (): void {
    $this->actingAs($user = User::factory()->create());

    $this->get(route('profile.edit'))
        ->assertOk()
        ->assertSee('Profile')
        ->assertSee('Save');
});

test('email verification status is unchanged when email address is unchanged', function (): void {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $this->actingAs($user);

    Livewire::test('pages::settings.profile')
        ->set('name', 'Test User')
        ->set('email', $user->email)
        ->call('updateProfileInformation');

    expect($user->refresh()->email_verified_at)->not->toBeNull();
});

test('correct password must be provided to delete account', function (): void {
    $user = User::factory()->create(['password' => bcrypt('password123')]);

    $this->actingAs($user);

    Livewire::test('pages::settings.delete-user-form')
        ->set('password', 'wrong-password')
        ->call('deleteUser')
        ->assertHasErrors();

    expect(User::query()->find($user->id))->not->toBeNull();
});

test('user can delete account with correct password', function (): void {
    $user = User::factory()->create(['password' => bcrypt('password123')]);

    $this->actingAs($user);

    Livewire::test('pages::settings.delete-user-form')
        ->set('password', 'password123')
        ->call('deleteUser')
        ->assertRedirect('/');

    expect(User::query()->find($user->id))->toBeNull();
});
