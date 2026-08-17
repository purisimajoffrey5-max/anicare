<?php

namespace App\Providers;

use App\Observers\TransactionObserver;
use Illuminate\Support\ServiceProvider;

class NotificationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        /*
        |--------------------------------------------------------------------------
        | CENTRAL TRANSACTION NOTIFICATION OBSERVER
        |--------------------------------------------------------------------------
        |
        | This lets the bell react to model transactions without having to
        | manually add notification code to every controller action.
        |
        */

        $models = [
            \App\Models\User::class,
            \App\Models\Order::class,
            \App\Models\MillingRequest::class,
            \App\Models\RiceProduct::class,
            \App\Models\Distribution::class,
            \App\Models\InventoryItem::class,
            \App\Models\Announcement::class,
        ];

        foreach ($models as $modelClass) {
            if (class_exists($modelClass)) {
                $modelClass::observe(TransactionObserver::class);
            }
        }
    }
}
