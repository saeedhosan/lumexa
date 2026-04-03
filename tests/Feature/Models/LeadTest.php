<?php

declare(strict_types=1);

use App\Enums\LeadStatus;
use App\Models\Company;
use App\Models\Lead;
use App\Models\LeadList;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates a lead with factory', function (): void {
    $lead = Lead::factory()->create();

    expect($lead)->toBeInstanceOf(Lead::class)
        ->and($lead->title)->not->toBeEmpty()
        ->and($lead->user_id)->not->toBeNull()
        ->and($lead->company_id)->not->toBeNull();
});

it('creates a lead with specific status', function (): void {
    $lead = Lead::factory()->pending()->create();

    expect($lead->status)->toBe(LeadStatus::pending);
});

it('creates a lead with specific user and company', function (): void {
    $user    = User::factory()->create();
    $company = Company::factory()->create();

    $lead = Lead::factory()
        ->forUser($user)
        ->forCompany($company)
        ->create();

    expect($lead->user_id)->toBe($user->id)
        ->and($lead->company_id)->toBe($company->id);
});

it('belongs to a user', function (): void {
    $lead = Lead::factory()->create();

    expect($lead->user)->toBeInstanceOf(User::class);
});

it('belongs to a company', function (): void {
    $lead = Lead::factory()->create();

    expect($lead->company)->toBeInstanceOf(Company::class);
});

it('has many lead lists', function (): void {
    $lead = Lead::factory()->create();
    LeadList::factory()->count(3)->for($lead)->create();

    expect($lead->leadList)->toHaveCount(3);
});

it('can check lead status', function (): void {
    $lead = Lead::factory()->create(['status' => LeadStatus::approved]);

    expect($lead->status->label())->toBe('Approved')
        ->and($lead->status->color())->toBe('green');
});

it('creates leads with all status types', function (): void {
    foreach (LeadStatus::cases() as $status) {
        $lead = Lead::factory()->create(['status' => $status]);

        expect($lead->status)->toBe($status);
    }
});
