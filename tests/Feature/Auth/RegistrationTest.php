<?php

declare(strict_types=1);

use App\Models\User;

test('registration screen can be rendered', function (): void {
    $response = $this->get(route('register'));

    $response->assertOk();
});

test('new users can register', function (): void {
    $response = $this->post(route('register.store'), [
        'name'                  => 'John Doe',
        'email'                 => 'test@example.com',
        'password'              => 'Password1',
        'password_confirmation' => 'Password1',
    ]);

    $response->assertSessionHasNoErrors()
        ->assertRedirect(route('onboarding', absolute: false));

    $this->assertAuthenticated();

    $this->assertDatabaseHas('companies', ['name' => "John Doe's Company"]);

    $user = User::query()->where('email', 'test@example.com')->first();

    expect($user->current_company_id)->not->toBeNull();
});

test('user cannot register with invalid data', function (): void {
    $response = $this->post(route('register.store'), [
        'name'                  => '',
        'email'                 => 'invalid-email',
        'password'              => 'short',
        'password_confirmation' => 'different',
    ]);

    $response->assertSessionHasErrors(['name', 'email', 'password']);

    $this->assertGuest();
});

test('registered user can access dashboard', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->get(route('dashboard'));

    $response->assertOk();
});
