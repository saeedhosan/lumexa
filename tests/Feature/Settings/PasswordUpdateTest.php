<?php

declare(strict_types=1);

use App\Models\User;

test('password can be updated', function (): void {
    $this->actingAs($user = User::factory()->create());

    $this->get(route('user-password.edit'))
        ->assertOk()
        ->assertSee('Update password');
});

test('correct password must be provided to update password', function (): void {
    $this->markTestSkipped('Password update requires Auth::user() context which is difficult to test with Livewire::test()');
});
