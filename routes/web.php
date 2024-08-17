<?php

use App\Http\Controllers\ActuatorController;
use App\Http\Controllers\HumidityController;
use App\Http\Controllers\IntensityController;
use App\Http\Controllers\MoistureController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\SensorDataController;
use App\Http\Controllers\TemperatureController;
use App\Http\Controllers\UsersController;
use App\Models\SensorData;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('pages.landing.landing');
});


Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rute untuk CRUD pengguna (users)
Route::middleware('auth')->resource('users', UsersController::class);

// Rute untuk CRUD data temperatur (temperatures)
Route::middleware('auth')->resource('temperatures', TemperatureController::class);

// Rute untuk CRUD data kelembaban (humidities)
Route::middleware('auth')->resource('humidities', HumidityController::class);

// Rute untuk CRUD data intensitas (intensities)
Route::middleware('auth')->resource('intensities', IntensityController::class);

// Rute untuk CRUD data kelembaban tanah (moistures)
Route::middleware('auth')->resource('moistures', MoistureController::class);

// Rute untuk CRUD data aktuator (actuators)
Route::middleware('auth')->resource('actuators', ActuatorController::class);
Route::put('/actuators/{id}/status', [ActuatorController::class, 'updateStatus']);

// Rute untuk CRUD data sensor (sensors)
Route::middleware('auth')->resource('sensors', SensorController::class);

// Rute untuk CRUD data sensor (sensors)
Route::middleware('auth')->resource('sensordata', SensorDataController::class);



require __DIR__.'/auth.php';
