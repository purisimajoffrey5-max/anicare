<?php

use App\Http\Controllers\Resident\OrderInvoiceController;
use Illuminate\Support\Facades\Route;

// Copy these routes into routes/web.php.
// If you already have a resident/auth route group, you can place them inside that group instead.
Route::middleware('auth')->group(function () {
    Route::get('/resident/orders/{order}/success', [OrderInvoiceController::class, 'success'])
        ->name('resident.orders.success');

    Route::get('/resident/orders/{order}/invoice', [OrderInvoiceController::class, 'show'])
        ->name('resident.orders.invoice.show');

    Route::get('/resident/orders/{order}/invoice/download', [OrderInvoiceController::class, 'download'])
        ->name('resident.orders.invoice.download');
});
