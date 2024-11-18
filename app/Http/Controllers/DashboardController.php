<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Hanya merender view tanpa data
        return view('dashboard');
    }
}
