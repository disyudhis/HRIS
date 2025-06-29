<?php

use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\AttendanceListController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\ManagerMiddleware;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\employee\SppdController;
use App\Http\Controllers\employee\OvertimeController;
use App\Http\Controllers\Manager\Schedule\ScheduleController;

// Redirect to login page
Route::redirect('/', '/login');

// Authenticated routes
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    // Dashboard
    Route::redirect('/', '/check-in')->name('dashboard');
    Route::get('/check-in', [DashboardController::class, 'checkIn'])->name('dashboard.check-in');
    Route::get('/schedules', [DashboardController::class, 'schedules'])->name('employee.schedules.index');

    // Profile routes
    Route::controller(ProfileController::class)
        ->prefix('profile')
        ->name('profile.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/edit', 'edit')->name('edit');
            Route::get('/change-password', 'changePassword')->name('change-password');
        });

    // Employee approval routes
    Route::prefix('approvals')
        ->name('employee.approvals.')
        ->group(function () {
            // Overtime routes
            Route::controller(OvertimeController::class)
                ->prefix('overtimes')
                ->name('overtime.')
                ->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::get('/create', 'create')->name('create');
                    Route::get('/edit/{overtime}', 'edit')->name('edit');
                    Route::get('/show/{overtime}', 'show')->name('show');
                });

            // Business trips routes
            Route::controller(SppdController::class)
                ->prefix('business-trips')
                ->name('business-trips.')
                ->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::get('/create', 'create')->name('create');
                    Route::get('/edit/{sppd}', 'edit')->name('edit');
                    Route::get('/show/{sppd}', 'show')->name('show');
                });
        });

    // Admin routes
    Route::middleware(AdminMiddleware::class)
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            // Office management
            Route::prefix('offices')
                ->name('offices.')
                ->group(function () {
                    Route::get('/', [OfficeController::class, 'index'])->name('index');
                    Route::get('/create', [OfficeController::class, 'create'])->name('create');
                    Route::get('/edit/{office}', [OfficeController::class, 'edit'])->name('edit');
                });

            // User management
            Route::prefix('users')
                ->name('users.')
                ->group(function () {
                    Route::get('/', [UserController::class, 'index'])->name('index');
                    Route::get('/create', [UserController::class, 'create'])->name('create');
                    Route::get('/edit/{user}', [UserController::class, 'edit'])->name('edit');
                });
        });

    // Manager routes
    Route::middleware(ManagerMiddleware::class)
        ->prefix('manager')
        ->name('manager.')
        ->group(function () {
            Route::prefix('attendance')
                ->name('attendance.')
                ->group(function () {
                    Route::get('/', [AttendanceListController::class, 'index'])->name('index');
                });
            // Manager approval routes
            Route::prefix('approvals')
                ->name('approvals.')
                ->group(function () {
                    Route::get('/overtime', [ApprovalController::class, 'overtime'])->name('overtime.index');
                    Route::get('/business-trips', [ApprovalController::class, 'businessTrips'])->name('business-trips.index');
                });

            // Schedule management
            Route::controller(ScheduleController::class)
                ->prefix('schedules')
                ->name('schedules.')
                ->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::get('/create', 'create')->name('create');
                    Route::get('/{schedule}/edit', 'edit')->name('edit');
                });
        });
});
