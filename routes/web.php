<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\WeeklyLogController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\PeriodeLaporanController;

Route::get('/', function () {
    return view('admin.dashboard');
});

Route::prefix('tasks')->name('tasks.')->group(function () {
    Route::get('/', [TaskController::class, 'index'])->name('index');
    Route::post('/', [TaskController::class, 'addTask'])->name('store');
    Route::put('/{id}', [TaskController::class, 'editTask'])->name('update');
    Route::delete('/{id}', [TaskController::class, 'destroy'])->name('destroy');
});

Route::prefix('weekly_log')->name('weekly_log.')->group(function () {
    Route::get('/', [WeeklyLogController::class, 'index'])->name('index');
    Route::post('/', [WeeklyLogController::class, 'store'])->name('store');
    Route::put('/{id}', [WeeklyLogController::class, 'update'])->name('update');
    Route::delete('/{id}', [WeeklyLogController::class, 'destroy'])->name('destroy');
});

Route::prefix('finance')->name('finance.')->group(function () {
    Route::get('/', [FinanceController::class, 'index'])->name('index');
    Route::get('/{id}', [FinanceController::class, 'show'])->name('show');
    Route::post('/budget', [FinanceController::class, 'storeBudget'])->name('budget.store');
    Route::post('/detail', [FinanceController::class, 'storeDetail'])->name('detail.store');
});

Route::post('/periode-laporan', [PeriodeLaporanController::class, 'store']);