<?php

use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Dashboard
Route::get('/dashboard', [ReportController::class, 'dashboard'])->name('dashboard');

// Services
Route::resource('services', ServiceController::class);

// Customers
Route::resource('customers', CustomerController::class);

// Staff
Route::resource('staff', StaffController::class);

// Appointments
Route::resource('appointments', AppointmentController::class);
Route::post('/appointments/{appointment}/confirm', [AppointmentController::class, 'confirm'])->name('appointments.confirm');
Route::post('/appointments/{appointment}/complete', [AppointmentController::class, 'complete'])->name('appointments.complete');

// POS (Point of Sale)
Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
Route::post('/pos/checkout', [PosController::class, 'checkout'])->name('pos.checkout');
Route::get('/pos/receipt/{transaction}', [PosController::class, 'receipt'])->name('pos.receipt');
Route::get('/pos/transactions', [PosController::class, 'recentTransactions'])->name('pos.transactions');

// Inventory
Route::resource('inventory', InventoryController::class);
Route::post('/inventory/{inventory}/adjust', [InventoryController::class, 'adjustStock'])->name('inventory.adjust');

// Reports
Route::get('/reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
Route::get('/reports/staff', [ReportController::class, 'staffPerformance'])->name('reports.staff');
Route::get('/reports/inventory', [ReportController::class, 'inventoryReport'])->name('reports.inventory');
Route::get('/reports/customers', [ReportController::class, 'customerReport'])->name('reports.customers');
