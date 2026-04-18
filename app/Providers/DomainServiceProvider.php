<?php

declare(strict_types=1);

namespace App\Providers;

use App\Domain\Company\CompanyService;
use App\Domain\Lead\LeadService;
use Illuminate\Support\ServiceProvider;

class DomainServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CompanyService::class);
        $this->app->singleton(LeadService::class);
    }
}
