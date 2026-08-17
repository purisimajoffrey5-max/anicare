<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PagesController;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\AdminApprovalsController;
use App\Http\Controllers\AdminFarmersMillersController;
use App\Http\Controllers\Admin\AnnouncementController as AdminAnnouncementController;
use App\Http\Controllers\Admin\AdminInventoryController;
use App\Http\Controllers\Admin\AdminMarketplaceController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminDistributionController;
use App\Http\Controllers\Admin\AdminMillingRequestController;

use App\Http\Controllers\Farmer\DashboardController as FarmerDashboardController;
use App\Http\Controllers\Farmer\FarmProfileController;
use App\Http\Controllers\Farmer\MillingRequestController;
use App\Http\Controllers\Farmer\MillingCompletionController;
use App\Http\Controllers\Farmer\EarningsController as FarmerEarningsController;
use App\Http\Controllers\Farmer\RiceProductController;
use App\Http\Controllers\Farmer\OrderController as FarmerOrderController;

use App\Http\Controllers\Miller\DashboardController as MillerDashboardController;
use App\Http\Controllers\Miller\RequestController as MillerRequestController;
use App\Http\Controllers\Miller\ScheduleController as MillerScheduleController;
use App\Http\Controllers\Miller\ReportController as MillerReportController;
use App\Http\Controllers\Miller\ProfileController as MillerProfileController;
use App\Http\Controllers\Miller\EarningsController as MillerEarningsController;

use App\Http\Controllers\Resident\DashboardController as ResidentDashboardController;
use App\Http\Controllers\Resident\MarketplaceController as ResidentMarketplaceController;
use App\Http\Controllers\Resident\OrderController as ResidentOrderController;
use App\Http\Controllers\Resident\OrderInvoiceController;
use App\Http\Controllers\Resident\ProfileController as ResidentProfileController;
use App\Http\Controllers\Resident\MapController;

use App\Http\Controllers\LocationController;

use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\NotificationController;


/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/

Route::get('/', [PagesController::class, 'welcome'])
    ->name('main');


/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.post')
    ->middleware('throttle:5,1');


/*
|--------------------------------------------------------------------------
| REGISTRATION
|--------------------------------------------------------------------------
*/

Route::get('/register', [AuthController::class, 'showRegister'])
    ->name('register');

Route::post('/register', [AuthController::class, 'register'])
    ->name('register.post');


/*
|--------------------------------------------------------------------------
| REGISTRATION OTP
|--------------------------------------------------------------------------
*/

Route::get(
    '/register/verify-otp',
    [AuthController::class, 'showRegisterOtpForm']
)->name('register.otp.form');


Route::post(
    '/register/verify-otp',
    [AuthController::class, 'verifyRegisterOtp']
)->name('register.otp.verify');


/*
|--------------------------------------------------------------------------
| RESEND REGISTRATION OTP
|--------------------------------------------------------------------------
*/

Route::post(
    '/register/resend-otp',
    [AuthController::class, 'resendRegisterOtp']
)->name('register.otp.resend');


/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');


/*
|--------------------------------------------------------------------------
| DASHBOARD REDIRECT
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [AuthController::class, 'redirectDashboard'])
    ->name('dashboard.redirect');

/*
|--------------------------------------------------------------------------
| GLOBAL IN-APP NOTIFICATIONS
|--------------------------------------------------------------------------
|
| Shared by Admin, Farmer, Miller, and Resident notification bells.
|
*/

Route::middleware(['auth'])->group(function () {

    Route::get(
        '/notifications',
        [NotificationController::class, 'index']
    )->name('notifications.index');

    Route::get(
        '/notifications/{notification}/open',
        [NotificationController::class, 'open']
    )->name('notifications.open');

    Route::post(
        '/notifications/read-all',
        [NotificationController::class, 'readAll']
    )->name('notifications.readAll');

});



