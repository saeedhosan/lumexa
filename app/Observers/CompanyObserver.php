<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Company;
use Illuminate\Support\Str;

class CompanyObserver
{
    public function creating(Company $company): void
    {
        if (empty($company->slug)) {
            $company->slug = Str::slug($company->name ?? 'company-'.Str::random(6));
        }
    }

    public function created(Company $company): void
    {
        activity()
            ->performedOn($company)
            ->withProperties(['name' => $company->name])
            ->log('Company created');
    }
}
