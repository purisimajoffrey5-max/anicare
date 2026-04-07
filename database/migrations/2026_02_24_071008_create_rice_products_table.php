<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rice_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // farmer owner
            $table->string('name', 150);
            $table->enum('type', ['palay','rice'])->default('rice');
            $table->decimal('price_per_kg', 10, 2)->default(0);
            $table->decimal('kilos_available', 10, 2)->default(0);
            $table->string('photo_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rice_products');
    }
};