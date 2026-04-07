<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'fullname'     => 'System Administrator',
                'email'        => 'admin@anicare.local',
                'password'     => Hash::make('admin123'),
                'role'         => 'admin',
                'is_approved'  => true,
                'approved_at'  => now(),
            ]
        );
    }
}