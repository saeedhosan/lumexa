<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\UserStatus;
use App\Enums\UserType;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class UserSeeder extends Seeder
{
    public function run(): void
    {

        User::query()->create([
            'name'              => 'Super admin',
            'email'             => 'super@example.com',
            'password'          => 'demo1234',
            'email_verified_at' => now(),
            'status'            => UserStatus::active,
            'type'              => UserType::super,
        ]);

        $admin = User::query()->create([
            'name'              => config('demo.admin.name', 'Admin user'),
            'email'             => config('demo.admin.email', 'admin@example.com'),
            'password'          => config('demo.admin.password', 'demo1234'),
            'email_verified_at' => now(),
            'status'            => UserStatus::active,
            'type'              => UserType::admin,
        ]);

        $user = User::query()->create([
            'name'              => config('demo.user.name', 'User'),
            'email'             => config('demo.user.email', 'user@example.com'),
            'password'          => config('demo.user.password', 'demo1234'),
            'email_verified_at' => now(),
            'status'            => UserStatus::active,
            'type'              => UserType::user,
        ]);

        $companies = Company::query()->inRandomOrder()->take(3)->pluck('id');

        $admin->companies()->sync($companies);
        $user->companies()->sync($companies);

        $user->update(['current_company_id' => Arr::first($companies)]);
        $admin->update(['current_company_id' => Arr::first($companies)]);

    }
}
