<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('vat_settings')) {
            Schema::create('vat_settings', function (Blueprint $table) {
                $table->id();
                $table->boolean('enabled')->default(false);
                $table->decimal('rate', 8, 4)->default(12.0000);
                $table->timestamps();
            });
        }

        if (Schema::hasTable('vat_settings') &&
            \Illuminate\Support\Facades\DB::table('vat_settings')->count() === 0) {
            \Illuminate\Support\Facades\DB::table('vat_settings')->insert([
                'enabled' => false,
                'rate' => 12.0000,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('vat_settings');
    }
};
