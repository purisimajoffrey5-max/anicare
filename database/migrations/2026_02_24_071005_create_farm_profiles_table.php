<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('farm_profiles', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->string('farm_name');
    $table->string('location');
    $table->string('contact');
    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('farm_profiles');
    }
};