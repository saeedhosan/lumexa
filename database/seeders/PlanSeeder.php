<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\Product;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $breachMonitoring = Product::where('code', 'breach_monitoring')->first();
        $leadManagement   = Product::where('code', 'lead_management')->first();

        $plans = [
            [
                'name'                    => 'Starter',
                'slug'                    => 'starter',
                'description'             => 'Perfect for individuals and small teams getting started with breach monitoring.',
                'is_active'               => true,
                'is_default'              => true,
                'trial_days'              => 14,
                'max_users'               => 1,
                'price_monthly'           => 9.00,
                'price_yearly'            => 90.00,
                'currency'                => 'USD',
                'stripe_price_id_monthly' => null,
                'stripe_price_id_yearly'  => null,
                'features'                => [
                    'email_monitoring'    => true,
                    'real_time_alerts'    => true,
                    'breach_history'      => true,
                    'api_access'          => false,
                    'lead_management'     => false,
                    'pipeline_management' => false,
                ],
                'settings' => [
                    'scan_interval'         => 'daily',
                    'notification_channels' => ['email'],
                ],
                'products' => [$breachMonitoring],
            ],
            [
                'name'                    => 'Professional',
                'slug'                    => 'professional',
                'description'             => 'For growing businesses that need breach monitoring plus lead management.',
                'is_active'               => true,
                'is_default'              => false,
                'trial_days'              => 14,
                'max_users'               => 5,
                'price_monthly'           => 29.00,
                'price_yearly'            => 290.00,
                'currency'                => 'USD',
                'stripe_price_id_monthly' => null,
                'stripe_price_id_yearly'  => null,
                'features'                => [
                    'email_monitoring'    => true,
                    'real_time_alerts'    => true,
                    'breach_history'      => true,
                    'api_access'          => false,
                    'lead_management'     => true,
                    'pipeline_management' => true,
                    'lead_capture'        => true,
                    'automations'         => true,
                    'analytics'           => true,
                ],
                'settings' => [
                    'scan_interval'         => 'daily',
                    'notification_channels' => ['email', 'push'],
                    'auto_assignment'       => false,
                ],
                'products' => [$breachMonitoring, $leadManagement],
            ],
            [
                'name'                    => 'Enterprise',
                'slug'                    => 'enterprise',
                'description'             => 'Unlimited access to all features for large organizations.',
                'is_active'               => true,
                'is_default'              => false,
                'trial_days'              => 30,
                'max_users'               => 0,
                'price_monthly'           => 99.00,
                'price_yearly'            => 990.00,
                'currency'                => 'USD',
                'stripe_price_id_monthly' => null,
                'stripe_price_id_yearly'  => null,
                'features'                => [
                    'email_monitoring'    => true,
                    'real_time_alerts'    => true,
                    'breach_history'      => true,
                    'api_access'          => true,
                    'lead_management'     => true,
                    'pipeline_management' => true,
                    'lead_capture'        => true,
                    'automations'         => true,
                    'analytics'           => true,
                    'custom_integrations' => true,
                    'priority_support'    => true,
                ],
                'settings' => [
                    'scan_interval'         => 'realtime',
                    'notification_channels' => ['email', 'push', 'sms', 'webhook'],
                    'auto_assignment'       => true,
                    'sla_guarantee'         => true,
                ],
                'products' => [$breachMonitoring, $leadManagement],
            ],
        ];

        foreach ($plans as $planData) {
            $products = $planData['products'];
            unset($planData['products']);

            $plan = Plan::updateOrCreate(
                ['slug' => $planData['slug']],
                $planData
            );

            if ($products) {
                $plan->products()->sync($products);
            }
        }
    }
}
