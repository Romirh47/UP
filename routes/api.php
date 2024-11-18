<?php

use App\Http\Controllers\Api\DashboardApiController;
use App\Http\Controllers\Api\ReportApiController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

// Route API untuk Dashboard
Route::get('/dashboard', [DashboardApiController::class, 'dashboardData'])->name('api.dashboard.index');

// Route untuk model users
Route::apiResource('users', UsersController::class)->names([
    'index' => 'api.users.index',
    'store' => 'api.users.store',
    'show' => 'api.users.show',
    'update' => 'api.users.update',
    'destroy' => 'api.users.destroy',
]);

// Route untuk model reports
Route::apiResource('reports', ReportApiController::class)->names([
    'index' => 'api.reports.index',
    'store' => 'api.reports.store',
    'show' => 'api.reports.show',
    'update' => 'api.reports.update',
    'destroy' => 'api.reports.destroy',
]);

// Route semua laporan
Route::delete('/reports/deleteAll', [ReportApiController::class, 'deleteAll'])->name('api.reports.deleteAll');
