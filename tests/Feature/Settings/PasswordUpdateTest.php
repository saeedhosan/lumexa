<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('password can be updated', function (): void {
    $this->actingAs($user = User::factory()->create());

    $this->get(route('user-password.edit'))
        ->assertOk()
        ->assertSee('Update password');
});

test('correct password must be provided to update password', function (): void {
    $user = User::factory()->create();

    Livewire::test('pages::settings.password')
        ->set('current_password', 'wrong-password')
        ->set('password', 'new-password')
        ->set('password_confirmation', 'new-password')
        ->call('updatePassword')
        ->assertHasErrors(['current_password']);

    Livewire::actingAs($user)
        ->test('pages::settings.password')
        ->set('current_password', 'password')
        ->set('password', 'new-password')
        ->set('password_confirmation', 'new-password')
        ->call('updatePassword')
        ->assertHasNoErrors();

    expect(Hash::check('password', $user->refresh()->password))->toBeFalse();
});
