<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Lead;
use App\Models\LeadList;
use App\Models\User;
use Illuminate\Database\Seeder;

class LeadDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::query()->where('email', config('demo.user.email', 'user@example.com'))->first();

        if (! $user instanceof User) {
            $company = Company::factory()->create();

            $user = User::factory()
                ->customer()
                ->active()
                ->withCompany($company, Company::ROLE_USER)
                ->create([
                    'name'               => config('demo.user.name', 'User'),
                    'email'              => config('demo.user.email', 'user@example.com'),
                    'current_company_id' => $company->id,
                    'onboarded_at'       => now(),
                ]);
        }

        if (! $user->currentCompany) {
            $company = $user->companies()->first() ?? Company::factory()->create();

            $user->forceFill(['current_company_id' => $company->id])->save();
        }

        if ($user->currentCompany) {
            Lead::factory()
                ->count(5)
                ->for($user)
                ->forCompany($user->currentCompany)
                ->create()->each(function ($lead): void {
                    LeadList::factory()
                        ->count(5)
                        ->for($lead)
                        ->create();
                });
        }
    }
}
