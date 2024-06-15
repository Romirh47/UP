<?php

use App\Http\Controllers\AktuatorController;
use App\Http\Controllers\HumidityController;
use App\Http\Controllers\IntensityController;
use App\Http\Controllers\MoisturesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\TemperatureController;
use App\Http\Controllers\UsersController;
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

Route::get('/dashboard', function () {
    return view('pages.dashboard.index');
})->middleware(['auth', 'verified'])->name('dashboard');

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
Route::middleware('auth')->resource('moistures', MoisturesController::class);

// Rute untuk CRUD data aktuator (actuators)
Route::middleware('auth')->resource('actuators', AktuatorController::class);

// Rute untuk CRUD data sensor (sensors)
Route::middleware('auth')->resource('sensors', SensorController::class);


require __DIR__.'/auth.php';
