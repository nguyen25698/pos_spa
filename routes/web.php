<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [ReportController::class, 'dashboard'])->name('dashboard');

// Services
Route::resource('services', ServiceController::class);

// Customers
Route::resource('customers', CustomerController::class);

// Staff
Route::resource('staff', StaffController::class);

// Appointments
Route::resource('appointments', AppointmentController::class);

// Inventory
Route::resource('inventory', InventoryController::class);

// POS Terminal
Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
Route::post('/pos/checkout', [PosController::class, 'processCheckout'])->name('pos.checkout');
Route::get('/pos/receipt/{transaction}', [PosController::class, 'receipt'])->name('pos.receipt');

// Reports
Route::get('/reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
Route::get('/reports/commissions', [ReportController::class, 'commissions'])->name('reports.commissions');
