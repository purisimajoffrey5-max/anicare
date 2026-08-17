<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $columns = [
            'purchase_unit' => fn (Blueprint $table) => $table->string('purchase_unit', 20)->nullable(),
            'quantity_sacks' => fn (Blueprint $table) => $table->decimal('quantity_sacks', 12, 4)->nullable(),
            'quantity_kilos' => fn (Blueprint $table) => $table->decimal('quantity_kilos', 12, 2)->nullable(),
            'total_kilos' => fn (Blueprint $table) => $table->decimal('total_kilos', 12, 2)->nullable(),
            'price_per_kg' => fn (Blueprint $table) => $table->decimal('price_per_kg', 12, 2)->nullable(),
            'price_per_sack' => fn (Blueprint $table) => $table->decimal('price_per_sack', 12, 2)->nullable(),
            'subtotal' => fn (Blueprint $table) => $table->decimal('subtotal', 14, 2)->nullable(),
            'shipping_fee' => fn (Blueprint $table) => $table->decimal('shipping_fee', 12, 2)->default(0),
            'distance_km' => fn (Blueprint $table) => $table->decimal('distance_km', 10, 2)->default(0),
            'grand_total' => fn (Blueprint $table) => $table->decimal('grand_total', 14, 2)->nullable(),
            'fulfillment_type' => fn (Blueprint $table) => $table->string('fulfillment_type', 20)->nullable(),
            'delivery_address' => fn (Blueprint $table) => $table->text('delivery_address')->nullable(),
            'pickup_address' => fn (Blueprint $table) => $table->text('pickup_address')->nullable(),
            'payment_method' => fn (Blueprint $table) => $table->string('payment_method', 30)->nullable(),
            'payment_status' => fn (Blueprint $table) => $table->string('payment_status', 30)->default('unpaid'),
            'buyer_name_snapshot' => fn (Blueprint $table) => $table->string('buyer_name_snapshot')->nullable(),
            'buyer_contact_snapshot' => fn (Blueprint $table) => $table->string('buyer_contact_snapshot', 80)->nullable(),
            'buyer_address_snapshot' => fn (Blueprint $table) => $table->text('buyer_address_snapshot')->nullable(),
            'farmer_name_snapshot' => fn (Blueprint $table) => $table->string('farmer_name_snapshot')->nullable(),
            'farmer_address_snapshot' => fn (Blueprint $table) => $table->text('farmer_address_snapshot')->nullable(),
            'product_name_snapshot' => fn (Blueprint $table) => $table->string('product_name_snapshot')->nullable(),
            'notes_snapshot' => fn (Blueprint $table) => $table->text('notes_snapshot')->nullable(),
        ];

        foreach ($columns as $name => $addColumn) {
            if (!Schema::hasColumn('orders', $name)) {
                Schema::table('orders', function (Blueprint $table) use ($addColumn) {
                    $addColumn($table);
                });
            }
        }
    }

    public function down(): void
    {
        $columns = [
            'purchase_unit',
            'quantity_sacks',
            'quantity_kilos',
            'total_kilos',
            'price_per_kg',
            'price_per_sack',
            'subtotal',
            'shipping_fee',
            'distance_km',
            'grand_total',
            'fulfillment_type',
            'delivery_address',
            'pickup_address',
            'payment_method',
            'payment_status',
            'buyer_name_snapshot',
            'buyer_contact_snapshot',
            'buyer_address_snapshot',
            'farmer_name_snapshot',
            'farmer_address_snapshot',
            'product_name_snapshot',
            'notes_snapshot',
        ];

        foreach ($columns as $column) {
            if (Schema::hasColumn('orders', $column)) {
                Schema::table('orders', function (Blueprint $table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }
    }
};
