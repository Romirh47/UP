<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\Api\ReportApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
// API Routes
|--------------------------------------------------------------------------
// Di sini Anda dapat mendaftarkan route API untuk aplikasi Anda.
// Route ini akan dimuat oleh RouteServiceProvider dalam group yang
| berisi middleware "api".
*/

// Route untuk model dashboard
Route::get('/dashboard', [DashboardController::class, 'getData'])->name('api.dashboard.index');

// Route untuk model users
Route::apiResource('users', UsersController::class)->names([
    'index' => 'api.users.index',
    'store' => 'api.users.store',
    'show' => 'api.users.show',
    'update' => 'api.users.update',
    'destroy' => 'api.users.destroy',
]);

Route::apiResource('reports', ReportApiController::class)->names([
    'index' => 'api.reports.index',
    'store' => 'api.reports.store',
    'show' => 'api.reports.show',
    'update' => 'api.reports.update',
    'destroy' => 'api.reports.destroy',
]);

// Route semua laporan
Route::delete('reports/destroyAll', [ReportApiController::class, 'destroyAll'])->name('api.reports.destroyAll');

