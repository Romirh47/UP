<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class DashboardApiController extends Controller
{
    // /**
    //  * Menyediakan data untuk dashboard.
    //  */
    // public function dashboardData()
    // {
    //     // Total laporan
    //     $totalReports = Report::count();

    //     // Distribusi jenis kejadian
    //     $jenisKejadianCount = Report::selectRaw('jenis_kejadian, COUNT(*) as count')
    //         ->groupBy('jenis_kejadian')
    //         ->get();

    //     // Data untuk distribusi chart
    //     $chartData = $jenisKejadianCount->map(function ($item) {
    //         return [
    //             'jenis' => $item->jenis_kejadian,
    //             'count' => $item->count,
    //         ];
    //     });

    //     // Kembalikan view dengan data
    //     return view('dashboard', [
    //         'totalReports' => $totalReports,
    //         'chartData' => $chartData,
    //     ]);
    // }
}
