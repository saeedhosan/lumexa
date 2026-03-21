<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\UserStatus;
use App\Enums\UserType;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $google    = Company::where('slug', 'google')->first();
        $amazon    = Company::where('slug', 'amazon')->first();
        $microsoft = Company::where('slug', 'microsoft')->first();

        $admin = User::query()->create([
            'name'              => 'Admin user',
            'email'             => 'admin@demo.com',
            'password'          => Hash::make('1234'),
            'email_verified_at' => now(),
            'status'            => UserStatus::active,
            'type'              => UserType::admin,
        ]);

        $admin->companies()->sync($google);

        $user = User::query()->create([
            'name'              => 'User',
            'email'             => 'user@demo.com',
            'password'          => Hash::make('1234'),
            'email_verified_at' => now(),
            'status'            => UserStatus::active,
            'type'              => UserType::customer,
        ]);

        $user->companies()->sync([$google->id, $amazon->id, $microsoft->id]);

    }
}
