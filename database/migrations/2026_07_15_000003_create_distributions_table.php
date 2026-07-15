<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('distributions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('beneficiary_name');
            $table->string('beneficiary_email')->nullable();
            $table->string('barangay')->nullable();
            $table->decimal('rice_qty', 10, 2);
            $table->timestamp('scheduled_at')->nullable();
            $table->string('status')->default('pending');
            $table->unsignedBigInteger('processed_by')->nullable()->index();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('processed_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('distributions');
    }
};
