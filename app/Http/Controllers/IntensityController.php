<?php

namespace App\Http\Controllers;

use App\Models\Intensity;
use Illuminate\Http\Request;

class IntensityController extends Controller
{
    public function index()
    {
        // Ambil semua data intensitas dari model Intensity
        $intensities = Intensity::all();

        // Kirim data intensitas ke view 'pages.intensities.intensities'
        return view('pages.intensities.intensities', compact('intensities'));
    }

    public function store(Request $request)
    {
        // Validasi input menggunakan metode validate()
        $request->validate([
            'value' => 'required|numeric', // Memastikan nilai harus diisi dan berupa angka
        ]);

        // Simpan nilai intensitas ke database
        $intensity = Intensity::create([
            'value' => $request->value,
        ]);

        // Kembalikan respons JSON berisi data intensitas yang baru dibuat
        return response()->json([
            'success' => 'Nilai intensitas berhasil disimpan.',
            'data' => $intensity, // Mengirimkan data intensitas yang baru dibuat
        ]);
    }

    public function destroy($id)
    {
        // Temukan data intensitas berdasarkan ID
        $intensity = Intensity::findOrFail($id);

        // Hapus data intensitas
        $intensity->delete();

        // Kembalikan respons JSON untuk memberi tahu bahwa data berhasil dihapus
        return response()->json(['success' => 'Data intensitas berhasil dihapus.']);
    }

    public function edit($id)
    {
        // Temukan data intensitas berdasarkan ID
        $intensity = Intensity::findOrFail($id);

        // Kembalikan data intensitas sebagai respons JSON untuk AJAX
        return response()->json($intensity);
    }

    public function update(Request $request, $id)
    {
        // Validasi input menggunakan metode validate()
        $request->validate([
            'value' => 'required|numeric', // Memastikan nilai harus diisi dan berupa angka
        ]);

        // Temukan data intensitas berdasarkan ID
        $intensity = Intensity::findOrFail($id);

        // Update data intensitas dengan data baru
        $intensity->update([
            'value' => $request->value,
        ]);

        // Kembalikan respons JSON untuk memberi tahu bahwa data berhasil diperbarui
        return response()->json([
            'success' => 'Data intensitas berhasil diperbarui.',
            'data' => $intensity, // Mengirimkan data intensitas yang telah diperbarui
        ]);
    }
}
