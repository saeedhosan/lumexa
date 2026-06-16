<?php

declare(strict_types=1);

namespace App\Providers;

use App\Domain\Company\CompanyService;
use App\Domain\Lead\LeadService;
use Illuminate\Support\ServiceProvider;
use Override;

class DomainServiceProvider extends ServiceProvider
{
    #[Override]
    public function register(): void
    {
        $this->app->singleton(CompanyService::class);
        $this->app->singleton(LeadService::class);
    }
}
