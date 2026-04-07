<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ✅ Create/Update the default ADMIN account
        User::updateOrCreate(
            ['username' => 'admin'], // unique key (avoid duplicates)
            [
                'fullname'    => 'System Administrator',
                'email'       => 'admin@anicare.local',
                'password'    => Hash::make('admin123'), // change later
                'role'        => 'admin',
                'is_approved' => true,
                'approved_at' => now(),
            ]
        );
    }
}