<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('milling_requests')) {
            return;
        }

        Schema::table('milling_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('milling_requests', 'requester_name_snapshot')) {
                $table->string('requester_name_snapshot', 255)->nullable();
            }

            if (!Schema::hasColumn('milling_requests', 'requester_username_snapshot')) {
                $table->string('requester_username_snapshot', 255)->nullable();
            }

            if (!Schema::hasColumn('milling_requests', 'requester_contact_snapshot')) {
                $table->string('requester_contact_snapshot', 100)->nullable();
            }

            if (!Schema::hasColumn('milling_requests', 'requester_address_snapshot')) {
                $table->string('requester_address_snapshot', 500)->nullable();
            }
        });

        if (!Schema::hasTable('users')) {
            return;
        }

        $requestColumns = ['id'];
        foreach ([
            'requester_id','farmer_id','user_id','requester_role',
            'requester_name_snapshot','requester_username_snapshot',
            'requester_contact_snapshot','requester_address_snapshot',
        ] as $column) {
            if (Schema::hasColumn('milling_requests', $column)) {
                $requestColumns[] = $column;
            }
        }

        $userColumns = ['id'];
        foreach ([
            'fullname','username','email','role','mobile_number',
            'contact_number','phone','address','barangay','municipality','province',
        ] as $column) {
            if (Schema::hasColumn('users', $column)) {
                $userColumns[] = $column;
            }
        }

        DB::table('milling_requests')
            ->select(array_unique($requestColumns))
            ->orderBy('id')
            ->chunkById(100, function ($requests) use ($userColumns) {
                foreach ($requests as $request) {
                    $requesterId = (int) (
                        ($request->requester_id ?? null)
                        ?: ($request->farmer_id ?? null)
                        ?: ($request->user_id ?? null)
                        ?: 0
                    );

                    if ($requesterId <= 0) {
                        continue;
                    }

                    $user = DB::table('users')
                        ->select(array_unique($userColumns))
                        ->where('id', $requesterId)
                        ->first();

                    if (!$user) {
                        continue;
                    }

                    $name = trim((string) (
                        $user->fullname
                        ?? $user->username
                        ?? $user->email
                        ?? 'User #'.$requesterId
                    ));

                    $username = trim((string) ($user->username ?? ''));

                    $contact = trim((string) (
                        $user->mobile_number
                        ?? $user->contact_number
                        ?? $user->phone
                        ?? ''
                    ));

                    $address = trim((string) ($user->address ?? ''));
                    if ($address === '') {
                        $parts = array_values(array_filter([
                            trim((string) ($user->barangay ?? '')),
                            trim((string) ($user->municipality ?? 'Allacapan')),
                            trim((string) ($user->province ?? 'Cagayan')),
                        ]));
                        $address = implode(', ', array_unique($parts));
                    }

                    $updates = [];

                    if (trim((string) ($request->requester_name_snapshot ?? '')) === '') {
                        $updates['requester_name_snapshot'] = $name;
                    }

                    if (trim((string) ($request->requester_username_snapshot ?? '')) === '') {
                        $updates['requester_username_snapshot'] = $username;
                    }

                    if (trim((string) ($request->requester_contact_snapshot ?? '')) === '') {
                        $updates['requester_contact_snapshot'] = $contact;
                    }

                    if (trim((string) ($request->requester_address_snapshot ?? '')) === '') {
                        $updates['requester_address_snapshot'] = $address;
                    }

                    if (Schema::hasColumn('milling_requests', 'requester_role')) {
                        $existingRole = strtolower(trim((string) ($request->requester_role ?? '')));
                        $actualRole = strtolower(trim((string) ($user->role ?? '')));

                        if (
                            !in_array($existingRole, ['admin','farmer'], true) &&
                            in_array($actualRole, ['admin','farmer'], true)
                        ) {
                            $updates['requester_role'] = $actualRole;
                        }
                    }

                    if (
                        Schema::hasColumn('milling_requests', 'requester_id') &&
                        empty($request->requester_id ?? null)
                    ) {
                        $updates['requester_id'] = $requesterId;
                    }

                    if (!empty($updates)) {
                        DB::table('milling_requests')
                            ->where('id', $request->id)
                            ->update($updates);
                    }
                }
            });
    }

    public function down(): void
    {
        if (!Schema::hasTable('milling_requests')) {
            return;
        }

        foreach ([
            'requester_name_snapshot',
            'requester_username_snapshot',
            'requester_contact_snapshot',
            'requester_address_snapshot',
        ] as $column) {
            if (Schema::hasColumn('milling_requests', $column)) {
                Schema::table('milling_requests', function (Blueprint $table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }
    }
};