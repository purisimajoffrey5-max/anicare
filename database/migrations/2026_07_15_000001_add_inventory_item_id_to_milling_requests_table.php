<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('milling_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('inventory_item_id')->nullable()->after('miller_id');
            $table->index('inventory_item_id');
        });
    }

    public function down(): void
    {
        Schema::table('milling_requests', function (Blueprint $table) {
            $table->dropIndex(['inventory_item_id']);
            $table->dropColumn('inventory_item_id');
        });
    }
};
