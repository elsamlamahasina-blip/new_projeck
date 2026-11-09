<?php

use App\Http\Controllers\Master\CustomerController;
use App\Http\Controllers\Master\ProductController;
use App\Http\Controllers\Master\VendorController;
use App\Http\Controllers\Master\WarehouseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['auth'])->group(function () {
    // Role redirector (supports both GET and POST for maximum resilience)
    Route::match(['get', 'post'], '/dashboard', [RoleDashboardController::class, 'redirect'])->name('dashboard');

    // Role-specific dashboards
    Route::get('/dashboard/admin', [RoleDashboardController::class, 'admin'])
        ->middleware('role:admin')
        ->name('dashboard.admin');

    Route::get('/dashboard/pembelian', [RoleDashboardController::class, 'pembelian'])
        ->middleware('role:pembelian')
        ->name('dashboard.pembelian');

    Route::get('/dashboard/gudang', [RoleDashboardController::class, 'gudang'])
        ->middleware('role:gudang')
        ->name('dashboard.gudang');

    Route::get('/dashboard/penjualan', [RoleDashboardController::class, 'penjualan'])
        ->middleware('role:penjualan')
        ->name('dashboard.penjualan');

    Route::get('/dashboard/kasir', [RoleDashboardController::class, 'kasir'])
        ->middleware('role:kasir')
        ->name('dashboard.kasir');

    Route::get('/dashboard/keuangan', [RoleDashboardController::class, 'keuangan'])
        ->middleware('role:keuangan')
        ->name('dashboard.keuangan');

    Route::get('/dashboard/eksekutif', [RoleDashboardController::class, 'eksekutif'])
        ->middleware('role:eksekutif')
        ->name('dashboard.eksekutif');

    // User profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Master Data
    Route::prefix('master')->name('master.')->group(function () {
        Route::resource('vendors', VendorController::class)->except(['show']);
        Route::resource('customers', CustomerController::class)->except(['show']);
        Route::resource('warehouses', WarehouseController::class)->except(['show']);
        Route::resource('products', ProductController::class);

        // Product Variant sub-routes
        Route::post('products/{product}/variants', [ProductController::class, 'storeVariant'])
            ->name('products.variants.store');
        Route::put('products/{product}/variants/{variant}', [ProductController::class, 'updateVariant'])
            ->name('products.variants.update');
        Route::delete('products/{product}/variants/{variant}', [ProductController::class, 'destroyVariant'])
            ->name('products.variants.destroy');
    });
});

require __DIR__.'/auth.php';
