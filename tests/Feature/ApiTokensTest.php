<?php

declare(strict_types=1);

use App\Models\User;
use Livewire\Livewire;

it('can access api tokens page', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/settings/api-tokens')
        ->assertOk();
});

it('can generate api token', function (): void {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test('pages::settings.api-tokens')
        ->set('tokenName', 'Test Token')
        ->call('generateToken')
        ->assertHasNoErrors();

    expect($user->tokens)->toHaveCount(1);
    expect($user->tokens->first()->name)->toBe('Test Token');
});

it('requires token name to generate token', function (): void {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test('pages::settings.api-tokens')
        ->set('tokenName', '')
        ->call('generateToken')
        ->assertHasErrors(['tokenName']);
});

it('can revoke a token', function (): void {
    $user  = User::factory()->create();
    $token = $user->createToken('Token to Revoke');

    Livewire::actingAs($user)
        ->test('pages::settings.api-tokens')
        ->call('revokeToken', $token->accessToken->id)
        ->assertHasNoErrors();

    expect($user->tokens()->count())->toBe(0);
});

it('can revoke all tokens', function (): void {
    $user = User::factory()->create();
    $user->createToken('Token 1');
    $user->createToken('Token 2');
    $user->createToken('Token 3');

    expect($user->tokens()->count())->toBe(3);

    Livewire::actingAs($user)
        ->test('pages::settings.api-tokens')
        ->call('revokeAllTokens')
        ->assertHasNoErrors();

    expect($user->tokens()->count())->toBe(0);
});

it('displays existing tokens', function (): void {
    $user = User::factory()->create();
    $user->createToken('My App Token');
    $user->createToken('Mobile Token');

    Livewire::actingAs($user)
        ->test('pages::settings.api-tokens')
        ->assertSee('My App Token')
        ->assertSee('Mobile Token')
        ->assertSee('Expires At');
});
