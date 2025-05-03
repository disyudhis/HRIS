<?php

use App\Http\Controllers\employee\OvertimeController;
use App\Http\Controllers\Employee\SppdController;
use App\Http\Controllers\Manager\Schedule\ScheduleController;
use App\Http\Controllers\ProfileController;
use App\Models\User;
use App\Models\Offices;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\UserManagement;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\ManagerMiddleware;

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('dashboard.check-in');
    })->name('dashboard');
    Route::get('/check-in', function () {
        return view('dashboard.check-in');
    })->name('dashboard.check-in');
    Route::get('/schedules', function () {
        return view('employee.schedules.index');
    })->name('employee.schedules.index');

    Route::prefix('/approvals')
        ->name('employee.approvals.')
        ->group(function () {
            Route::prefix('/overtimes')
                ->name('overtime.')
                ->group(function () {
                    Route::get('/', [OvertimeController::class, 'index'])->name('index');
                    Route::get('/create', [OvertimeController::class, 'create'])->name('create');
                    Route::get('/edit/{overtime}', [OvertimeController::class, 'edit'])->name('edit');
                    Route::get('/show/{overtime}', [OvertimeController::class, 'show'])->name('show');
                });
            Route::prefix('/business-trips')
                ->name('business-trips.')
                ->group(function () {
                    Route::get('/', [SppdController::class, 'index'])->name('index');
                    Route::get('/create', [SppdController::class, 'create'])->name('create');
                    Route::get('/edit/{sppd}', [SppdController::class, 'edit'])->name('edit');
                    Route::get('/show/{sppd}', [SppdController::class, 'show'])->name('show');
                });
        });

    Route::prefix('profile')
        ->name('profile.')
        ->group(function () {
            Route::get('/', [ProfileController::class, 'index'])->name('index');
            Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
            Route::get('/change-password', [ProfileController::class, 'changePassword'])->name('change-password');
        });

    Route::middleware([AdminMiddleware::class])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::prefix('offices')
                ->name('offices.')
                ->group(function () {
                    Route::get('/', function () {
                        return view('admin.office.index');
                    })->name('index');
                    Route::get('/create', function () {
                        return view('admin.office.create');
                    })->name('create');
                    Route::get('/edit/{office}', function (Offices $office) {
                        return view('admin.office.edit', compact('office'));
                    })->name('edit');
                });
            Route::prefix('users')
                ->name('users.')
                ->group(function () {
                    Route::get('/', function () {
                        return view('admin.user.index');
                    })->name('index');
                    Route::get('/create', function () {
                        return view('admin.user.create');
                    })->name('create');
                    Route::get('/edit/{user}', function (User $user) {
                        return view('admin.user.edit', compact('user'));
                    })->name('edit');
                });
        });
    Route::middleware([ManagerMiddleware::class])
        ->prefix('manager')
        ->name('manager.')
        ->group(function () {
            Route::prefix('approvals')
                ->name('approvals.')
                ->group(function () {
                    Route::get('/overtime', function () {
                        return view('manager.overtime.index');
                    })->name('overtime.index');
                    Route::get('/business-trips', function () {
                        return view('manager.business-trips.index');
                    })->name('business-trips.index');
                });
            Route::prefix('schedules')
                ->name('schedules.')
                ->group(function () {
                    Route::get('/', [ScheduleController::class, 'index'])->name('index');
                    Route::get('/create', [ScheduleController::class, 'create'])->name('create');
                    Route::get('/{schedule}/edit', [ScheduleController::class, 'edit'])->name('edit');
                });
        });
});