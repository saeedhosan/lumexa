<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'name'       => 'Campaign Monitoring',
                'slug'       => 'campaign-monitoring',
                'code'       => 'campaign_monitoring',
                'icon'       => 'shield-check',
                'about'      => 'Monitor emails for data breaches and receive alerts when your information is found in known data leaks.',
                'is_active'  => false,
                'is_default' => true,
                'version'    => '0.0',
                'features'   => [
                    'email_monitoring' => true,
                    'real_time_alerts' => true,
                    'campaign_history' => true,
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

        foreach ($services as $serviceData) {
            Service::query()->updateOrCreate(['code' => $serviceData['code']], $serviceData);
        }
    }
}
