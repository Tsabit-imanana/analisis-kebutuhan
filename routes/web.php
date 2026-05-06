<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\WeeklyLogController;

Route::get('/', function () {
    return view('admin.dashboard');
});

Route::prefix('tasks')->name('tasks.')->group(function () {
    Route::get('/', [TaskController::class, 'index'])->name('index');
    Route::post('/', [TaskController::class, 'addTask'])->name('store');
    Route::put('/{id}', [TaskController::class, 'update'])->name('update');
    Route::delete('/{id}', [TaskController::class, 'destroy'])->name('destroy');
});
Route::prefix('weekly_log')->name('weekly_log.')->group(function () {
    Route::get('/', [WeeklyLogController::class, 'index'])->name('index');
    Route::post('/', [WeeklyLogController::class, 'store'])->name('store');
    Route::put('/{id}', [WeeklyLogController::class, 'update'])->name('update');
    Route::delete('/{id}', [WeeklyLogController::class, 'destroy'])->name('destroy');
});