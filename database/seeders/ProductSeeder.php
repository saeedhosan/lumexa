<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name'       => 'Breach Monitoring',
                'slug'       => 'breach-monitoring',
                'code'       => 'breach_monitoring',
                'icon'       => 'shield-check',
                'about'      => 'Monitor emails for data breaches and receive alerts when your information is found in known data leaks.',
                'is_active'  => true,
                'is_default' => true,
                'features'   => [
                    'email_monitoring' => true,
                    'real_time_alerts' => true,
                    'breach_history'   => true,
                    'api_access'       => false,
                ],
                'settings' => [
                    'scan_interval'         => 'daily',
                    'notification_channels' => ['email', 'push'],
                ],
            ],
            [
                'name'       => 'Lead Management',
                'slug'       => 'lead-management',
                'code'       => 'lead_management',
                'icon'       => 'users',
                'about'      => 'Track and manage sales leads throughout your sales pipeline with powerful automation tools.',
                'is_active'  => true,
                'is_default' => false,
                'features'   => [
                    'pipeline_management' => true,
                    'lead_capture'        => true,
                    'automations'         => true,
                    'analytics'           => true,
                    'api_access'          => true,
                ],
                'settings' => [
                    'default_pipeline_stages' => ['new', 'contacted', 'qualified', 'proposal', 'won', 'lost'],
                    'auto_assignment'         => false,
                ],
            ],
        ];

        foreach ($products as $productData) {
            Product::query()->updateOrCreate(['code' => $productData['code']], $productData);
        }
    }
}