/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get(
            '/dashboard',
            fn () => view('dashboards.admin')
        )->name('dashboard');


        Route::view(
            '/overview',
            'admin.overview'
        )->name('overview');


        /*
        |--------------------------------------------------------------------------
        | INVENTORY
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/inventory',
            [AdminInventoryController::class, 'index']
        )->name('inventory');


        /*
        |--------------------------------------------------------------------------
        | DISTRIBUTION
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/distribution',
            [AdminDistributionController::class, 'index']
        )->name('distribution');

        Route::post(
            '/distribution',
            [AdminDistributionController::class, 'store']
        )->name('distribution.store');

        Route::post(
            '/distribution/{id}/schedule',
            [AdminDistributionController::class, 'schedule']
        )->name('distribution.schedule');

        Route::post(
            '/distribution/{id}/complete',
            [AdminDistributionController::class, 'complete']
        )->name('distribution.complete');


        /*
        |--------------------------------------------------------------------------
        | MARKETPLACE
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/market',
            [AdminMarketplaceController::class, 'index']
        )->name('market');


        /*
        |--------------------------------------------------------------------------
        | ADMIN CHECKOUT
        |--------------------------------------------------------------------------
        */

        Route::middleware('role:admin')->group(function () {

            Route::get(
                '/checkout/{id}',
                [AdminOrderController::class, 'showCheckout']
            )->name('checkout.show');

            Route::post(
                '/checkout/{id}',
                [AdminOrderController::class, 'placeOrder']
            )->name('checkout.place');


            /*
            |--------------------------------------------------------------------------
            | ASSIGN PALAY TO MILLER
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/inventory/assign/{id}',
                [AdminInventoryController::class, 'assignForm']
            )->name('inventory.assign.form');

            Route::post(
                '/inventory/assign/{id}',
                [AdminInventoryController::class, 'assign']
            )->name('inventory.assign');
        });

        Route::get(
    '/orders',
    [AdminOrderController::class, 'index']
)->name('orders.index');

Route::get(
    '/orders/{id}',
    [AdminOrderController::class, 'show']
)->name('orders.show');

Route::post(
    '/orders/{id}/received',
    [AdminOrderController::class, 'confirmReceived']
)->name('orders.received');

Route::get(
    '/orders/{order}/invoice',
    [AdminOrderController::class, 'invoiceShow']
)->name('orders.invoice.show');

Route::get(
    '/orders/{order}/invoice/download',
    [AdminOrderController::class, 'invoiceDownload']
)->name('orders.invoice.download');


        /*
        |--------------------------------------------------------------------------
        | ADMIN MILLING TRANSACTIONS
        |--------------------------------------------------------------------------
        |
        | Admin can act as the requester:
        |
        | PENDING
        |   -> Miller ACCEPTS
        |   -> SCHEDULED + milling fee
        |   -> IN PROGRESS
        |   -> FINISHED + proof
        |   -> Admin confirms completion
        |   -> COMPLETED
        |
        */

        Route::get(
            '/milling/request',
            [AdminMillingRequestController::class, 'create']
        )->name('milling.create');

        Route::post(
            '/milling/request',
            [AdminMillingRequestController::class, 'store']
        )->name('milling.store');


        /*
        |--------------------------------------------------------------------------
        | SELECTED MILLER BARANGAY -> MAP LOCATION
        |--------------------------------------------------------------------------
        |
        | Used by the Admin Milling Request form.
        | The selected Miller's users.barangay is geocoded and returned as
        | latitude/longitude for the hidden Leaflet shipping calculation.
        |
        | IMPORTANT:
        | This route is already inside prefix('admin') and name('admin.'),
        | therefore the final URL/name become:
        |
        | URL  : /admin/milling/millers/{id}/location
        | NAME : admin.milling.millerLocation
        |
        */

        Route::get(
            '/milling/millers/{id}/location',
            [AdminMillingRequestController::class, 'millerLocation']
        )->name('milling.millerLocation');


        Route::get(
            '/milling/requests',
            [AdminMillingRequestController::class, 'index']
        )->name('milling.index');

        Route::get(
            '/milling/requests/{id}',
            [AdminMillingRequestController::class, 'show']
        )->name('milling.show');

        Route::post(
            '/milling/requests/{id}/cancel',
            [AdminMillingRequestController::class, 'cancel']
        )->name('milling.cancel');

        Route::post(
            '/milling/requests/{id}/confirm-completed',
            [AdminMillingRequestController::class, 'confirmCompleted']
        )->name('milling.confirmCompleted');

        Route::get(
            '/milling/requests/{id}/invoice',
            [AdminMillingRequestController::class, 'invoiceShow']
        )->name('milling.invoice.show');

        Route::get(
            '/milling/requests/{id}/invoice/download',
            [AdminMillingRequestController::class, 'invoiceDownload']
        )->name('milling.invoice.download');

/*
        |--------------------------------------------------------------------------
        | FARMERS / MILLERS
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/farmers-millers',
            [AdminFarmersMillersController::class, 'index']
        )->name('farmers_millers');

        Route::get(
            '/farmers-millers/{id}',
            [AdminFarmersMillersController::class, 'show']
        )->name('farmers_millers.show');

        Route::post(
            '/farmers-millers/{id}/delete',
            [AdminFarmersMillersController::class, 'destroy']
        )->name('farmers_millers.delete');


        /*
        |--------------------------------------------------------------------------
        | APPROVALS
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/approvals',
            [AdminApprovalsController::class, 'index']
        )->name('approvals');

        Route::post(
            '/approvals/{id}/approve',
            [AdminApprovalsController::class, 'approve']
        )->name('approvals.approve');

        Route::post(
            '/approvals/{id}/revoke',
            [AdminApprovalsController::class, 'revoke']
        )->name('approvals.revoke');


        /*
        |--------------------------------------------------------------------------
        | ANNOUNCEMENTS
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/announcements',
            [AdminAnnouncementController::class, 'index']
        )->name('announcements.index');

        Route::post(
            '/announcements',
            [AdminAnnouncementController::class, 'store']
        )->name('announcements.store');

        Route::post(
            '/announcements/{id}/archive',
            [AdminAnnouncementController::class, 'archive']
        )->name('announcements.archive');

        Route::get(
            '/announcements-library',
            [AdminAnnouncementController::class, 'library']
        )->name('announcements.library');

        Route::post(
            '/announcements/{id}/restore',
            [AdminAnnouncementController::class, 'restore']
        )->name('announcements.restore');

        Route::post(
            '/announcements/{id}/delete',
            [AdminAnnouncementController::class, 'destroy']
        )->name('announcements.delete');


        /*
        |--------------------------------------------------------------------------
        | ADMIN NOTIFICATIONS
        |--------------------------------------------------------------------------
        |
        | Admin uses the GLOBAL notification routes declared near the top:
        |
        | notifications.index
        | notifications.open
        | notifications.readAll
        |
        | Do NOT add App\Http\Controllers\Admin\NotificationController here.
        | The global NotificationController handles Admin, Farmer, Miller,
        | and Resident notification navigation.
        |
        */
    });


