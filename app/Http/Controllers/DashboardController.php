<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User; // Pastikan model User di-import
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Total laporan
        $totalReports = Report::count();

        // Total pengguna, admin dan user
        $totalAdmins = User::where('role', 'admin')->count();
        $totalUsers = User::where('role', 'user')->count();

        // Distribusi jenis kejadian
        $jenisKejadianCount = Report::selectRaw('jenis_kejadian, COUNT(*) as count')
            ->groupBy('jenis_kejadian')
            ->get();

        // Laporan terbaru
        $latestReports = Report::latest()->take(5)->get();

        // Grafik laporan berdasarkan waktu (misalnya setiap bulan)
        $reportsByMonth = Report::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->get();

        // Pengguna terbaru
        $latestUser = User::latest()->first();

        // Kirim data ke view
        return view('dashboard', [
            'totalReports' => $totalReports,
            'totalAdmins' => $totalAdmins,
            'totalUsers' => $totalUsers,
            'chartData' => $jenisKejadianCount,
            'latestReports' => $latestReports,
            'reportsByMonth' => $reportsByMonth,
            'latestUser' => $latestUser,
        ]);
    }
}
