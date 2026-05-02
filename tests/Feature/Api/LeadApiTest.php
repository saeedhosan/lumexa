<?php

declare(strict_types=1);

use App\Models\Lead;
use App\Models\User;

it('api leads index returns success', function (): void {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withToken($token)->getJson('/api/v1/leads');

    expect($response->status())->toBe(200);
});

it('api leads index returns json structure', function (): void {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;
    Lead::factory()->count(3)->create();

    $response = $this->withToken($token)->getJson('/api/v1/leads');

    $response->assertJsonStructure([
        'data',
        'meta',
    ]);
});

it('api leads index requires authentication', function (): void {
    $response = $this->getJson('/api/v1/leads');

    expect($response->status())->toBe(401);
});
