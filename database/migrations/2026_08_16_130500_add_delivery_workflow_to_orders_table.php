<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            if (!Schema::hasColumn('orders', 'payment_status')) {
                $table->string('payment_status', 20)
                    ->default('unpaid');
            }

            if (!Schema::hasColumn('orders', 'paid_at')) {
                $table->timestamp('paid_at')
                    ->nullable();
            }

            if (!Schema::hasColumn('orders', 'delivery_status')) {
                $table->string('delivery_status', 30)
                    ->default('pending');
            }

            if (!Schema::hasColumn('orders', 'out_for_delivery_at')) {
                $table->timestamp('out_for_delivery_at')
                    ->nullable();
            }

            if (!Schema::hasColumn('orders', 'proof_of_delivery_path')) {
                $table->string('proof_of_delivery_path')
                    ->nullable();
            }

            if (!Schema::hasColumn('orders', 'delivered_at')) {
                $table->timestamp('delivered_at')
                    ->nullable();
            }

            if (!Schema::hasColumn('orders', 'buyer_confirmed_at')) {
                $table->timestamp('buyer_confirmed_at')
                    ->nullable();
            }
        });
    }

    public function down(): void
    {
        $columns = [
            'payment_status',
            'paid_at',
            'delivery_status',
            'out_for_delivery_at',
            'proof_of_delivery_path',
            'delivered_at',
            'buyer_confirmed_at',
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
