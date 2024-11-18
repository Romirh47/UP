<?php

namespace App\Http\Controllers\Api;

use App\Models\Report;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class ReportApiController extends Controller
{
    /**
     * Menampilkan daftar laporan dengan pagination.
     */
    public function index(Request $request)
    {
        $reports = Report::orderBy('created_at', 'desc')->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $reports,
            'pagination' => [
                'total' => $reports->total(),
                'per_page' => $reports->perPage(),
                'current_page' => $reports->currentPage(),
                'last_page' => $reports->lastPage(),
                'next_page_url' => $reports->nextPageUrl(),
                'prev_page_url' => $reports->previousPageUrl(),
            ],
        ], 200);
    }

    /**
     * Menampilkan detail laporan berdasarkan ID.
     */
    public function show($id, Request $request)
    {
        $report = Report::find($id);

        if (!$report) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $report,
        ], 200);
    }

    /**
     * Menyimpan laporan baru.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'jenis_kejadian' => 'required|string|max:255',
            'foto_kejadian' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $fotoKejadianPath = null;

        if ($request->hasFile('foto_kejadian') && $request->file('foto_kejadian')->isValid()) {
            $file = $request->file('foto_kejadian');
            $fotoKejadianName = time() . '.' . $file->getClientOriginalExtension();

            // Simpan file ke folder storage/public/kejadian
            $fotoKejadianPath = $file->storeAs('public/kejadian', $fotoKejadianName);

            // Hanya menyimpan path relatif ke storage, tanpa 'public/'
            $fotoKejadianPath = 'kejadian/' . $fotoKejadianName;
        }

        $report = Report::create([
            'jenis_kejadian' => $validatedData['jenis_kejadian'],
            'foto_kejadian' => $fotoKejadianPath,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil disimpan!',
            'data' => $report,
        ], 201);
    }

    /**
     * Menghapus laporan berdasarkan ID.
     */
    public function destroy($id, Request $request)
    {
        $report = Report::find($id);

        if (!$report) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan tidak ditemukan.',
            ], 404);
        }

        // Perbaikan pada pengecekan path file
        if ($report->foto_kejadian && Storage::exists('public/' . $report->foto_kejadian)) {
            $deleted = Storage::delete('public/' . $report->foto_kejadian);
            if (!$deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Foto kejadian gagal dihapus.',
                ], 500);
            }
        }

        $report->delete();

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil dihapus!',
        ], 200);
    }

    public function deleteAll()
    {
        try {
            $deleted = Report::truncate(); // Menghapus semua laporan

            return response()->json([
                'success' => true,
                'message' => 'Semua laporan berhasil dihapus.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan tidak ditemukan atau terjadi kesalahan.'
            ], 404);
        }
    }
}
