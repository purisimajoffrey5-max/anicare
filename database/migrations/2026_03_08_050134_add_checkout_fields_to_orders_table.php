<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'buyer_name')) {
                $table->string('buyer_name')->nullable()->after('farmer_id');
            }

            if (!Schema::hasColumn('orders', 'contact_number')) {
                $table->string('contact_number')->nullable()->after('buyer_name');
            }

            if (!Schema::hasColumn('orders', 'fulfillment_type')) {
                $table->string('fulfillment_type')->nullable()->after('status');
            }

            if (!Schema::hasColumn('orders', 'pickup_address')) {
                $table->string('pickup_address')->nullable()->after('delivery_address');
            }

            if (!Schema::hasColumn('orders', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('pickup_address');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $columns = [];

            if (Schema::hasColumn('orders', 'buyer_name')) {
                $columns[] = 'buyer_name';
            }

            if (Schema::hasColumn('orders', 'contact_number')) {
                $columns[] = 'contact_number';
            }

            if (Schema::hasColumn('orders', 'fulfillment_type')) {
                $columns[] = 'fulfillment_type';
            }

            if (Schema::hasColumn('orders', 'pickup_address')) {
                $columns[] = 'pickup_address';
            }

            if (Schema::hasColumn('orders', 'payment_method')) {
                $columns[] = 'payment_method';
            }

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};