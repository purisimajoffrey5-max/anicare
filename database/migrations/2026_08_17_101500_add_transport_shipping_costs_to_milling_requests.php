<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('milling_requests')) {
            return;
        }

        Schema::table('milling_requests', function (Blueprint $table) {

            if (!Schema::hasColumn('milling_requests', 'transport_type')) {
                $table->string('transport_type', 20)
                    ->default('delivery')
                    ->index();
            }

            if (!Schema::hasColumn('milling_requests', 'quantity_unit')) {
                $table->string('quantity_unit', 20)
                    ->default('kg');
            }

            if (!Schema::hasColumn('milling_requests', 'quantity_value')) {
                $table->decimal('quantity_value', 12, 2)
                    ->nullable();
            }

            if (!Schema::hasColumn('milling_requests', 'pickup_address')) {
                $table->string('pickup_address', 500)
                    ->nullable();
            }

            if (!Schema::hasColumn('milling_requests', 'requester_latitude')) {
                $table->decimal('requester_latitude', 10, 7)
                    ->nullable();
            }

            if (!Schema::hasColumn('milling_requests', 'requester_longitude')) {
                $table->decimal('requester_longitude', 10, 7)
                    ->nullable();
            }

            if (!Schema::hasColumn('milling_requests', 'miller_latitude')) {
                $table->decimal('miller_latitude', 10, 7)
                    ->nullable();
            }

            if (!Schema::hasColumn('milling_requests', 'miller_longitude')) {
                $table->decimal('miller_longitude', 10, 7)
                    ->nullable();
            }

            if (!Schema::hasColumn('milling_requests', 'shipping_distance_km')) {
                $table->decimal('shipping_distance_km', 10, 2)
                    ->nullable();
            }

            if (!Schema::hasColumn('milling_requests', 'shipping_fee')) {
                $table->decimal('shipping_fee', 12, 2)
                    ->default(0);
            }

            // Existing workflow normally already has these.
            if (!Schema::hasColumn('milling_requests', 'milling_fee_per_kg')) {
                $table->decimal('milling_fee_per_kg', 12, 2)
                    ->default(0);
            }

            if (!Schema::hasColumn('milling_requests', 'total_amount')) {
                $table->decimal('total_amount', 14, 2)
                    ->default(0);
            }

            if (!Schema::hasColumn('milling_requests', 'grand_total')) {
                $table->decimal('grand_total', 14, 2)
                    ->default(0);
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('milling_requests')) {
            return;
        }

        $columns = [
            'quantity_unit',
            'quantity_value',
            'pickup_address',
            'requester_latitude',
            'requester_longitude',
            'miller_latitude',
            'miller_longitude',
            'shipping_distance_km',
            'shipping_fee',
            'grand_total',
        ];

        foreach ($columns as $column) {
            if (Schema::hasColumn('milling_requests', $column)) {
                Schema::table('milling_requests', function (Blueprint $table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }

        // transport_type, milling_fee_per_kg and total_amount are intentionally
        // not removed because they may have been created by older migrations.
    }
};