/*
|--------------------------------------------------------------------------
| FARMER
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])
    ->prefix('farmer')
    ->name('farmer.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/dashboard',
            [FarmerDashboardController::class, 'index']
        )->name('dashboard');


        /*
        |--------------------------------------------------------------------------
        | EARNINGS & ANALYTICS
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/earnings',
            [FarmerEarningsController::class, 'index']
        )->name('earnings.index');


        /*
        |--------------------------------------------------------------------------
        | PROFILE
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/profile',
            [FarmProfileController::class, 'show']
        )->name('profile');

        Route::post(
            '/profile',
            [FarmProfileController::class, 'update']
        )->name('profile.update');


        /*
        |--------------------------------------------------------------------------
        | FARM LOCATION
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/location',
            [LocationController::class, 'saveFarmer']
        )->name('location.save');


        /*
        |--------------------------------------------------------------------------
        | MILLING REQUEST
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/milling/request',
            [MillingRequestController::class, 'create']
        )->name('milling.create');

        Route::post(
            '/milling/request',
            [MillingRequestController::class, 'store']
        )->name('milling.store');

        Route::get(
            '/milling/requests',
            [MillingRequestController::class, 'index']
        )->name('milling.index');

        /*
        |--------------------------------------------------------------------------
        | FARMER CONFIRMS MILLING COMPLETION
        |--------------------------------------------------------------------------
        |
        | FINISHED + PAID + PROOF
        |          ↓
        | Farmer confirms
        |          ↓
        | COMPLETED
        |
        */

        Route::post(
            '/milling/requests/{id}/confirm-completed',
            [MillingCompletionController::class, 'confirmCompleted']
        )->name('milling.confirmCompleted');


        /*
        |--------------------------------------------------------------------------
        | RICE PRODUCTS
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/products',
            [RiceProductController::class, 'index']
        )->name('products.index');

        Route::get(
            '/products/create',
            [RiceProductController::class, 'create']
        )->name('products.create');

        Route::post(
            '/products/create',
            [RiceProductController::class, 'store']
        )->name('products.store');

        Route::post(
            '/products/{id}/toggle',
            [RiceProductController::class, 'toggle']
        )->name('products.toggle');

        Route::post(
            '/products/{id}/stock/out',
            [RiceProductController::class, 'outOfStock']
        )->name('products.outOfStock');

        Route::post(
            '/products/{id}/stock/restock',
            [RiceProductController::class, 'restock']
        )->name('products.restock');

        Route::post(
            '/products/{id}/delete',
            [RiceProductController::class, 'destroy']
        )->name('products.delete');


        /*
        |--------------------------------------------------------------------------
        | FARMER ORDERS
        |--------------------------------------------------------------------------
        |
        | Workflow:
        | PENDING
        |   -> APPROVED
        |   -> PAID (when payment is actually received)
        |   -> SET EXPECTED DELIVERY DATE & TIME
        |   -> DISPATCH NOW / OUT FOR DELIVERY
        |   -> DELIVERED + proof photo
        |   -> Buyer confirms "I Received My Order"
        |   -> COMPLETED
        |
        */

        Route::get(
            '/orders',
            [FarmerOrderController::class, 'index']
        )->name('orders.index');

        Route::post(
            '/orders/{id}/approve',
            [FarmerOrderController::class, 'approve']
        )->name('orders.approve');

        Route::post(
            '/orders/{id}/paid',
            [FarmerOrderController::class, 'markPaid']
        )->name('orders.paid');

        Route::post(
            '/orders/{id}/expected-delivery',
            [FarmerOrderController::class, 'setExpectedDelivery']
        )->name('orders.expectedDelivery');

        Route::post(
            '/orders/{id}/start-delivery',
            [FarmerOrderController::class, 'startDelivery']
        )->name('orders.startDelivery');

        Route::post(
            '/orders/{id}/delivered',
            [FarmerOrderController::class, 'markDelivered']
        )->name('orders.delivered');

        /*
         * Legacy route:
         * The new Farmer OrderController no longer allows the farmer
         * to finalize an order as COMPLETED. The buyer must confirm receipt.
         * Keeping this route prevents old buttons/links from crashing
         * while you finish updating the Blade files.
         */
        Route::post(
            '/orders/{id}/complete',
            [FarmerOrderController::class, 'complete']
        )->name('orders.complete');

        Route::post(
            '/orders/{id}/cancel',
            [FarmerOrderController::class, 'cancel']
        )->name('orders.cancel');
    });


