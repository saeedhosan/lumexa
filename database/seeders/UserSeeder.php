<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\UserStatus;
use App\Enums\UserType;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $google    = Company::where('slug', 'google')->first();
        $amazon    = Company::where('slug', 'amazon')->first();
        $microsoft = Company::where('slug', 'microsoft')->first();

        $admin = User::query()->create([
            'name'              => config('demo.admin.name', 'Admin user'),
            'email'             => config('demo.admin.email', 'admin@example.com'),
            'password'          => config('demo.admin.password', '1234'),
            'email_verified_at' => now(),
            'status'            => UserStatus::active,
            'type'              => UserType::admin,
        ]);

        $user = User::query()->create([
            'name'              => config('demo.user.name', 'User'),
            'email'             => config('demo.user.email', 'user@example.com'),
            'password'          => config('demo.user.password', '1234'),
            'email_verified_at' => now(),
            'status'            => UserStatus::active,
            'type'              => UserType::customer,
        ]);

        $admin->companies()->sync($google);
        $user->companies()->sync([$google->id, $amazon->id, $microsoft->id]);

    }
}
