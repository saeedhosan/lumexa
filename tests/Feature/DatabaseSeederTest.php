<?php

declare(strict_types=1);

use App\Models\Company;
use App\Models\Invite;
use App\Models\Lead;
use App\Models\LeadList;
use App\Models\Log;
use App\Models\Plan;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\DB;

it('seeds a complete demo workspace', function (): void {
    $this->seed();

    expect(Service::query()->count())->toBe(2);
    expect(Plan::query()->count())->toBe(3);
    expect(Company::query()->count())->toBe(4);
    expect(User::query()->count())->toBe(10);
    expect(Invite::query()->count())->toBe(4);
    expect(Lead::query()->count())->toBe(16);
    expect(LeadList::query()->count())->toBe(64);
    expect(Log::query()->count())->toBe(8);

    expect(User::query()->where('email', 'super@example.com')->exists())->toBeTrue();
    expect(User::query()->where('email', 'admin@example.com')->exists())->toBeTrue();
    expect(User::query()->where('email', 'user@example.com')->exists())->toBeTrue();

    expect(User::query()->whereNotNull('current_company_id')->count())->toBe(10);
    expect(Company::query()->whereNotNull('plan_id')->count())->toBe(4);
    expect(DB::table('activity_log')->count())->toBeGreaterThan(0);
});
