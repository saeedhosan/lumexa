<?php

declare(strict_types=1);

use App\Models\User;
use Livewire\Livewire;

it('can access api tokens page', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->withSession(['auth.password_confirmed_at' => time()])
        ->get('/settings/api-tokens')
        ->assertOk();
});

it('can generate api token', function (): void {
    $user      = User::factory()->create();
    $expiresAt = now()->addMonth()->format('Y-m-d');

    Livewire::actingAs($user)
        ->test('pages::settings.api-tokens')
        ->set('tokenName', 'Test Token')
        ->set('expiresAt', $expiresAt)
        ->call('generateToken')
        ->assertHasNoErrors();

    expect($user->tokens()->count())->toBe(1);
    expect($user->tokens()->first()->name)->toBe('Test Token');
    expect($user->tokens()->first()->expires_at->format('Y-m-d'))->toBe($expiresAt);
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
    $user->createToken('My App Token', ['*'], now()->addDays(30));
    $user->createToken('Mobile Token', ['*'], now()->addDays(60));

    Livewire::actingAs($user)
        ->test('pages::settings.api-tokens')
        ->assertSee('My App Token')
        ->assertSee('Mobile Token')
        ->assertSee('Expires At')
        ->assertSee(now()->addDays(30)->format('M j, Y'))
        ->assertSee(now()->addDays(60)->format('M j, Y'));
});
