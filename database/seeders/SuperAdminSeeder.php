<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            [
                'username' => 'superadmin',
            ],
            [
                'fullname' => 'ANI-CARE Super Administrator',
                'email' => 'superadmin@anicare.local',
                'password' => Hash::make('SuperAdmin123!'),
                'role' => 'super_admin',
                'is_approved' => true,
                'approved_at' => now(),
            ]
        );

        $this->command->info('Super Admin account created successfully.');
        $this->command->info('Username: superadmin');
        $this->command->info('Password: SuperAdmin123!');
    }
}