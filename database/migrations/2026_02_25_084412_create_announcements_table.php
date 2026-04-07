<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('admin_id')->nullable();
            $table->string('title', 120);
            $table->text('message');

            // active = show on dashboards
            // archived = tapos na (goes to library)
            $table->string('status', 20)->default('active'); // active|archived
            $table->timestamp('archived_at')->nullable();

            $table->timestamps();

            $table->foreign('admin_id')->references('id')->on('users')->nullOnDelete();
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};