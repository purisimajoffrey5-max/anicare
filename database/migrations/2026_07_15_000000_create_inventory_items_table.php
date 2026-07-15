<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('milling_request_id')->nullable();
            $table->string('name');
            $table->string('product_type', 50); // rice or palay
            $table->decimal('kilos_available', 10, 2)->default(0);
            $table->decimal('price_per_kg', 12, 2)->default(0);
            $table->string('status', 50)->default('available');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('product_type');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
};
