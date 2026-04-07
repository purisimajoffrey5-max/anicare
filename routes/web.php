<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PagesController;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\AdminApprovalsController;
use App\Http\Controllers\AdminFarmersMillersController;
use App\Http\Controllers\Admin\AnnouncementController as AdminAnnouncementController;

use App\Http\Controllers\Farmer\DashboardController as FarmerDashboardController;
use App\Http\Controllers\Farmer\FarmProfileController;
use App\Http\Controllers\Farmer\MillingRequestController;
use App\Http\Controllers\Farmer\RiceProductController;
use App\Http\Controllers\Farmer\OrderController as FarmerOrderController;

use App\Http\Controllers\Miller\DashboardController as MillerDashboardController;
use App\Http\Controllers\Miller\RequestController as MillerRequestController;
use App\Http\Controllers\Miller\ScheduleController as MillerScheduleController;
use App\Http\Controllers\Miller\ReportController as MillerReportController;
use App\Http\Controllers\Miller\ProfileController as MillerProfileController;

use App\Http\Controllers\Resident\DashboardController as ResidentDashboardController;
use App\Http\Controllers\Resident\MarketplaceController as ResidentMarketplaceController;
use App\Http\Controllers\Resident\OrderController as ResidentOrderController;
use App\Http\Controllers\Resident\ProfileController as ResidentProfileController;
use App\Http\Controllers\Resident\MapController;

use App\Http\Controllers\LocationController;

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/
Route::get('/', [PagesController::class, 'welcome'])->name('main');

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])
    ->name('login.post')
    ->middleware('throttle:5,1'); // ✅ 5 attempts per 1 minute

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', [AuthController::class, 'redirectDashboard'])->name('dashboard.redirect');

/*
|--------------------------------------------------------------------------
| ADMIN (auth required)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', fn () => view('dashboards.admin'))->name('dashboard');

    Route::view('/overview', 'admin.overview')->name('overview');
    Route::view('/inventory', 'admin.inventory')->name('inventory');
    Route::view('/distribution', 'admin.distribution')->name('distribution');
    Route::view('/market', 'admin.market')->name('market');

    Route::get('/farmers-millers', [AdminFarmersMillersController::class, 'index'])->name('farmers_millers');
    Route::get('/farmers-millers/{id}', [AdminFarmersMillersController::class, 'show'])->name('farmers_millers.show');
    Route::post('/farmers-millers/{id}/delete', [AdminFarmersMillersController::class, 'destroy'])->name('farmers_millers.delete');

    Route::get('/approvals', [AdminApprovalsController::class, 'index'])->name('approvals');
    Route::post('/approvals/{id}/approve', [AdminApprovalsController::class, 'approve'])->name('approvals.approve');
    Route::post('/approvals/{id}/revoke', [AdminApprovalsController::class, 'revoke'])->name('approvals.revoke');

    // ✅ ANNOUNCEMENTS
    Route::get('/announcements', [AdminAnnouncementController::class, 'index'])->name('announcements.index');
    Route::post('/announcements', [AdminAnnouncementController::class, 'store'])->name('announcements.store');
    Route::post('/announcements/{id}/archive', [AdminAnnouncementController::class, 'archive'])->name('announcements.archive');

    Route::get('/announcements-library', [AdminAnnouncementController::class, 'library'])->name('announcements.library');
    Route::post('/announcements/{id}/restore', [AdminAnnouncementController::class, 'restore'])->name('announcements.restore');
    Route::post('/announcements/{id}/delete', [AdminAnnouncementController::class, 'destroy'])->name('announcements.delete');
});

/*
|--------------------------------------------------------------------------
| FARMER (auth required)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('farmer')->name('farmer.')->group(function () {

    Route::get('/dashboard', [FarmerDashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [FarmProfileController::class, 'show'])->name('profile');
    Route::post('/profile', [FarmProfileController::class, 'update'])->name('profile.update');

    // ✅ Save Farmer location (keep inside farmer prefix)
    Route::post('/location', [LocationController::class, 'saveFarmer'])->name('location.save');

    Route::get('/milling/request', [MillingRequestController::class, 'create'])->name('milling.create');
    Route::post('/milling/request', [MillingRequestController::class, 'store'])->name('milling.store');
    Route::get('/milling/requests', [MillingRequestController::class, 'index'])->name('milling.index');

    Route::get('/products', [RiceProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [RiceProductController::class, 'create'])->name('products.create');
    Route::post('/products/create', [RiceProductController::class, 'store'])->name('products.store');
    Route::post('/products/{id}/toggle', [RiceProductController::class, 'toggle'])->name('products.toggle');
    Route::post('/products/{id}/delete', [RiceProductController::class, 'destroy'])->name('products.delete');

    Route::get('/orders', [FarmerOrderController::class, 'index'])->name('orders.index');
    Route::post('/orders/{id}/approve', [FarmerOrderController::class, 'approve'])->name('orders.approve');
    Route::post('/orders/{id}/complete', [FarmerOrderController::class, 'complete'])->name('orders.complete');
    Route::post('/orders/{id}/cancel', [FarmerOrderController::class, 'cancel'])->name('orders.cancel');
});

/*
|--------------------------------------------------------------------------
| MILLER (auth required)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('miller')->name('miller.')->group(function () {
    Route::get('/dashboard', [MillerDashboardController::class, 'index'])->name('dashboard');
    Route::post('/toggle-open', [MillerDashboardController::class, 'toggleOpen'])->name('toggleOpen');

    Route::get('/requests', [MillerRequestController::class, 'index'])->name('requests');
    Route::post('/requests/{id}/approve', [MillerRequestController::class, 'approve'])->name('requests.approve');
    Route::post('/requests/{id}/reject', [MillerRequestController::class, 'reject'])->name('requests.reject');
    Route::post('/requests/{id}/complete', [MillerRequestController::class, 'complete'])->name('requests.complete');

    Route::get('/schedule', [MillerScheduleController::class, 'index'])->name('schedule');
    Route::post('/schedule/{id}', [MillerScheduleController::class, 'setSchedule'])->name('schedule.set');

    Route::get('/reports', [MillerReportController::class, 'index'])->name('reports');

    // ✅ Miller profile page + save location
    Route::get('/profile', [MillerProfileController::class, 'edit'])->name('profile');
    Route::post('/location', [LocationController::class, 'saveMiller'])->name('location.save');
});

/*
|--------------------------------------------------------------------------
| RESIDENT (auth required)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('resident')->name('resident.')->group(function () {
    Route::get('/dashboard', [ResidentDashboardController::class, 'index'])->name('dashboard');

    Route::get('/marketplace', [ResidentMarketplaceController::class, 'index'])->name('marketplace');

    Route::get('/orders', [ResidentOrderController::class, 'index'])->name('orders.index');
    Route::post('/orders', [ResidentOrderController::class, 'store'])->name('orders.store');

    // ✅ CHECKOUT
    Route::get('/checkout/{id}', [ResidentOrderController::class, 'showCheckout'])->name('checkout.show');
    Route::post('/checkout/{id}', [ResidentOrderController::class, 'placeOrder'])->name('checkout.place');

    Route::get('/profile', [ResidentProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [ResidentProfileController::class, 'update'])->name('profile.update');

    // ✅ Map data endpoint protected by auth and under resident
    Route::get('/map-data', [MapController::class, 'mapData'])->name('map.data');
});