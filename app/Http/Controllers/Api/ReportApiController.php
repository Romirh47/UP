<?php

namespace App\Http\Controllers\Api;

use App\Models\Report;
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

    /**
     * Menghapus semua laporan.
     */
    // public function destroyAll(): JsonResponse
    // {
    //     try {
    //         Log::debug('Memulai penghapusan semua laporan...');

    //         // Menghapus semua laporan
    //         $reports = Report::all();
    //         Log::debug('Laporan yang ditemukan untuk dihapus:', $reports->toArray()); // Log data laporan yang ditemukan

    //         foreach ($reports as $report) {
    //             Log::debug('Memeriksa foto untuk laporan ID ' . $report->id);

    //             // Periksa dan hapus foto kejadian
    //             if ($report->foto_kejadian && Storage::exists('public/' . $report->foto_kejadian)) {
    //                 $deleted = Storage::delete('public/' . $report->foto_kejadian);
    //                 Log::debug('Hapus foto kejadian untuk laporan ID ' . $report->id . ' - Status: ' . ($deleted ? 'Sukses' : 'Gagal'));
    //                 if (!$deleted) {
    //                     return response()->json([
    //                         'success' => false,
    //                         'message' => 'Beberapa foto kejadian gagal dihapus',
    //                     ], 500);
    //                 }
    //             }
    //         }

    //         // Truncate tabel laporan setelah menghapus foto kejadian
    //         Report::query()->delete(); // Menghapus semua data laporan tanpa men-truncate ID
    //         Log::debug('Semua laporan berhasil dihapus.');

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Semua laporan berhasil dihapus!',
    //         ]);
    //     } catch (\Exception $e) {
    //         Log::error('Terjadi kesalahan dalam penghapusan semua laporan: ' . $e->getMessage());
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
    //         ], 500);
    //     }
    // }
}
