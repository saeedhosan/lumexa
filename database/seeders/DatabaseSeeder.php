<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Invite;
use App\Models\Lead;
use App\Models\LeadList;
use App\Models\Log;
use App\Models\Plan;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ServiceSeeder::class,
            PlanSeeder::class,
        ]);

        $services = Service::query()->orderBy('id')->get();
        $plans    = Plan::query()->orderBy('id')->get()->values();

        $superAdmin   = $this->createSuperAdmin();
        $companies    = $this->seedCompanies($plans, $services, $superAdmin);
        $firstCompany = $companies->first();

        if ($firstCompany instanceof Company) {
            $superAdmin->forceFill([
                'current_company_id' => $firstCompany->id,
            ])->save();

            $superAdmin->companies()->syncWithoutDetaching([
                $firstCompany->id => ['role' => Company::ROLE_ADMIN],
            ]);
        }

        $admin   = $this->createAdmin($firstCompany);
        $members = $this->seedCompanyMembers($companies);

        $this->seedInvites($companies, $admin);
        $this->seedLeads($companies, $members);
        $this->seedLogs(collect([$superAdmin, $admin])->merge($members)->values());
    }

    private function createSuperAdmin(): User
    {
        return User::factory()
            ->super()
            ->active()
            ->create([
                'name'               => 'Super Admin',
                'email'              => 'super@example.com',
                'current_company_id' => null,
                'onboarded_at'       => now(),
            ]);
    }

    private function createAdmin(?Company $company): User
    {
        $company ??= Company::factory()->create();

        return User::factory()
            ->admin()
            ->active()
            ->withCompany($company, Company::ROLE_ADMIN)
            ->create([
                'name'               => config('demo.admin.name', 'Admin user'),
                'email'              => config('demo.admin.email', 'admin@example.com'),
                'current_company_id' => $company->id,
                'onboarded_at'       => now(),
            ]);
    }

    /**
     * @param  Collection<int, Plan>  $plans
     * @param  Collection<int, Service>  $services
     * @return Collection<int, Company>
     */
    private function seedCompanies(Collection $plans, Collection $services, User $creator): Collection
    {
        $companyDefinitions = [
            [
                'name'        => 'Northstar Analytics',
                'slug'        => 'northstar-analytics',
                'title'       => 'Revenue operations for growing teams',
                'description' => 'Monitors campaigns, routes leads, and keeps pipeline activity visible in one workspace.',
                'country'     => 'US',
            ],
            [
                'name'        => 'Blue Harbor CRM',
                'slug'        => 'blue-harbor-crm',
                'title'       => 'Customer operations with less friction',
                'description' => 'Tracks company access, onboarding status, and account health for distributed teams.',
                'country'     => 'US',
            ],
            [
                'name'        => 'Vertex Growth Studio',
                'slug'        => 'vertex-growth-studio',
                'title'       => 'Lead management for delivery-focused agencies',
                'description' => 'Keeps outreach clean with segmented leads, reusable services, and audit-ready activity.',
                'country'     => 'GB',
            ],
            [
                'name'        => 'Apex Market Systems',
                'slug'        => 'apex-market-systems',
                'title'       => 'Multi-tenant workspace controls for operations teams',
                'description' => 'Combines service provisioning, tenant membership, and reporting in a single admin surface.',
                'country'     => 'CA',
            ],
        ];

        return collect($companyDefinitions)->map(function (array $definition, int $index) use ($plans, $services, $creator): Company {
            $plan = $plans->get($index % $plans->count()) ?? $plans->first();

            $company = Company::factory()
                ->state($definition)
                ->create([
                    'plan_id'    => $plan?->id,
                    'created_by' => $creator->id,
                    'updated_by' => $creator->id,
                ]);

            $company->services()->sync($services->pluck('id'));

            return $company;
        });
    }

    private function seedCompanyMembers(Collection $companies): Collection
    {
        return $companies->flatMap(function (Company $company, int $index): Collection {
            if ($index === 0) {
                return collect([
                    User::factory()
                        ->customer()
                        ->active()
                        ->withCompany($company, Company::ROLE_USER)
                        ->create([
                            'name'               => config('demo.user.name', 'User'),
                            'email'              => config('demo.user.email', 'user@example.com'),
                            'current_company_id' => $company->id,
                            'onboarded_at'       => now(),
                        ]),
                    User::factory()
                        ->customer()
                        ->active()
                        ->withCompany($company, Company::ROLE_USER)
                        ->create([
                            'current_company_id' => $company->id,
                            'onboarded_at'       => now(),
                        ]),
                ]);
            }

            return User::factory()
                ->count(2)
                ->customer()
                ->active()
                ->withCompany($company, Company::ROLE_USER)
                ->create([
                    'current_company_id' => $company->id,
                    'onboarded_at'       => now(),
                ]);
        })->values();
    }

    private function seedInvites(Collection $companies, User $inviter): void
    {
        $inviteData = [
            ['email' => 'ops@northstar-analytics.com', 'role' => Company::ROLE_ADMIN],
            ['email' => 'finance@blueharborcrm.com', 'role' => Company::ROLE_USER],
            ['email' => 'sales@vertexgrowthstudio.com', 'role' => Company::ROLE_USER],
            ['email' => 'admin@apexmarketsystems.com', 'role' => Company::ROLE_ADMIN],
        ];

        foreach ($companies->values() as $index => $company) {
            Invite::factory()
                ->count(1)
                ->create([
                    'company_id' => $company->id,
                    'invited_by' => $inviter->id,
                    'email'      => $inviteData[$index]['email'],
                    'role'       => $inviteData[$index]['role'],
                ]);
        }
    }

    private function seedLeads(Collection $companies, Collection $members): void
    {
        $leadDefinitions = [
            ['title' => 'Website demo requests', 'factory' => fn () => Lead::factory()->pending()],
            ['title' => 'Enterprise renewal pipeline', 'factory' => fn () => Lead::factory()->approved()],
            ['title' => 'Inbound qualification review', 'factory' => fn () => Lead::factory()->rejected()],
            ['title' => 'Data cleanup follow-up', 'factory' => fn () => Lead::factory()->blocked()],
        ];

        foreach ($companies as $company) {
            $companyMembers = $members
                ->where('current_company_id', $company->id)
                ->values();

            foreach ($leadDefinitions as $definition) {
                $lead = ($definition['factory'])()
                    ->forUser($companyMembers->random())
                    ->forCompany($company)
                    ->create([
                        'title' => $definition['title'].' - '.$company->name,
                    ]);

                LeadList::factory()
                    ->count(4)
                    ->forLead($lead)
                    ->create();
            }
        }
    }

    private function seedLogs(Collection $users): void
    {
        $logEntries = [
            ['level' => Log::SUCCESS, 'message' => 'Dashboard statistics refreshed', 'context' => ['source' => 'seed']],
            ['level' => Log::INFO, 'message' => 'New company workspace provisioned', 'context' => ['source' => 'seed']],
            ['level' => Log::INFO, 'message' => 'Lead import completed successfully', 'context' => ['source' => 'seed']],
            ['level' => Log::WARNING, 'message' => 'One invite is still pending acceptance', 'context' => ['source' => 'seed']],
            ['level' => Log::SUCCESS, 'message' => 'API lead sync finished', 'context' => ['source' => 'seed']],
            ['level' => Log::ERROR, 'message' => 'A stale company token was rotated', 'context' => ['source' => 'seed']],
            ['level' => Log::DEBUG, 'message' => 'Background queue processed invite notification', 'context' => ['source' => 'seed']],
            ['level' => Log::INFO, 'message' => 'Activity log stream is populated', 'context' => ['source' => 'seed']],
        ];

        foreach ($logEntries as $index => $entry) {
            Log::factory()
                ->forUser($users->get($index % $users->count()))
                ->create($entry);
        }
    }
}
