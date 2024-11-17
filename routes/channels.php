<?php

use Illuminate\Support\Facades\Broadcast;
use App\Events\ReportChanged;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// Channel untuk pengguna berdasarkan ID
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id; // Memastikan pengguna hanya bisa mendengarkan channel mereka sendiri
});

// Channel untuk laporan

Broadcast::channel('reports-channel', function ($user) {
    return true; // Membolehkan semua pengguna untuk mendengarkan channel ini, Anda bisa menambahkan logika otorisasi di sini jika diperlukan
});
