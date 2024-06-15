<?php

use App\Http\Controllers\AktuatorController;
use App\Http\Controllers\HumidityController;
use App\Http\Controllers\IntensityController;
use App\Http\Controllers\MoisturesController;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\TemperatureController;
use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::apiResource('users', UsersController::class);


// Route untuk model Temperature
Route::resource('temperature', TemperatureController::class);

// Route untuk model Humidity
Route::resource('humidity', HumidityController::class);

// Route untuk model Intensity
Route::resource('intensity', IntensityController::class);

// Route untuk model Moistures
Route::resource('moistures', MoisturesController::class);

// Route untuk model Aktuator
Route::resource('aktuator', AktuatorController::class);

// Route untuk model sensors
Route::resource('sensors', SensorController::class);
