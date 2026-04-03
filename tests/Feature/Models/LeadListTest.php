<?php

declare(strict_types=1);

use App\Enums\LeadLestStatus;
use App\Models\Lead;
use App\Models\LeadList;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates a lead list with factory', function (): void {
    $leadList = LeadList::factory()->create();

    expect($leadList)->toBeInstanceOf(LeadList::class)
        ->and($leadList->lead_id)->not->toBeNull()
        ->and($leadList->first_name)->not->toBeEmpty()
        ->and($leadList->last_name)->not->toBeEmpty()
        ->and($leadList->email)->not->toBeEmpty()
        ->and($leadList->phone)->not->toBeEmpty();
});

it('creates a lead list with specific status', function (): void {
    $leadList = LeadList::factory()->cleaned()->create();

    expect($leadList->status)->toBe(LeadLestStatus::cleaned);
});

it('creates a lead list for a specific lead', function (): void {
    $lead = Lead::factory()->create();

    $leadList = LeadList::factory()->forLead($lead)->create();

    expect($leadList->lead_id)->toBe($lead->id);
});

it('belongs to a lead', function (): void {
    $lead     = Lead::factory()->create();
    $leadList = LeadList::factory()->for($lead)->create();

    expect($leadList->lead)->toBeInstanceOf(Lead::class)
        ->and($leadList->lead->id)->toBe($lead->id);
});

it('has unique email', function (): void {
    $lead = Lead::factory()->create();

    $leadList1 = LeadList::factory()->for($lead)->create(['email' => 'test@example.com']);

    expect($leadList1->email)->toBe('test@example.com');
});

it('can check lead list status', function (): void {
    $leadList = LeadList::factory()->create(['status' => LeadLestStatus::blocked]);

    expect($leadList->status->label())->toBe('Blocked')
        ->and($leadList->status->color())->toBe('red');
});

it('can identify final status', function (): void {
    $leadList = LeadList::factory()->dnc()->create();

    expect($leadList->status->isFinal())->toBeTrue();
});

it('can identify non-final status', function (): void {
    $leadList = LeadList::factory()->pending()->create();

    expect($leadList->status->isFinal())->toBeFalse();
});

it('creates lead lists with all status types', function (): void {
    foreach (LeadLestStatus::cases() as $status) {
        $leadList = LeadList::factory()->create(['status' => $status]);

        expect($leadList->status)->toBe($status);
    }
});
