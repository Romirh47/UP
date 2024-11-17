<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        // Mengambil laporan dan menampilkan 10 per halaman
        $reports = Report::orderBy('created_at', 'desc')->paginate(10);
        return view('pages.reports', compact('reports'));
    }

    public function show($id)
    {
        // Menampilkan detail laporan berdasarkan ID
        $report = Report::find($id);

        if (!$report) {
            return redirect()->route('web.reports.index')->with('error', 'Report not found');
        }

        return view('reports.show', compact('report'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'jenis_kejadian' => 'required|string|max:255',
            'foto_kejadian' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Menangani upload foto kejadian
        $fotoKejadianPath = null;
        if ($request->hasFile('foto_kejadian')) {
            $fotoKejadianName = time() . '.' . $request->foto_kejadian->extension();
            $request->foto_kejadian->move(public_path('storage/foto_kejadian'), $fotoKejadianName);
            $fotoKejadianPath = 'storage/foto_kejadian/' . $fotoKejadianName;
        }

        // Menyimpan data laporan
        $report = Report::create([
            'jenis_kejadian' => $validatedData['jenis_kejadian'],
            'foto_kejadian' => $fotoKejadianPath,
        ]);

        return redirect()->route('web.reports.index')->with('success', 'Laporan berhasil disimpan!');
    }

    public function destroy($id)
    {
        // Menghapus laporan berdasarkan ID
        $report = Report::find($id);

        if (!$report) {
            return redirect()->route('web.reports.index')->with('error', 'Report not found');
        }

        // Hapus file foto kejadian jika ada
        if ($report->foto_kejadian && file_exists(public_path($report->foto_kejadian))) {
            unlink(public_path($report->foto_kejadian));
        }

        // Hapus laporan dari database
        $report->delete();

        return redirect()->route('web.reports.index')->with('success', 'Laporan berhasil dihapus!');
    }
}
