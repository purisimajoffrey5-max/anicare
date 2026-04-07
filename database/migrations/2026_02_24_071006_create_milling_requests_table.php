<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('milling_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');   // farmer
            $table->unsignedBigInteger('miller_id')->nullable(); // later assign
            $table->decimal('kilos', 10, 2);
            $table->string('status', 30)->default('pending'); // pending/approved/rejected/completed
            $table->timestamp('scheduled_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('milling_requests');
    }
};