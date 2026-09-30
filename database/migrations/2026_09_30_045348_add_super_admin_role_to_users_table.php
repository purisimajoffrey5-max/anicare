```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE users
            MODIFY COLUMN role
            ENUM('admin', 'super_admin', 'farmer', 'miller', 'resident')
            NOT NULL DEFAULT 'resident'
        ");
    }

    public function down(): void
    {
        // Convert any Super Admin accounts back to Admin
        // before removing the super_admin enum value.
        DB::table('users')
            ->where('role', 'super_admin')
            ->update([
                'role' => 'admin'
            ]);

        DB::statement("
            ALTER TABLE users
            MODIFY COLUMN role
            ENUM('admin', 'farmer', 'miller', 'resident')
            NOT NULL DEFAULT 'resident'
        ");
    }
};