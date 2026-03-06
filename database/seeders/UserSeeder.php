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
        $acme      = Company::where('slug', 'acme-corporation')->first();
        $techstart = Company::where('slug', 'techstart-inc')->first();
        $global    = Company::where('slug', 'global-systems-ltd')->first();

        $users = [
            [
                'name'              => 'John Smith',
                'email'             => 'john@acme-corporation.com',
                'password'          => Hash::make('password'),
                'email_verified_at' => now(),
                'status'            => UserStatus::active,
                'type'              => UserType::admin,
                'company'           => $acme,
                'role'              => Company::ROLE_ADMIN,
            ],
            [
                'name'              => 'Sarah Johnson',
                'email'             => 'sarah@acme-corporation.com',
                'password'          => Hash::make('password'),
                'email_verified_at' => now(),
                'status'            => UserStatus::active,
                'type'              => UserType::customer,
                'company'           => $acme,
                'role'              => Company::ROLE_CUSTOMER,
            ],
            [
                'name'              => 'Mike Chen',
                'email'             => 'mike@techstart-inc.com',
                'password'          => Hash::make('password'),
                'email_verified_at' => now(),
                'status'            => UserStatus::active,
                'type'              => UserType::admin,
                'company'           => $techstart,
                'role'              => Company::ROLE_ADMIN,
            ],
            [
                'name'              => 'Emily Davis',
                'email'             => 'emily@techstart-inc.com',
                'password'          => Hash::make('password'),
                'email_verified_at' => now(),
                'status'            => UserStatus::active,
                'type'              => UserType::customer,
                'company'           => $techstart,
                'role'              => Company::ROLE_CUSTOMER,
            ],
            [
                'name'              => 'David Wilson',
                'email'             => 'david@global-systems-ltd.com',
                'password'          => Hash::make('password'),
                'email_verified_at' => now(),
                'status'            => UserStatus::active,
                'type'              => UserType::admin,
                'company'           => $global,
                'role'              => Company::ROLE_ADMIN,
            ],
            [
                'name'              => 'Lisa Brown',
                'email'             => 'lisa@global-systems-ltd.com',
                'password'          => Hash::make('password'),
                'email_verified_at' => now(),
                'status'            => UserStatus::active,
                'type'              => UserType::customer,
                'company'           => $global,
                'role'              => Company::ROLE_CUSTOMER,
            ],
            [
                'name'              => 'Test User',
                'email'             => 'test@example.com',
                'password'          => Hash::make('password'),
                'email_verified_at' => now(),
                'status'            => UserStatus::active,
                'type'              => UserType::customer,
                'company'           => null,
                'role'              => null,
            ],
        ];

        foreach ($users as $userData) {
            $company = $userData['company'];
            $role    = $userData['role'];
            unset($userData['company'], $userData['role']);

            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );

            if ($company && $role) {
                $user->companies()->syncWithoutDetaching([
                    $company->id => ['role' => $role],
                ]);
            }
        }
    }
}