/*
|--------------------------------------------------------------------------
| MILLER
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])
    ->prefix('miller')
    ->name('miller.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/dashboard',
            [MillerDashboardController::class, 'index']
        )->name('dashboard');


        /*
        |--------------------------------------------------------------------------
        | EARNINGS & ANALYTICS
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/earnings',
            [MillerEarningsController::class, 'index']
        )->name('earnings.index');

        Route::post(
            '/toggle-open',
            [MillerDashboardController::class, 'toggleOpen']
        )->name('toggleOpen');


        /*
        |--------------------------------------------------------------------------
        | REQUESTS - MILLING TRANSACTION WORKFLOW
        |--------------------------------------------------------------------------
        |
        | PENDING
        |   -> ACCEPTED
        |   -> SCHEDULED + MILLING FEE
        |   -> PAID (separate payment status)
        |   -> IN PROGRESS
        |   -> FINISHED + PROOF
        |   -> REQUESTER CONFIRMS
        |   -> COMPLETED
        |
        */

        Route::get(
            '/requests',
            [MillerRequestController::class, 'index']
        )->name('requests');

        /*
         * Legacy approve route.
         * The new controller forwards approve() to accept().
         */
        Route::post(
            '/requests/{id}/approve',
            [MillerRequestController::class, 'approve']
        )->name('requests.approve');

        Route::post(
            '/requests/{id}/accept',
            [MillerRequestController::class, 'accept']
        )->name('requests.accept');

        Route::post(
            '/requests/{id}/reject',
            [MillerRequestController::class, 'reject']
        )->name('requests.reject');

        Route::post(
            '/requests/{id}/schedule',
            [MillerRequestController::class, 'setSchedule']
        )->name('requests.schedule');

        Route::post(
            '/requests/{id}/paid',
            [MillerRequestController::class, 'markPaid']
        )->name('requests.paid');

        Route::post(
            '/requests/{id}/start-milling',
            [MillerRequestController::class, 'startMilling']
        )->name('requests.startMilling');

        Route::post(
            '/requests/{id}/finish',
            [MillerRequestController::class, 'finishMilling']
        )->name('requests.finish');

        /*
         * Legacy complete route.
         * Miller no longer finalizes COMPLETED.
         * Farmer/Admin requester must confirm the finished transaction.
         */
        Route::post(
            '/requests/{id}/complete',
            [MillerRequestController::class, 'complete']
        )->name('requests.complete');


        /*
        |--------------------------------------------------------------------------
        | SCHEDULE
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/schedule',
            [MillerScheduleController::class, 'index']
        )->name('schedule');

        Route::post(
            '/schedule/{id}',
            [MillerScheduleController::class, 'setSchedule']
        )->name('schedule.set');


        /*
        |--------------------------------------------------------------------------
        | REPORTS
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/reports',
            [MillerReportController::class, 'index']
        )->name('reports');


        /*
        |--------------------------------------------------------------------------
        | NOTIFICATIONS
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/notifications',
            [\App\Http\Controllers\Miller\NotificationController::class, 'index']
        )->name('notifications');

        Route::post(
            '/notifications/{id}/read',
            [\App\Http\Controllers\Miller\NotificationController::class, 'markAsRead']
        )->name('notifications.read');


        /*
        |--------------------------------------------------------------------------
        | PROFILE / LOCATION
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/profile',
            [MillerProfileController::class, 'edit']
        )->name('profile');

        Route::post(
            '/location',
            [LocationController::class, 'saveMiller']
        )->name('location.save');
    });


/*
|--------------------------------------------------------------------------
| RESIDENT
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])
    ->prefix('resident')
    ->name('resident.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/dashboard',
            [ResidentDashboardController::class, 'index']
        )->name('dashboard');


        /*
        |--------------------------------------------------------------------------
        | MARKETPLACE
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/marketplace',
            [ResidentMarketplaceController::class, 'index']
        )->name('marketplace');


        /*
        |--------------------------------------------------------------------------
        | ORDERS
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/orders',
            [ResidentOrderController::class, 'index']
        )->name('orders.index');

        Route::get(
            '/orders/{id}',
            [ResidentOrderController::class, 'show']
        )->name('orders.show');

        Route::post(
            '/orders/{id}/received',
            [ResidentOrderController::class, 'confirmReceived']
        )->name('orders.received');

        Route::post(
            '/orders',
            [ResidentOrderController::class, 'store']
        )->name('orders.store');


        /*
        |--------------------------------------------------------------------------
        | CHECKOUT
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/checkout/{id}',
            [ResidentOrderController::class, 'showCheckout']
        )->name('checkout.show');

        Route::post(
            '/checkout/{id}',
            [ResidentOrderController::class, 'placeOrder']
        )->name('checkout.place');

        /*
        |--------------------------------------------------------------------------
        | ORDER INVOICE
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/orders/{order}/success',
            [OrderInvoiceController::class, 'success']
        )->name('orders.success');

        Route::get(
            '/orders/{order}/invoice',
            [OrderInvoiceController::class, 'show']
        )->name('orders.invoice.show');

        Route::get(
            '/orders/{order}/invoice/download',
            [OrderInvoiceController::class, 'download']
        )->name('orders.invoice.download');


        /*
        |--------------------------------------------------------------------------
        | PROFILE
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/profile',
            [ResidentProfileController::class, 'index']
        )->name('profile');

        Route::post(
            '/profile',
            [ResidentProfileController::class, 'update']
        )->name('profile.update');


        /*
        |--------------------------------------------------------------------------
        | MAP
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/map-data',
            [MapController::class, 'mapData']
        )->name('map.data');


        /*
        |--------------------------------------------------------------------------
        | PRODUCT DETAILS
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/product/{id}',
            [ResidentMarketplaceController::class, 'show']
        )->name('product.show');
    });


/*
|--------------------------------------------------------------------------
| FORGOT PASSWORD
|--------------------------------------------------------------------------
*/

Route::get(
    '/forgot-password',
    [ForgotPasswordController::class, 'showEmailForm']
)->name('forgot.password');


Route::post(
    '/forgot-password',
    [ForgotPasswordController::class, 'sendOtp']
)->name('forgot.password.send');


Route::get(
    '/verify-otp',
    [ForgotPasswordController::class, 'showOtpForm']
)->name('otp.form');


Route::post(
    '/verify-otp',
    [ForgotPasswordController::class, 'verifyOtp']
)->name('otp.verify');


Route::get(
    '/reset-password',
    [ForgotPasswordController::class, 'showResetForm']
)->name('password.reset.form');


Route::post(
    '/reset-password',
    [ForgotPasswordController::class, 'resetPassword']
)->name('password.reset');