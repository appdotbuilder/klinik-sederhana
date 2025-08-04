<?php

use App\Http\Controllers\BillController;
use App\Http\Controllers\BillItemController;
use App\Http\Controllers\BillPaymentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\PrescriptionItemController;
use App\Http\Controllers\VisitController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/health-check', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
    ]);
})->name('health-check');

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Patients Management
    Route::resource('patients', PatientController::class);
    
    // Visits Management
    Route::resource('visits', VisitController::class);
    
    // Inventory Management
    Route::resource('inventory', InventoryController::class);
    
    // Prescriptions Management
    Route::resource('prescriptions', PrescriptionController::class);
    Route::resource('prescriptions.items', PrescriptionItemController::class)->only(['store', 'destroy']);
    
    // Bills & Payment Management
    Route::resource('bills', BillController::class);
    Route::resource('bills.items', BillItemController::class)->only(['store', 'destroy']);
    Route::resource('bills.payments', BillPaymentController::class)->only(['store']);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
