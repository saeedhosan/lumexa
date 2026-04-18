<?php

declare(strict_types=1);

namespace App\Providers;

use App\Domain\Company\CompanyService;
use App\Domain\Lead\LeadService;
use App\Models\Company;
use App\Models\Lead;
use App\Models\Service;
use App\Policies\CompanyPolicy;
use App\Policies\LeadPolicy;
use App\Policies\ServicePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class DomainServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CompanyService::class);
        $this->app->singleton(LeadService::class);
    }

    public function boot(): void
    {
        Gate::policy(Company::class, CompanyPolicy::class);
        Gate::policy(Lead::class, LeadPolicy::class);
        Gate::policy(Service::class, ServicePolicy::class);
    }
}
