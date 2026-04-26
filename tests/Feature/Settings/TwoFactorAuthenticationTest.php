<?php

declare(strict_types=1);

use App\Models\User;
use Laravel\Fortify\Features;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;

beforeEach(function (): void {
    Features::twoFactorAuthentication([
        'confirm'         => true,
        'confirmPassword' => true,
    ]);
});

test('two factor settings page can be rendered', function (): void {
    $user = User::factory()->create();

    actingAs($user)
        ->withSession(['auth.password_confirmed_at' => time()])
        ->get(route('two-factor.show'))
        ->assertOk()
        ->assertSee('Two Factor Authentication')
        ->assertSee('Disabled');
});

test('two factor settings page requires password confirmation when enabled', function (): void {
    $user = User::factory()->create();

    $response = actingAs($user)
        ->get(route('two-factor.show'));

    $response->assertRedirect(route('password.confirm'));
});

test('two factor settings page returns forbidden response when two factor is disabled', function (): void {
    config(['fortify.features' => []]);

    $user = User::factory()->create();

    $response = actingAs($user)
        ->withSession(['auth.password_confirmed_at' => time()])
        ->get(route('two-factor.show'));

    $response->assertForbidden();
});

test('two factor authentication disabled when confirmation abandoned between requests', function (): void {
    $user = User::factory()->create();

    $user->forceFill([
        'two_factor_secret'         => encrypt('test-secret'),
        'two_factor_recovery_codes' => encrypt(json_encode(['code1', 'code2'])),
        'two_factor_confirmed_at'   => null,
    ])->save();

    actingAs($user);

    $component = Livewire::test('pages::settings.two-factor');

    $component->assertSet('twoFactorEnabled', false);

    assertDatabaseHas('users', [
        'id'                        => $user->id,
        'two_factor_secret'         => null,
        'two_factor_recovery_codes' => null,
    ]);
});
