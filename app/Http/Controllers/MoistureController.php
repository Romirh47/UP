<?php

namespace App\Http\Controllers;

use App\Models\Moisture;
use Illuminate\Http\Request;

class MoistureController extends Controller
{
    public function index()
    {
        $moistures = Moisture::all();
        return view('pages.moistures.moistures', compact('moistures'));
    }

    public function store(Request $request)
    {
        // Validasi input menggunakan metode validate()
        $request->validate([
            'value' => 'required|numeric', // Memastikan nilai harus diisi dan berupa angka
        ]);

        // Simpan nilai kelembapan ke database
        Moisture::create([
            'value' => $request->value,
        ]);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('moistures.index')->with('success', 'Nilai kelembapan berhasil disimpan.');
    }

    public function destroy($id)
    {
        // Temukan data kelembapan berdasarkan ID
        $moisture = Moisture::findOrFail($id);

        // Hapus data kelembapan
        $moisture->delete();

        // Response untuk permintaan delete yang berhasil
        return response()->json(['success' => 'Data kelembapan berhasil dihapus.']);
    }

    public function edit($id)
    {
        // Temukan data kelembapan berdasarkan ID
        $moisture = Moisture::findOrFail($id);

        // Tampilkan form edit dengan data kelembapan yang ditemukan
        return view('pages.moistures.edit', compact('moisture'));
    }

    public function update(Request $request, $id)
    {
        // Validasi data yang diterima dari request
        $request->validate([
            'value' => 'required|numeric',
        ]);

        // Temukan data kelembapan berdasarkan ID
        $moisture = Moisture::findOrFail($id);

        // Update data kelembapan dengan data baru
        $moisture->update([
            'value' => $request->value,
            // Tambahkan kolom lain yang ingin diupdate
        ]);

        // Redirect ke halaman sebelumnya dengan pesan sukses
        return redirect()->route('moistures.index')->with('success', 'Data kelembapan berhasil diperbarui');
    }
}

