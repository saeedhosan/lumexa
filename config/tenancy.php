<?php

declare(strict_types=1);

use App\Models\Company;
use App\Tenancy\CompanyTenantResolver;

return [
    'tenant_model' => Company::class,

    'tenant_key' => 'company_id',

    'resolver' => CompanyTenantResolver::class,
];
