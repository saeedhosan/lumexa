<?php

declare(strict_types=1);

use App\Enums\LeadLestStatus;
use App\Models\Company;
use App\Models\Lead;
use App\Models\LeadList;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates a lead list with factory', function (): void {
    $company = Company::factory()->create();
    $user    = User::factory()->create(['current_company_id' => $company->id]);
    $this->actingAs($user);

    $leadList = LeadList::factory()->create();

    expect($leadList)->toBeInstanceOf(LeadList::class)
        ->and($leadList->lead_id)->not->toBeNull()
        ->and($leadList->first_name)->not->toBeEmpty()
        ->and($leadList->last_name)->not->toBeEmpty()
        ->and($leadList->email)->not->toBeEmpty()
        ->and($leadList->phone)->not->toBeEmpty();
});

it('creates a lead list with specific status', function (): void {
    $company = Company::factory()->create();
    $user    = User::factory()->create(['current_company_id' => $company->id]);
    $this->actingAs($user);

    $leadList = LeadList::factory()->cleaned()->create();

    expect($leadList->status)->toBe(LeadLestStatus::cleaned);
});

it('creates a lead list for a specific lead', function (): void {
    $company = Company::factory()->create();
    $user    = User::factory()->create(['current_company_id' => $company->id]);
    $this->actingAs($user);

    $lead = Lead::factory()->create();

    $leadList = LeadList::factory()->forLead($lead)->create();

    expect($leadList->lead_id)->toBe($lead->id);
});

it('belongs to a lead', function (): void {
    $company = Company::factory()->create();
    $user    = User::factory()->create(['current_company_id' => $company->id]);
    $this->actingAs($user);

    $lead     = Lead::factory()->for($user)->forCompany($company)->create();
    $leadList = LeadList::factory()->for($lead)->create();

    expect($leadList->lead)->toBeInstanceOf(Lead::class)
        ->and($leadList->lead->id)->toBe($lead->id);
});

it('has valid contact information', function (): void {
    $company = Company::factory()->create();
    $user    = User::factory()->create(['current_company_id' => $company->id]);
    $this->actingAs($user);

    $lead     = Lead::factory()->create();
    $leadList = LeadList::factory()->for($lead)->create([
        'email' => 'test@example.com',
        'phone' => '+1234567890',
    ]);

    expect($leadList->email)->toBe('test@example.com')
        ->and($leadList->phone)->toBe('+1234567890');
});

it('can check lead list status', function (): void {
    $company = Company::factory()->create();
    $user    = User::factory()->create(['current_company_id' => $company->id]);
    $this->actingAs($user);

    $leadList = LeadList::factory()->create(['status' => LeadLestStatus::blocked]);

    expect($leadList->status->label())->toBe('Blocked')
        ->and($leadList->status->color())->toBe('red');
});

it('can identify final status', function (): void {
    $company = Company::factory()->create();
    $user    = User::factory()->create(['current_company_id' => $company->id]);
    $this->actingAs($user);

    $leadList = LeadList::factory()->dnc()->create();

    expect($leadList->status->isFinal())->toBeTrue();
});

it('can identify non-final status', function (): void {
    $company = Company::factory()->create();
    $user    = User::factory()->create(['current_company_id' => $company->id]);
    $this->actingAs($user);

    $leadList = LeadList::factory()->pending()->create();

    expect($leadList->status->isFinal())->toBeFalse();
});

it('creates lead lists with all status types', function (): void {
    $company = Company::factory()->create();
    $user    = User::factory()->create(['current_company_id' => $company->id]);
    $this->actingAs($user);

    foreach (LeadLestStatus::cases() as $status) {
        $leadList = LeadList::factory()->create(['status' => $status]);

        expect($leadList->status)->toBe($status);
    }
});
