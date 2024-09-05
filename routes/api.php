<?php

use App\Http\Controllers\ActuatorController;
use App\Http\Controllers\ActuatorValueController;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\SensorDataController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Di sini Anda dapat mendaftarkan route API untuk aplikasi Anda. Route
| ini akan dimuat oleh RouteServiceProvider dalam group yang
| berisi middleware "api".
|
*/

// Route untuk model users
Route::apiResource('users', UsersController::class)->names([
    'index' => 'api.users.index',
    'store' => 'api.users.store',
    'show' => 'api.users.show',
    'update' => 'api.users.update',
    'destroy' => 'api.users.destroy',
]);

// Route untuk model actuators
Route::prefix('actuators')->group(function () {
    Route::get('/', [ActuatorController::class, 'apiIndex'])->name('api.actuators.index');
    Route::post('/', [ActuatorController::class, 'apiStore'])->name('api.actuators.store');
    Route::get('/{id}', [ActuatorController::class, 'apiShow'])->name('api.actuators.show');
    Route::put('/{id}', [ActuatorController::class, 'apiUpdate'])->name('api.actuators.update');
    Route::delete('/{id}', [ActuatorController::class, 'apiDestroy'])->name('api.actuators.destroy');
});

// Routes untuk model actuator-values
Route::prefix('actuator-values')->group(function () {
    Route::get('/', [ActuatorValueController::class, 'apiIndex'])->name('api.actuator_values.index');
    Route::post('/', [ActuatorValueController::class, 'store'])->name('api.actuator_values.store');
    Route::get('/{id}', [ActuatorValueController::class, 'show'])->name('api.actuator_values.show');
    Route::put('/{id}', [ActuatorValueController::class, 'update'])->name('api.actuator_values.update');
    Route::delete('/{id}', [ActuatorValueController::class, 'destroy'])->name('api.actuator_values.destroy');
});

// Rute untuk API
Route::apiResource('sensors', SensorController::class)->names([
    'index' => 'api.sensors.index',
    'store' => 'api.sensors.store',
    'show' => 'api.sensors.show',
    'update' => 'api.sensors.update',
    'destroy' => 'api.sensors.destroy',
]);

// Route untuk model sensor data
Route::apiResource('sensordata', SensorDataController::class)->names([
    'index' => 'api.sensordata.index',
    'store' => 'api.sensordata.store',
    'show' => 'api.sensordata.show',
    'update' => 'api.sensordata.update',
    'destroy' => 'api.sensordata.destroy',
]);

// routes/api.php
Route::resource('settings', SettingController::class)->names([
    'index' => 'api.settings.index',
    'store' => 'api.settings.store',
    'show' => 'api.settings.show',
    'update' => 'api.settings.update',
    'destroy' => 'api.settings.destroy',
]);

// routes api dashboard
Route::resource('dashboard', DashboardController::class)->names([
    'index' => 'api.dashboard.index',
    'store' => 'api.dashboard.store',
    'show' => 'api.dashboard.show',
    'update' => 'api.dashboard.update',
    'destroy' => 'api.dashboard.destroy',
]);


   // Rute untuk CRUD controls
   Route::resource('controls', ControlController::class)->names([
    'index' => 'api.controls.index',
    'create' => 'api.controls.create',
    'store' => 'api.controls.store',
    'show' => 'api.controls.show',
    'edit' => 'api.controls.edit',
    'update' => 'api.controls.update',
    'destroy' => 'api.controls.destroy',
]);
