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
                'name'        => 'NVIDIA',
                'slug'        => 'nvidia',
                'logo'        => 'https://www.nvidia.com/favicon.ico',
                'description' => 'Leading in AI chips and hardware.',
                'country'     => 'USA',
                'is_active'   => true,
            ],
            [
                'name'        => 'Microsoft',
                'slug'        => 'microsoft',
                'logo'        => 'https://www.microsoft.com/favicon.ico',
                'description' => 'Software, cloud computing (Azure), and AI integration.',
                'country'     => 'USA',
                'is_active'   => true,
            ],
            [
                'name'        => 'Apple',
                'slug'        => 'apple',
                'logo'        => 'https://www.apple.com/favicon.ico',
                'description' => 'Consumer electronics, software, and services.',
                'country'     => 'USA',
                'is_active'   => true,
            ],
            [
                'name'        => 'Google',
                'slug'        => 'google',
                'logo'        => 'https://www.google.com/favicon.ico',
                'description' => 'Search, advertising, AI, and cloud.',
                'country'     => 'USA',
                'is_active'   => true,
            ],
            [
                'name'        => 'Amazon',
                'slug'        => 'amazon',
                'logo'        => 'https://www.amazon.com/favicon.ico',
                'description' => 'E-commerce and cloud computing (AWS).',
                'country'     => 'USA',
                'is_active'   => true,
            ],
            [
                'name'        => 'Meta Platforms',
                'slug'        => 'meta-platforms',
                'logo'        => 'https://www.facebook.com/favicon.ico',
                'description' => 'Social media and metaverse technology.',
                'country'     => 'USA',
                'is_active'   => true,
            ],
            [
                'name'        => 'Taiwan Semiconductor Manufacturing Co. (TSMC)',
                'slug'        => 'tsmc',
                'logo'        => 'https://www.tsmc.com/favicon.ico',
                'description' => 'Largest semiconductor foundry.',
                'country'     => 'Taiwan',
                'is_active'   => false,
            ],
            [
                'name'        => 'Broadcom',
                'slug'        => 'broadcom',
                'logo'        => 'https://www.broadcom.com/favicon.ico',
                'description' => 'Semiconductor and software solutions.',
                'country'     => 'USA',
                'is_active'   => true,
            ],
            [
                'name'        => 'Tencent Holdings',
                'slug'        => 'tencent',
                'logo'        => 'https://www.tencent.com/favicon.ico',
                'description' => 'Gaming, internet services, and social media.',
                'country'     => 'China',
                'is_active'   => false,
            ],
            [
                'name'        => 'Samsung Group',
                'slug'        => 'samsung',
                'logo'        => 'https://www.samsung.com/etc.clientlibs/samsung/clientlibs/consumer/global/clientlib-common/resources/images/Favicon.png',
                'description' => 'Electronics, semiconductors, and displays.',
                'country'     => 'South Korea',
                'is_active'   => true,
            ],
        ];
        foreach ($companies as $companyData) {
            Company::query()->updateOrCreate(['slug' => $companyData['slug']], $companyData);
        }
    }
}
