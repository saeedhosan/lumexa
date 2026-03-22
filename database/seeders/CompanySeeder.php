<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        $companies = [
            [
                'name'        => 'Google',
                'slug'        => 'google',
                'description' => 'A leading technology solutions provider.',
                'language'    => 'en',
                'timezone'    => 'America/New_York',
                'currency'    => 'USD',
                'country'     => 'US',
                'is_active'   => true,
                'settings'    => [
                    'notifications' => ['email', 'push'],
                    'theme'         => 'light',
                ],
            ],
            [
                'name'        => 'Microsoft',
                'slug'        => 'microsoft',
                'description' => 'Innovative startup focused on AI solutions.',
                'language'    => 'en',
                'timezone'    => 'America/Los_Angeles',
                'currency'    => 'USD',
                'country'     => 'US',
                'is_active'   => true,
                'settings'    => [
                    'notifications' => ['email', 'sms'],
                    'theme'         => 'dark',
                ],
            ],
            [
                'name'        => 'Amazon',
                'slug'        => 'amazon',
                'description' => 'Enterprise-level managed service provider.',
                'language'    => 'en',
                'timezone'    => 'Europe/London',
                'currency'    => 'GBP',
                'country'     => 'GB',
                'is_active'   => true,
                'settings'    => [
                    'notifications' => ['email', 'push', 'webhook'],
                    'theme'         => 'light',
                ],
            ],
        ];

        foreach ($companies as $companyData) {
            Company::query()->updateOrCreate(['slug' => $companyData['slug']], $companyData);
        }
    }
}
