<?php

declare(strict_types=1);

namespace Database\Seeders;

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

        if ($user && $user->currentCompany) {
            Lead::factory()
                ->count(5)
                ->for($user)
                ->forCompany($user->currentCompany)
                ->create()->each(function ($lead) {
                    LeadList::factory()
                        ->count(5)
                        ->for($lead)
                        ->create();
                });
        }
    }
}
