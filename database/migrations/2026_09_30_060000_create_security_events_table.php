<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('security_events', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id')->nullable();

            $table->string('event_type');
            $table->string('risk_level')->default('low');

            $table->string('ip_address', 45)->nullable();
            $table->string('method', 10)->nullable();
            $table->string('route')->nullable();
            $table->string('user_agent', 1000)->nullable();

            $table->text('description')->nullable();

            $table->timestamps();

            $table->index('event_type');
            $table->index('risk_level');
            $table->index('ip_address');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('security_events');
    }
};