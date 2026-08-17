<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | IN-APP NOTIFICATIONS
        |--------------------------------------------------------------------------
        |
        | If wala pang table:
        |     create natin.
        |
        | If existing na:
        |     add lang natin ang mga kulang na columns.
        |
        */

        if (!Schema::hasTable('in_app_notifications')) {

            Schema::create(
                'in_app_notifications',
                function (Blueprint $table) {

                    $table->id();

                    $table->unsignedBigInteger('user_id')
                        ->nullable()
                        ->index();

                    $table->unsignedBigInteger('actor_id')
                        ->nullable()
                        ->index();

                    $table->string('title', 180)
                        ->default('Notification');

                    $table->text('message')
                        ->nullable();

                    $table->string('type', 50)
                        ->default('info');

                    $table->string('icon', 80)
                        ->nullable();

                    $table->string('url', 500)
                        ->nullable();

                    $table->json('data')
                        ->nullable();

                    $table->boolean('is_read')
                        ->default(false);

                    $table->timestamp('read_at')
                        ->nullable();

                    $table->timestamps();
                }
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | EXISTING TABLE
        |--------------------------------------------------------------------------
        */

        Schema::table(
            'in_app_notifications',
            function (Blueprint $table) {

                /*
                |--------------------------------------------------------------------------
                | USER ID
                |--------------------------------------------------------------------------
                */

                if (
                    !Schema::hasColumn(
                        'in_app_notifications',
                        'user_id'
                    )
                ) {
                    $table->unsignedBigInteger('user_id')
                        ->nullable()
                        ->index();
                }


                /*
                |--------------------------------------------------------------------------
                | ACTOR ID
                |--------------------------------------------------------------------------
                |
                | Sino ang gumawa ng transaction.
                | Example:
                | Farmer approved order.
                |
                */

                if (
                    !Schema::hasColumn(
                        'in_app_notifications',
                        'actor_id'
                    )
                ) {
                    $table->unsignedBigInteger('actor_id')
                        ->nullable()
                        ->index();
                }


                /*
                |--------------------------------------------------------------------------
                | TITLE
                |--------------------------------------------------------------------------
                */

                if (
                    !Schema::hasColumn(
                        'in_app_notifications',
                        'title'
                    )
                ) {
                    $table->string('title', 180)
                        ->default('Notification');
                }


                /*
                |--------------------------------------------------------------------------
                | MESSAGE
                |--------------------------------------------------------------------------
                */

                if (
                    !Schema::hasColumn(
                        'in_app_notifications',
                        'message'
                    )
                ) {
                    $table->text('message')
                        ->nullable();
                }


                /*
                |--------------------------------------------------------------------------
                | TYPE
                |--------------------------------------------------------------------------
                |
                | order
                | delivery
                | payment
                | account
                | milling
                | inventory
                | announcement
                |
                */

                if (
                    !Schema::hasColumn(
                        'in_app_notifications',
                        'type'
                    )
                ) {
                    $table->string('type', 50)
                        ->default('info');
                }


                /*
                |--------------------------------------------------------------------------
                | ICON
                |--------------------------------------------------------------------------
                */

                if (
                    !Schema::hasColumn(
                        'in_app_notifications',
                        'icon'
                    )
                ) {
                    $table->string('icon', 80)
                        ->nullable();
                }


                /*
                |--------------------------------------------------------------------------
                | URL
                |--------------------------------------------------------------------------
                |
                | Kapag pinindot notification,
                | dito ire-redirect ang user.
                |
                */

                if (
                    !Schema::hasColumn(
                        'in_app_notifications',
                        'url'
                    )
                ) {
                    $table->string('url', 500)
                        ->nullable();
                }


                /*
                |--------------------------------------------------------------------------
                | EXTRA DATA
                |--------------------------------------------------------------------------
                */

                if (
                    !Schema::hasColumn(
                        'in_app_notifications',
                        'data'
                    )
                ) {
                    $table->json('data')
                        ->nullable();
                }


                /*
                |--------------------------------------------------------------------------
                | IS READ
                |--------------------------------------------------------------------------
                |
                | ITO ANG CURRENTLY MISSING SA DATABASE MO.
                |
                */

                if (
                    !Schema::hasColumn(
                        'in_app_notifications',
                        'is_read'
                    )
                ) {
                    $table->boolean('is_read')
                        ->default(false);
                }


                /*
                |--------------------------------------------------------------------------
                | READ AT
                |--------------------------------------------------------------------------
                */

                if (
                    !Schema::hasColumn(
                        'in_app_notifications',
                        'read_at'
                    )
                ) {
                    $table->timestamp('read_at')
                        ->nullable();
                }


                /*
                |--------------------------------------------------------------------------
                | CREATED AT
                |--------------------------------------------------------------------------
                */

                if (
                    !Schema::hasColumn(
                        'in_app_notifications',
                        'created_at'
                    )
                ) {
                    $table->timestamp('created_at')
                        ->nullable();
                }


                /*
                |--------------------------------------------------------------------------
                | UPDATED AT
                |--------------------------------------------------------------------------
                */

                if (
                    !Schema::hasColumn(
                        'in_app_notifications',
                        'updated_at'
                    )
                ) {
                    $table->timestamp('updated_at')
                        ->nullable();
                }
            }
        );
    }


    public function down(): void
    {
        /*
        |--------------------------------------------------------------------------
        | SAFE DOWN
        |--------------------------------------------------------------------------
        |
        | Huwag muna nating i-drop ang table dahil existing na ang
        | notification system mo at maaaring may laman na ito.
        |
        */
    }
};