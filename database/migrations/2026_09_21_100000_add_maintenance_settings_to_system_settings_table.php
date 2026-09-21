<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('system_settings')) {
            return;
        }

        DB::table('system_settings')->updateOrInsert(
            ['key' => 'maintenance_mode'],
            [
                'value' => '0',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('system_settings')->updateOrInsert(
            ['key' => 'maintenance_message'],
            [
                'value' => 'ANI-CARE is temporarily unavailable while system maintenance is being performed. Please try again later.',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    public function down(): void
    {
        if (!Schema::hasTable('system_settings')) {
            return;
        }

        DB::table('system_settings')
            ->whereIn('key', ['maintenance_mode', 'maintenance_message'])
            ->delete();
    }
};
