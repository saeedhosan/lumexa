<?php

declare(strict_types=1);

use App\Models\Lead;

it('api leads index returns success', function (): void {
    $response = $this->getJson('/api/v1/leads');

    expect($response->status())->toBe(200);
});

it('api leads index returns json structure', function (): void {
    Lead::factory()->count(3)->create();

    $response = $this->getJson('/api/v1/leads');

    $response->assertJsonStructure([
        'data',
        'meta',
    ]);
});
