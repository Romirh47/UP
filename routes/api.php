<?php

use App\Http\Controllers\ActuatorController;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\SensorDataController;
use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
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

// Menghapus middleware auth:sanctum agar API bisa diakses tanpa login
// Jika Anda tidak memerlukan middleware auth:sanctum, Anda bisa menghapus baris ini atau memodifikasinya sesuai kebutuhan
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route untuk model users
Route::resource('users', UsersController::class)->names([
    'index' => 'api.users.index',
    'create' => 'api.users.create',
    'store' => 'api.users.store',
    'show' => 'api.users.show',
    'edit' => 'api.users.edit',
    'update' => 'api.users.update',
    'destroy' => 'api.users.destroy',
]);

// Route untuk model actuators
Route::resource('actuators', ActuatorController::class)->names([
    'index' => 'api.actuators.index',
    'create' => 'api.actuators.create',
    'store' => 'api.actuators.store',
    'show' => 'api.actuators.show',
    'edit' => 'api.actuators.edit',
    'update' => 'api.actuators.update',
    'destroy' => 'api.actuators.destroy',
]);

// Route untuk model sensors
Route::resource('sensors', SensorController::class)->names([
    'index' => 'api.sensors.index',
    'create' => 'api.sensors.create',
    'store' => 'api.sensors.store',
    'show' => 'api.sensors.show',
    'edit' => 'api.sensors.edit',
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
