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
    $company = Company::factory()->create();
    $user    = User::factory()->create(['current_company_id' => $company->id]);
    $this->actingAs($user);

    $lead = Lead::factory()->create();

    expect($lead)->toBeInstanceOf(Lead::class)
        ->and($lead->title)->not->toBeEmpty()
        ->and($lead->user_id)->not->toBeNull()
        ->and($lead->company_id)->not->toBeNull();
});

it('creates a lead with specific status', function (): void {
    $company = Company::factory()->create();
    $user    = User::factory()->create(['current_company_id' => $company->id]);
    $this->actingAs($user);

    $lead = Lead::factory()->pending()->create();

    expect($lead->status)->toBe(LeadStatus::pending);
});

it('creates a lead with specific user and company', function (): void {
    $company = Company::factory()->create();
    $user    = User::factory()->create(['current_company_id' => $company->id]);
    $this->actingAs($user);

    $company2 = Company::factory()->create();

    $lead = Lead::factory()
        ->forUser($user)
        ->forCompany($company2)
        ->create();

    expect($lead->user_id)->toBe($user->id)
        ->and($lead->company_id)->toBe($company2->id);
});

it('belongs to a user', function (): void {
    $company = Company::factory()->create();
    $user    = User::factory()->create(['current_company_id' => $company->id]);
    $this->actingAs($user);

    $lead = Lead::factory()->create();

    expect($lead->user)->toBeInstanceOf(User::class);
});

it('belongs to a company', function (): void {
    $company = Company::factory()->create();
    $user    = User::factory()->create(['current_company_id' => $company->id]);
    $this->actingAs($user);

    $lead = Lead::factory()->create();

    expect($lead->company)->toBeInstanceOf(Company::class);
});

it('has many lead lists', function (): void {
    $company = Company::factory()->create();
    $user    = User::factory()->create(['current_company_id' => $company->id]);
    $this->actingAs($user);

    $lead = Lead::factory()->create();
    LeadList::factory()->count(3)->for($lead)->create();

    expect($lead->leadList)->toHaveCount(3);
});

it('can check lead status', function (): void {
    $company = Company::factory()->create();
    $user    = User::factory()->create(['current_company_id' => $company->id]);
    $this->actingAs($user);

    $lead = Lead::factory()->create(['status' => LeadStatus::approved]);

    expect($lead->status->label())->toBe('Approved')
        ->and($lead->status->color())->toBe('green');
});

it('creates leads with all status types', function (): void {
    $company = Company::factory()->create();
    $user    = User::factory()->create(['current_company_id' => $company->id]);
    $this->actingAs($user);

    foreach (LeadStatus::cases() as $status) {
        $lead = Lead::factory()->create(['status' => $status]);

        expect($lead->status)->toBe($status);
    }
});

it('scopes leads by company for authenticated user', function (): void {
    $company1 = Company::factory()->create();
    $company2 = Company::factory()->create();
    $user1    = User::factory()->create(['current_company_id' => $company1->id]);
    $user2    = User::factory()->create(['current_company_id' => $company2->id]);

    Lead::factory()->for($company1)->count(3)->create();
    Lead::factory()->for($company2)->count(2)->create();

    $this->actingAs($user1);

    $scopedLeads = Lead::query()->get();

    expect($scopedLeads)->toHaveCount(3);
});

it('returns empty collection when user has no company', function (): void {
    $user    = User::factory()->create(['current_company_id' => null]);
    $company = Company::factory()->create();

    Lead::factory()->for($company)->count(3)->create();

    $this->actingAs($user);

    $scopedLeads = Lead::query()->get();

    expect($scopedLeads)->toHaveCount(0);
});
