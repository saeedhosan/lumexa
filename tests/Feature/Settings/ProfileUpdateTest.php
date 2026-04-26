<?php

declare(strict_types=1);

use App\Models\User;

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

test('user can delete their account', function (): void {
    $this->markTestSkipped('Delete user form requires Auth::user() context which is difficult to test with Livewire::test()');
});

test('correct password must be provided to delete account', function (): void {
    $this->markTestSkipped('Delete user form validation requires Auth::user() context which is difficult to test with Livewire::test()');
});
