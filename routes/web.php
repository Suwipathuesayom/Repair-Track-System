<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RepairController;
use App\Http\Controllers\RepairRequestController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {

    Route::get('/repair/create', [RepairRequestController::class, 'create'])->name('repair.create');
    Route::post('/repair', [RepairRequestController::class, 'store'])->name('repair.store');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/repair/{id}/attachment', [RepairRequestController::class, 'addAttachment'])->name('repair.attachment');
    Route::get('/repair/{id}/edit', [RepairRequestController::class, 'edit'])->name('repair.edit');
    Route::put('/repair/{id}', [RepairRequestController::class, 'update'])->name('repair.update');
    Route::post('/repair/{id}/cancel', [RepairRequestController::class, 'cancel'])->name('repair.cancel');
    Route::get('/repair/history', [RepairRequestController::class, 'history'])->name('repair.history');
    Route::post('/repair/{id}/update-status', [RepairRequestController::class, 'updateStatus'])->name('repair.update_status');
    Route::post('/repair/{id}/log-action', [RepairRequestController::class, 'logAction'])->name('repair.log_action');
    Route::post('/repair/{id}/senior-action', [RepairRequestController::class, 'seniorAction'])->name('repair.senior_action');
    Route::post('/repair/{id}/manager-action', [RepairRequestController::class, 'managerAction'])->name('repair.manager_action');
    Route::get('/repair/{id}/report', [ReportController::class, 'report'])->name('report.report');
    Route::get('/repair/{id}/report/pdf', [ReportController::class, 'exportPdf'])->name('report.export_pdf');
    Route::post('/repair/{id}/set-urgency', [RepairRequestController::class, 'setUrgency'])->name('repair.set_urgency');
    Route::post('/repair/{id}/parts-request', [RepairRequestController::class, 'storePartsRequest'])->name('repair.parts_request');

    Route::resource('repair', RepairRequestController::class);
});

Route::get('/', function () {
    return redirect()->route(auth()->check() ? 'dashboard' : 'login');
});
