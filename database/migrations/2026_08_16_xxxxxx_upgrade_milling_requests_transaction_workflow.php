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

            /*
            |--------------------------------------------------------------------------
            | REQUESTER
            |--------------------------------------------------------------------------
            |
            | requester_id   = actual user who requested the milling service
            | requester_role = farmer or admin
            |
            | Existing farmer_id/user_id columns are NOT removed so the current
            | Farmer/Miller workflow remains compatible while we upgrade it.
            |
            */

            if (!Schema::hasColumn('milling_requests', 'requester_id')) {
                $table->unsignedBigInteger('requester_id')
                    ->nullable()
                    ->index();
            }

            if (!Schema::hasColumn('milling_requests', 'requester_role')) {
                $table->string('requester_role', 20)
                    ->nullable()
                    ->index();
            }


            /*
            |--------------------------------------------------------------------------
            | PAYMENT / MILLING CHARGE
            |--------------------------------------------------------------------------
            */

            if (!Schema::hasColumn('milling_requests', 'payment_status')) {
                $table->string('payment_status', 20)
                    ->default('unpaid')
                    ->index();
            }

            if (!Schema::hasColumn('milling_requests', 'milling_fee_per_kg')) {
                $table->decimal('milling_fee_per_kg', 12, 2)
                    ->nullable();
            }

            if (!Schema::hasColumn('milling_requests', 'total_amount')) {
                $table->decimal('total_amount', 14, 2)
                    ->nullable();
            }

            if (!Schema::hasColumn('milling_requests', 'paid_at')) {
                $table->timestamp('paid_at')
                    ->nullable();
            }


            /*
            |--------------------------------------------------------------------------
            | MILLING WORKFLOW
            |--------------------------------------------------------------------------
            |
            | pending
            | accepted
            | scheduled
            | in_progress
            | finished
            | completed
            | rejected / cancelled
            |
            */

            if (!Schema::hasColumn('milling_requests', 'scheduled_at')) {
                $table->dateTime('scheduled_at')
                    ->nullable();
            }

            if (!Schema::hasColumn('milling_requests', 'started_at')) {
                $table->timestamp('started_at')
                    ->nullable();
            }

            if (!Schema::hasColumn('milling_requests', 'finished_at')) {
                $table->timestamp('finished_at')
                    ->nullable();
            }

            if (!Schema::hasColumn('milling_requests', 'completed_at')) {
                $table->timestamp('completed_at')
                    ->nullable();
            }


            /*
            |--------------------------------------------------------------------------
            | PROOF + REQUESTER CONFIRMATION
            |--------------------------------------------------------------------------
            */

            if (!Schema::hasColumn('milling_requests', 'proof_of_milling_path')) {
                $table->string('proof_of_milling_path')
                    ->nullable();
            }

            if (!Schema::hasColumn('milling_requests', 'requester_confirmed_at')) {
                $table->timestamp('requester_confirmed_at')
                    ->nullable();
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('milling_requests')) {
            return;
        }

        $columns = [
            'requester_id',
            'requester_role',
            'payment_status',
            'milling_fee_per_kg',
            'total_amount',
            'paid_at',
            'scheduled_at',
            'started_at',
            'finished_at',
            'completed_at',
            'proof_of_milling_path',
            'requester_confirmed_at',
        ];

        foreach ($columns as $column) {

            if (Schema::hasColumn('milling_requests', $column)) {

                Schema::table('milling_requests', function (Blueprint $table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }
    }
};