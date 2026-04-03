<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Lead;
use App\Models\LeadList;
use Illuminate\Database\Seeder;

class LeadListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Lead::query()->each(function ($lead): void {
            LeadList::factory()
                ->count(5)
                ->for($lead)
                ->create();
        });

        LeadList::factory()
            ->count(10)
            ->pending()
            ->create();

        LeadList::factory()
            ->count(5)
            ->cleaned()
            ->create();

        LeadList::factory()
            ->count(3)
            ->invalid()
            ->create();

        LeadList::factory()
            ->count(2)
            ->duplicate()
            ->create();

        LeadList::factory()
            ->count(2)
            ->spam()
            ->create();

        LeadList::factory()
            ->count(1)
            ->dnc()
            ->create();

        LeadList::factory()
            ->count(1)
            ->archived()
            ->create();
    }
}
