<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class DashboardApiController extends Controller
{
    public function dashboardData()
    {
        try {
            // Total laporan
            $totalReports = Report::count();
            Log::info('Total Reports: ' . $totalReports);

            // Total pengguna, admin, dan user
            $totalAdmins = User::where('role', 'admin')->count();
            $totalUsers = User::where('role', 'user')->count();
            Log::info('Total Admins: ' . $totalAdmins . ', Total Users: ' . $totalUsers);

            // Distribusi jenis kejadian
            $jenisKejadianCount = Report::selectRaw('jenis_kejadian, COUNT(*) as count')
                ->groupBy('jenis_kejadian')
                ->get();
            Log::info('Jenis Kejadian Count: ' . $jenisKejadianCount);

            // Laporan terbaru
            $latestReports = Report::latest()->take(5)->get();
            Log::info('Latest Reports: ' . $latestReports);

            return response()->json([
                'totalReports' => $totalReports,
                'totalAdmins' => $totalAdmins,
                'totalUsers' => $totalUsers,
                'chartData' => $jenisKejadianCount,
                'latestReports' => $latestReports,
            ]);
        } catch (\Exception $e) {
            Log::error('Dashboard API Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to fetch dashboard data.',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
