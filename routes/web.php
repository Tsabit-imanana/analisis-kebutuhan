<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\WeeklyLogController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\PeriodeLaporanController;
use App\Http\Controllers\RoleManagementController;
use App\Http\Controllers\DivisiController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\SpvDashboardController;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    if (! auth()->check()) {
        return redirect()->route('login');
    }

    $role = auth()->user()->role;

    return match ($role) {
        'admin' => redirect()->route('admin.dashboard'),
        'spv' => redirect()->route('spv.dashboard'),
        'employee' => redirect()->route('employee.dashboard'),
        default => redirect()->route('login')->with('error', 'Role akun tidak dikenali.'),
    };
})->middleware('auth')->name('dashboard');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->middleware('admin')
        ->name('admin.dashboard');

    Route::get('/spv/dashboard', [SpvDashboardController::class, 'index'])
        ->name('spv.dashboard');

    Route::get('/employee/dashboard', function () {
        return view('employee.dashboard');
    })->name('employee.dashboard');

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
    });

    Route::prefix('tasks')->name('tasks.')->group(function () {
        Route::get('/', [TaskController::class, 'index'])->name('index');
        Route::post('/', [TaskController::class, 'addTask'])->name('store');
        Route::put('/{id}', [TaskController::class, 'editTask'])->name('update');
        Route::delete('/{id}', [TaskController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/start', [TaskController::class, 'startTask'])->name('start');
        Route::post('/{id}/submit', [TaskController::class, 'submitTask'])->name('submit');
        Route::post('/{id}/review', [TaskController::class, 'reviewTask'])->name('review');
    });

    Route::middleware('admin')->group(function () {
        Route::get('/role-management', [RoleManagementController::class, 'index'])->name('admin.users.index');
        Route::post('/role-management', [RoleManagementController::class, 'store'])->name('admin.users.store');
        Route::get('/role-management/{user}/edit', [RoleManagementController::class, 'edit'])->name('admin.users.edit');
        Route::put('/role-management/{user}', [RoleManagementController::class, 'update'])->name('admin.users.update');
        Route::delete('/role-management/{user}', [RoleManagementController::class, 'destroy'])->name('admin.users.destroy');

        Route::prefix('weekly_log')->name('weekly_log.')->group(function () {
            Route::get('/', [WeeklyLogController::class, 'index'])->name('index');
            Route::post('/', [WeeklyLogController::class, 'store'])->name('store');
            Route::put('/{id}', [WeeklyLogController::class, 'update'])->name('update');
            Route::delete('/{id}', [WeeklyLogController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('document')->name('document.')->group(function () {
            Route::get('/', [DocumentController::class, 'index'])->name('index');
            Route::post('/', [DocumentController::class, 'store'])->name('store');
            Route::put('/{id}', [DocumentController::class, 'update'])->name('update');
            Route::delete('/{id}', [DocumentController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('finance')->name('finance.')->group(function () {
            Route::get('/', [FinanceController::class, 'index'])->name('index');
            Route::get('/{id}', [FinanceController::class, 'show'])->name('show');
            Route::post('/budget', [FinanceController::class, 'storeBudget'])->name('budget.store');
            Route::post('/detail', [FinanceController::class, 'storeDetail'])->name('detail.store');
        });

        Route::post('/periode-laporan', [PeriodeLaporanController::class, 'store']);

        // Settings: Divisi CRUD
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [DivisiController::class, 'index'])->name('index');
            Route::post('/', [DivisiController::class, 'store'])->name('store');
            Route::put('/{divisi}', [DivisiController::class, 'update'])->name('update');
            Route::delete('/{divisi}', [DivisiController::class, 'destroy'])->name('destroy');
        });
    });

    Route::prefix('documents')->name('documents.')->group(function () {
        Route::get('/', [DocumentController::class, 'index'])->name('index');
        Route::get('/create', [DocumentController::class, 'create'])->name('create');
        Route::post('/', [DocumentController::class, 'store'])->name('store');
        Route::post('/{document}/submit', [DocumentController::class, 'submit'])->name('submit');
        Route::post('/{document}/approve', [DocumentController::class, 'approve'])->name('approve');
        Route::post('/{document}/reject', [DocumentController::class, 'reject'])->name('reject');
        Route::get('/{document}/download', [DocumentController::class, 'download'])->name('download');
    });
});