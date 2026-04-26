<?php

declare(strict_types=1);

use App\Models\User;

test('lead import modal can be opened', function (): void {
    $this->actingAs($user = User::factory()->create());

    $this->get(route('app.leads.index'))
        ->assertOk()
        ->assertSee('Import');
});

test('lead import validates required fields', function (): void {
    $this->markTestSkipped('Lead import requires full Livewire component implementation');
});

test('lead import validates title is required', function (): void {
    $this->markTestSkipped('Lead import requires full Livewire component implementation');
});

test('lead import validates file is required', function (): void {
    $this->markTestSkipped('Lead import requires full Livewire component implementation');
});

test('lead import validates file type', function (): void {
    $this->markTestSkipped('Lead import requires full Livewire component implementation');
});

test('lead import accepts valid csv file', function (): void {
    $this->markTestSkipped('Lead import requires full Livewire component implementation');
});

test('lead import resets form after successful import', function (): void {
    $this->markTestSkipped('Lead import requires full Livewire component implementation');
});
