<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('rsbsa_no')->nullable()->after('barangay');
            $table->boolean('is_icc_ip')->default(false)->after('rsbsa_no');
            $table->string('icc_ip_name')->nullable()->after('is_icc_ip');

            // NEW FIELD
            $table->string('membership')->nullable()->after('icc_ip_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'rsbsa_no',
                'is_icc_ip',
                'icc_ip_name',
                'membership'
            ]);
        });
    }
};