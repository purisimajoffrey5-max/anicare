<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $columns = [
            'vat_enabled' => fn (Blueprint $table) => $table->boolean('vat_enabled')->default(false),
            'vat_rate' => fn (Blueprint $table) => $table->decimal('vat_rate', 5, 4)->nullable(),
            'vatable_sales' => fn (Blueprint $table) => $table->decimal('vatable_sales', 14, 2)->nullable(),
            'vat_amount' => fn (Blueprint $table) => $table->decimal('vat_amount', 14, 2)->nullable(),
            'total_sales' => fn (Blueprint $table) => $table->decimal('total_sales', 14, 2)->nullable(),
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
        foreach ([
            'vat_enabled',
            'vat_rate',
            'vatable_sales',
            'vat_amount',
            'total_sales',
        ] as $column) {
            if (Schema::hasColumn('orders', $column)) {
                Schema::table('orders', function (Blueprint $table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }
    }
};
