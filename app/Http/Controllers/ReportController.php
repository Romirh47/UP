<?php


namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // Menampilkan laporan dengan tampilan web
    public function index()
    {
        // Mengambil data laporan
        $reports = Report::all();  // Mengambil semua laporan

        // Mengirimkan data laporan ke view
        return view('pages.reports', compact('reports'));
    }

    // Menampilkan halaman laporan tertentu
    public function show(Report $report)
    {
        return view('reports.show', compact('report'));  // Pastikan yang dikirim 'report' bukan 'reports'
    }

    // Menghapus laporan
    public function destroy(Report $report)
    {
        $report->delete();
        return redirect()->route('web.reports.index')->with('success', 'Laporan berhasil dihapus.');
    }
}
