<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Database\Seeder;

class LeadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = Company::factory()
            ->count(3)
            ->withAdmin(1)
            ->withCustomers(1)
            ->create();

        $companies->each(function (Company $company): void {
            $user = $company->admins()->first() ?? $company->users()->first();

            if (! $user instanceof User) {
                return;
            }

            Lead::factory()
                ->count(5)
                ->forUser($user)
                ->forCompany($company)
                ->create();
        });

        $company = $companies->first();
        $user    = $company?->admins()->first();

        if ($company instanceof Company && $user instanceof User) {
            Lead::factory()
                ->count(10)
                ->pending()
                ->forUser($user)
                ->forCompany($company)
                ->create();

            Lead::factory()
                ->count(5)
                ->approved()
                ->forUser($user)
                ->forCompany($company)
                ->create();

            Lead::factory()
                ->count(3)
                ->rejected()
                ->forUser($user)
                ->forCompany($company)
                ->create();

            Lead::factory()
                ->count(2)
                ->blocked()
                ->forUser($user)
                ->forCompany($company)
                ->create();
        }
    }
}
