<?php

use App\Http\Controllers\ActuatorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HumidityController;
use App\Http\Controllers\IntensityController;
use App\Http\Controllers\MoisturesController;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\SensorDataController;
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


// Route::apiResource('users', UsersController::class);


// Route::resource('dahboard', DashboardController::class)->names([
//     'index' => 'dahboard.index',
//     'create' => 'dahboard.create',
//     'store' => 'dahboard.store',
//     'show' => 'dahboard.show',
//     'edit' => 'dahboard.edit',
//     'update' => 'dahboard.update',
//     'destroy' => 'dahboard.destroy'
// ]);

// Route untuk model users
Route::resource('users', UsersController::class)->names([
    'index' => 'users.index',
    'create' => 'users.create',
    'store' => 'users.store',
    'show' => 'users.show',
    'edit' => 'users.edit',
    'update' => 'users.update',
    'destroy' => 'users.destroy'
]);


// Route untuk model Temperature
Route::resource('temperature', TemperatureController::class)->names([
    'index' => 'temperature.index',
    'create' => 'temperature.create',
    'store' => 'temperature.store',
    'show' => 'temperature.show',
    'edit' => 'temperature.edit',
    'update' => 'temperature.update',
    'destroy' => 'temperature.destroy'
]);

// Route untuk model Humidity
Route::resource('humidity', HumidityController::class)->names([
    'index' => 'humidity.index',
    'create' => 'humidity.create',
    'store' => 'humidity.store',
    'show' => 'humidity.show',
    'edit' => 'humidity.edit',
    'update' => 'humidity.update',
    'destroy' => 'humidity.destroy'
]);

// Route untuk model Intensity
Route::resource('intensity', IntensityController::class)->names([
    'index' => 'intensity.index',
    'create' => 'intensity.create',
    'store' => 'intensity.store',
    'show' => 'intensity.show',
    'edit' => 'intensity.edit',
    'update' => 'intensity.update',
    'destroy' => 'intensity.destroy'
]);

// Route untuk model Moistures
Route::resource('moistures', MoisturesController::class)->names([
    'index' => 'moistures.index',
    'create' => 'moistures.create',
    'store' => 'moistures.store',
    'show' => 'moistures.show',
    'edit' => 'moistures.edit',
    'update' => 'moistures.update',
    'destroy' => 'moistures.destroy'
]);

// Route untuk model Aktuator
Route::resource('actuators', ActuatorController::class)->names([
    'index' => 'actuators.index',
    'create' => 'actuators.create',
    'store' => 'actuators.store',
    'show' => 'actuators.show',
    'edit' => 'actuators.edit',
    'update' => 'actuators.update',
    'destroy' => 'actuators.destroy'
]);

// Route untuk model sensors
Route::resource('sensors', SensorController::class)->names([
    'index' => 'sensors.index',
    'create' => 'sensors.create',
    'store' => 'sensors.store',
    'show' => 'sensors.show',
    'edit' => 'sensors.edit',
    'update' => 'sensors.update',
    'destroy' => 'sensors.destroy'
]);

// Route untuk model sensors
Route::resource('sensorsdata', SensorDataController::class)->names([
    'index' => 'sensorsdata.index',
    'create' => 'sensorsdata.create',
    'store' => 'sensorsdata.store',
    'show' => 'sensorsdata.show',
    'edit' => 'sensorsdata.edit',
    'update' => 'sensorsdata.update',
    'destroy' => 'sensorsdata.destroy'
]);

// // Contoh menggunakan Laravel
// Route::get('/api/temperatures', function () {
//     // Ambil data dari tabel sensors
//     $sensors = DB::table('temperatures')->select('value')->get();

//     // Mengembalikan data dalam format JSON
//     return response()->json($sensors);
// });



// // Contoh menggunakan Laravel
// Route::get('/api/humidities', function () {
//     // Ambil data dari tabel sensors
//     $sensors = DB::table('humidities')->select('value')->get();

//     // Mengembalikan data dalam format JSON
//     return response()->json($sensors);
// });

// // Contoh menggunakan Laravel
// Route::get('/api/moistures', function () {
//     // Ambil data dari tabel sensors
//     $sensors = DB::table('moistures')->select('value')->get();

//     // Mengembalikan data dalam format JSON
//     return response()->json($sensors);
// });

// // Contoh menggunakan Laravel
// Route::get('/api/intensities', function () {
//     // Ambil data dari tabel sensors
//     $sensors = DB::table('intensities')->select('value')->get();

//     // Mengembalikan data dalam format JSON
//     return response()->json($sensors);
// });

