<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (
            Schema::hasTable('orders') &&
            !Schema::hasColumn('orders', 'expected_delivery_at')
        ) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dateTime('expected_delivery_at')
                    ->nullable()
                    ->after('out_for_delivery_at');
            });
        }
    }

    public function down(): void
    {
        if (
            Schema::hasTable('orders') &&
            Schema::hasColumn('orders', 'expected_delivery_at')
        ) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('expected_delivery_at');
            });
        }
    }
};
