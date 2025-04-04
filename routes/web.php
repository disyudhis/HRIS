<?php

use App\Http\Middleware\AdminMiddleware;
use App\Livewire\Admin\UserManagement;
use App\Models\Offices;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/dashboard/check-in', function () {
        return view('dashboard.check-in');
    })->name('dashboard.check-in');

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
            Route::prefix('users')->name('users.')->group(function(){
                Route::get('/', function() {
                    return view('admin.user.index');
                })->name('index');
                Route::get('/create', function() {
                    return view('admin.user.create');
                })->name('create');
                Route::get('/edit/{user}', function(User $user) {
                    return view('admin.user.edit', compact('user'));
                })->name('edit');
            });
        });
});