<?php

declare(strict_types=1);

use App\Models\Company;
use App\Models\Lead;
use App\Models\User;

it('api leads index returns success', function (): void {
    $user  = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withToken($token)->getJson('/api/v1/leads');

    expect($response->status())->toBe(200);
});

it('api leads index returns json structure', function (): void {
    $user  = User::factory()->create();
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

it('scopes api leads to the active company only', function (): void {
    $user = User::factory()->create();
    $activeCompany = Company::factory()->create();
    $otherCompany = Company::factory()->create();

    $user->companies()->attach($activeCompany, ['role' => Company::ROLE_USER]);
    $user->companies()->attach($otherCompany, ['role' => Company::ROLE_USER]);
    $user->forceFill(['current_company_id' => $activeCompany->id])->save();

    $activeLead = Lead::factory()->forUser($user)->forCompany($activeCompany)->create();
    Lead::factory()->forUser($user)->forCompany($otherCompany)->create();

    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withToken($token)->getJson('/api/v1/leads');

    $response->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', $activeLead->id);
});

it('assigns api-created leads to the active company and ignores a submitted company id', function (): void {
    $user = User::factory()->create();
    $activeCompany = Company::factory()->create();
    $otherCompany = Company::factory()->create();

    $user->companies()->attach($activeCompany, ['role' => Company::ROLE_USER]);
    $user->companies()->attach($otherCompany, ['role' => Company::ROLE_USER]);
    $user->forceFill(['current_company_id' => $activeCompany->id])->save();

    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withToken($token)->postJson('/api/v1/leads', [
        'title' => 'Tenant-safe lead',
        'company_id' => $otherCompany->id,
    ]);

    $response->assertOk();

    $this->assertDatabaseHas('leads', [
        'title' => 'Tenant-safe lead',
        'company_id' => $activeCompany->id,
        'user_id' => $user->id,
    ]);
});
