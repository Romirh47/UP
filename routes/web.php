<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('pages.landing.landing');
})->name('web.landing');

// Rute untuk halaman dashboard
Route::middleware(['auth', 'verified'])->get('/dashboard', [DashboardController::class, 'index'])->name('web.dashboard.index');


// Rute profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('web.profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('web.profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('web.profile.destroy');
});

// Rute untuk CRUD pengguna (users) hanya untuk admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('users', UsersController::class)->names([
        'index' => 'web.users.index',
        'create' => 'web.users.create',
        'store' => 'web.users.store',
        'show' => 'web.users.show',
        'edit' => 'web.users.edit',
        'update' => 'web.users.update',
        'destroy' => 'web.users.destroy',
    ]);
});



// Rute untuk CRUD laporan reports
Route::middleware('auth')->resource('reports', ReportController::class)->names([
    'index' => 'web.reports.index',
    'create' => 'web.reports.create',
    'store' => 'web.reports.store',
    'show' => 'web.reports.show',
    'edit' => 'web.reports.edit',
    'update' => 'web.reports.update',
    'destroy' => 'web.reports.destroy',
]);


require __DIR__ . '/auth.php';
