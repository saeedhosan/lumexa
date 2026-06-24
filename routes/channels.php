<?php

declare(strict_types=1);

use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('company.{companyId}', fn (User $user, int $companyId): bool => $user->belongsToCompany(Company::query()->findOrFail($companyId)));
