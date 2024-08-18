<?php

use App\Http\Controllers\ActuatorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\SensorDataController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('pages.landing.landing');
})->name('web.landing');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('web.dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('web.profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('web.profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('web.profile.destroy');
});

// Rute untuk CRUD pengguna (users)
Route::middleware('auth')->resource('users', UsersController::class)->names([
    'index' => 'web.users.index',
    'create' => 'web.users.create',
    'store' => 'web.users.store',
    'show' => 'web.users.show',
    'edit' => 'web.users.edit',
    'update' => 'web.users.update',
    'destroy' => 'web.users.destroy',
]);

// Rute untuk CRUD data aktuator (actuators)
Route::resource('actuators', ActuatorController::class)->names([
    'index' => 'web.actuators.index',
    'create' => 'web.actuators.create',
    'store' => 'web.actuators.store',
    'show' => 'web.actuators.show',
    'edit' => 'web.actuators.edit',
    'update' => 'web.actuators.update',
    'destroy' => 'web.actuators.destroy',
]);

// Route::put('/actuators/{id}/status', [ActuatorController::class, 'updateStatus'])->name('web.actuators.updateStatus');

// Rute untuk CRUD data sensor (sensors)
Route::middleware('auth')->resource('sensors', SensorController::class)->names([
    'index' => 'web.sensors.index',
    'create' => 'web.sensors.create',
    'store' => 'web.sensors.store',
    'show' => 'web.sensors.show',
    'edit' => 'web.sensors.edit',
    'update' => 'web.sensors.update',
    'destroy' => 'web.sensors.destroy',
]);

// Rute untuk tampilan web data sensor (sensordata)
Route::middleware('auth')->get('/sensordata', [SensorDataController::class, 'indexWeb'])->name('web.sensordata.index');

// Route MQTT
// Route::get('/publish-sensor-data', [SensorController::class, 'publishSensorData'])->name('sensor.publishData');

require __DIR__.'/auth.php';
