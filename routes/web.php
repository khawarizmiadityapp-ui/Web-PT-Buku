<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SalesInvoiceController;
use App\Http\Controllers\StockAuditController;
use App\Http\Controllers\ProductReturnController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StockOutController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\PurchaseController;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard/sales-chart', [AuthController::class, 'getSalesChartData'])->name('dashboard.sales-chart');
    
    // Cashier / POS
    Route::get('/cashier', [CashierController::class, 'index'])->name('cashier.index');
    Route::get('/cashier/profile', [CashierController::class, 'profile'])->name('cashier.profile');
    Route::get('/cashier/transaction', [CashierController::class, 'transaction'])->name('cashier.transaction');
    Route::get('/cashier/transaction-enhanced', [CashierController::class, 'transactionEnhanced'])->name('cashier.transaction.enhanced');
    Route::post('/cashier/process', [CashierController::class, 'processTransaction'])->name('cashier.process');
    Route::get('/cashier/history', [CashierController::class, 'history'])->name('cashier.history');
    Route::get('/cashier/{id}', [CashierController::class, 'show'])->name('cashier.show');
    Route::get('/cashier/print/{id}', [CashierController::class, 'printReceipt'])->name('cashier.print');
    Route::get('/cashier/search/product', [CashierController::class, 'searchProduct'])->name('cashier.search.product');
    
    // Customers Management
    Route::get('customers/export', [CustomerController::class, 'export'])->name('customers.export');
    Route::resource('customers', CustomerController::class);
    Route::post('customers/quick-store', [CustomerController::class, 'quickStore'])->name('customers.quickStore');
    
    // Suppliers Management
    Route::get('suppliers/export', [SupplierController::class, 'export'])->name('suppliers.export');
    Route::resource('suppliers', SupplierController::class);
    
    // Purchase Management (Purchase Orders)
    Route::get('purchases/export', [PurchaseController::class, 'export'])->name('purchases.export');
    Route::resource('purchases', PurchaseController::class);
    Route::patch('purchases/{purchase}/status', [PurchaseController::class, 'updateStatus'])->name('purchases.updateStatus');
    
    // Stock Out Management
    Route::get('stock-outs/export', [StockOutController::class, 'export'])->name('stock-outs.export');
    Route::resource('stock-outs', StockOutController::class);
    
    // Sales Management
    Route::get('sales/invoices', [SalesInvoiceController::class, 'index'])->name('sales.invoices.index');
    Route::get('sales/invoices/create', [SalesInvoiceController::class, 'create'])->name('sales.invoices.create');
    Route::post('sales/invoices', [SalesInvoiceController::class, 'store'])->name('sales.invoices.store');
    Route::get('sales/invoices/{invoice}', [SalesInvoiceController::class, 'show'])->name('sales.invoices.show');
    Route::patch('sales/invoices/{invoice}/payment', [SalesInvoiceController::class, 'updatePayment'])->name('sales.invoices.updatePayment');
    Route::get('sales/report', [SalesInvoiceController::class, 'report'])->name('sales.report');
    
    // Warehouse
    Route::get('warehouse', [WarehouseController::class, 'index'])->name('warehouse.index');
    Route::get('warehouse/incoming-goods', [WarehouseController::class, 'incomingGoods'])->name('warehouse.incoming-goods');
    Route::post('warehouse/incoming-goods', [WarehouseController::class, 'storeIncomingGoods'])->name('warehouse.incoming-goods.store');
    
    // Warehouse - Verifikasi Barang Masuk
    Route::get('warehouse/verifikasi', [WarehouseController::class, 'verifikasiIndex'])->name('warehouse.verifikasi.index');
    Route::get('warehouse/verifikasi/{id}', [WarehouseController::class, 'verifikasiShow'])->name('warehouse.verifikasi.show');
    Route::post('warehouse/verifikasi/{id}', [WarehouseController::class, 'verifikasiProcess'])->name('warehouse.verifikasi.process');
    
    Route::get('warehouse/stock', [WarehouseController::class, 'stockWarehouse'])->name('warehouse.stock');
    Route::get('warehouse/picking', [WarehouseController::class, 'picking'])->name('warehouse.picking');
    Route::get('warehouse/packing', [WarehouseController::class, 'packing'])->name('warehouse.packing');
    
    // Warehouse - Stock Audit
    Route::get('warehouse/stock-audit/export', [StockAuditController::class, 'export'])->name('warehouse.stock-audit.export');
    Route::get('warehouse/stock-audit', [StockAuditController::class, 'index'])->name('warehouse.stock-audit.index');
    Route::post('warehouse/stock-audit/process', [StockAuditController::class, 'processAdjustment'])->name('warehouse.stock-audit.process');
    Route::get('warehouse/stock-audit/start', [StockAuditController::class, 'startStockCount'])->name('warehouse.stock-audit.start');
    
    // Warehouse - Returns
    Route::get('warehouse/returns', [ProductReturnController::class, 'index'])->name('warehouse.returns.index');
    Route::post('warehouse/returns', [ProductReturnController::class, 'store'])->name('warehouse.returns.store');
    Route::patch('warehouse/returns/{return}/status', [ProductReturnController::class, 'updateStatus'])->name('warehouse.returns.updateStatus');
    
    // Returns Management (New Enhanced Routes)
    Route::get('returns', [ProductReturnController::class, 'index'])->name('returns.index');
    Route::get('returns/create', [ProductReturnController::class, 'create'])->name('returns.create');
    Route::post('returns', [ProductReturnController::class, 'store'])->name('returns.store');
    Route::get('returns/{return}', [ProductReturnController::class, 'show'])->name('returns.show');
    Route::patch('returns/{return}/status', [ProductReturnController::class, 'updateStatus'])->name('returns.updateStatus');
    Route::post('returns/{return}/refund', [ProductReturnController::class, 'processRefund'])->name('returns.processRefund');
    Route::get('returns/search/invoice', [ProductReturnController::class, 'searchInvoice'])->name('returns.searchInvoice');
    
    // Master Data - Products
    Route::get('products/export', [ProductController::class, 'export'])->name('products.export');
    Route::resource('products', ProductController::class);
    
    // Reports
    Route::get('reports/financial', [ReportController::class, 'financial'])->name('reports.financial');
    
    // Settings
    Route::get('settings', [SettingsController::class, 'profile'])->name('settings');
    Route::get('settings/profile', [SettingsController::class, 'profile'])->name('settings.profile.view');
    Route::get('settings/system', [SettingsController::class, 'system'])->name('settings.system');
    Route::get('settings/audit', [SettingsController::class, 'audit'])->name('settings.audit');
    Route::get('settings/audit/export', [SettingsController::class, 'exportAudit'])->name('settings.audit.export');
    Route::post('settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile');
    Route::post('settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password');
    Route::post('settings/notifications', [SettingsController::class, 'updateNotifications'])->name('settings.notifications');
    Route::post('settings/system', [SettingsController::class, 'updateSystem'])->name('settings.system.update');
    Route::post('settings/company', [SettingsController::class, 'updateCompany'])->name('settings.company');
});
