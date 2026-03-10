<?php

use App\Http\Controllers\{
    DashboardController,
    ProductController,
    CategoryController,
    SupplierController,
    SaleController,
    PurchaseController,
    EmployeeController,
    SalaryController,
    ReportController,
    StockController,
    UserController,
};
use Illuminate\Support\Facades\Route;

// ============================================================
// AUTH
// ============================================================
Route::middleware('guest')->group(function () {
    Route::view('/login', 'auth.login')->name('login');
    Route::post('/login', [App\Http\Controllers\Auth\AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [App\Http\Controllers\Auth\AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// ============================================================
// AUTHENTICATED ROUTES
// ============================================================
Route::middleware(['auth', 'check.active'])->group(function () {

    // Dashboard (semua role bisa akses, konten disesuaikan di controller)
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // ============================================================
    // KASIR ROUTES (kasir & admin)
    // ============================================================
    Route::middleware('role:admin,kasir')->group(function () {
        // Penjualan
        Route::resource('sales', SaleController::class)->except(['edit', 'update']);
        Route::get('sales/{sale}/print', [SaleController::class, 'printInvoice'])->name('sales.print');
    });

    // ============================================================
    // GUDANG ROUTES (gudang & admin)
    // ============================================================
    Route::middleware('role:admin,gudang')->group(function () {
        // Pembelian
        Route::resource('purchases', PurchaseController::class)->except(['edit', 'update']);
        Route::get('purchases/{purchase}/print', [PurchaseController::class, 'printInvoice'])->name('purchases.print');

        // Stok
        Route::get('stock', [StockController::class, 'index'])->name('stock.index');
        Route::post('stock/adjust', [StockController::class, 'adjust'])->name('stock.adjust');

        // Produk - view only untuk gudang
        Route::get('products', [ProductController::class, 'index'])->name('products.index');
        Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');
    });

    // ============================================================
    // ADMIN ONLY ROUTES
    // ============================================================
    Route::middleware('role:admin')->group(function () {
        // Produk - full CRUD
        Route::resource('products', ProductController::class)->except(['index', 'show']);

        // Kategori
        Route::resource('categories', CategoryController::class);

        // Supplier
        Route::resource('suppliers', SupplierController::class);

        // Karyawan
        Route::resource('employees', EmployeeController::class);
        Route::get('employees/export/excel', [EmployeeController::class, 'exportExcel'])->name('employees.export');
        Route::post('employees/import/excel', [EmployeeController::class, 'importExcel'])->name('employees.import');

        // Gaji
        Route::resource('salary', SalaryController::class)->except(['edit', 'update']);
        Route::get('salary/{salary}/print', [SalaryController::class, 'printSlip'])->name('salary.print');

        // Manajemen User
        Route::resource('users', UserController::class);

        // Laporan
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('sales', [ReportController::class, 'salesReport'])->name('sales');
            Route::get('purchases', [ReportController::class, 'purchaseReport'])->name('purchases');
            Route::get('stock', [ReportController::class, 'stockReport'])->name('stock');

            // Export
            Route::get('sales/pdf', [ReportController::class, 'exportSalesPdf'])->name('sales.pdf');
            Route::get('sales/excel', [ReportController::class, 'exportSalesExcel'])->name('sales.excel');
            Route::get('purchases/excel', [ReportController::class, 'exportPurchaseExcel'])->name('purchases.excel');
        });
    });
});
