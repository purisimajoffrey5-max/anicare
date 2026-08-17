<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('milling_requests')) {
            return;
        }

        Schema::table('milling_requests', function (Blueprint $table) {

            if (!Schema::hasColumn('milling_requests', 'transport_type')) {
                $table->string('transport_type', 20)
                    ->default('delivery')
                    ->index();
            }
        });
    }

    public function down(): void
    {
        if (
            Schema::hasTable('milling_requests') &&
            Schema::hasColumn('milling_requests', 'transport_type')
        ) {
            Schema::table('milling_requests', function (Blueprint $table) {
                $table->dropColumn('transport_type');
            });
        }
    }
};