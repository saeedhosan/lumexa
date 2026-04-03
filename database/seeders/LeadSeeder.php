<?php

declare(strict_types=1);

namespace Database\Seeders;

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
        $users = User::factory()->count(3)->create();

        $users->each(function ($user): void {
            Lead::factory()
                ->count(5)
                ->for($user)
                ->for($user->company)
                ->create();
        });

        Lead::factory()
            ->count(10)
            ->pending()
            ->create();

        Lead::factory()
            ->count(5)
            ->approved()
            ->create();

        Lead::factory()
            ->count(3)
            ->rejected()
            ->create();

        Lead::factory()
            ->count(2)
            ->blocked()
            ->create();
    }
}
