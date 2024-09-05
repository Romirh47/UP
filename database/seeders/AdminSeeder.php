<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Jalankan seeder.
     */
    public function run(): void
    {
        User::create([
            'name' => 'romi',
            'email' => 'romi@gmail.com',
            'password' => Hash::make('romipass'), // Ganti dengan password yang aman
            'role' => 'admin', // Tetapkan role admin
        ]);
    }
}
