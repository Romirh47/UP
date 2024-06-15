<?php

namespace App\Http\Controllers;

use App\Models\Temperature;
use Illuminate\Http\Request;

class TemperatureController extends Controller
{
    public function index()
    {
        // Ambil semua data temperatur dari model Temperature
        $temperatures = Temperature::all();

        // Kirim data temperatur ke view 'temperatures.temperatures'
        return view('pages.temperatures.temperatures', compact('temperatures'));
    }

    public function store(Request $request)
    {
        // Validasi input menggunakan metode validate()
        $request->validate([
            'value' => 'required|numeric', // Memastikan nilai harus diisi dan berupa angka
        ]);

        // Simpan nilai temperatur ke database
        Temperature::create([
            'value' => $request->value,
        ]);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('temperatures.index')->with('success', 'Nilai temperatur berhasil disimpan.');
    }

    public function destroy($id)
    {
        // Temukan data temperatur berdasarkan ID
        $temperature = Temperature::findOrFail($id);

        // Hapus data temperatur
        $temperature->delete();

        // Redirect ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Data Temperatur berhasil dihapus');
    }

    public function edit($id)
    {
        // Temukan data temperatur berdasarkan ID
        $temperature = Temperature::findOrFail($id);

        // Tampilkan form edit dengan data temperatur yang ditemukan
        return view('pages.temperatures.edit', compact('temperature'));
    }

    /**
     * Mengupdate data temperatur berdasarkan ID.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validasi data yang diterima dari request
        $request->validate([
            'value' => 'required|numeric',
        ]);

        // Temukan data temperatur berdasarkan ID
        $temperature = Temperature::findOrFail($id);

        // Update data temperatur dengan data baru
        $temperature->update([
            'value' => $request->value,
            // Tambahkan kolom lain yang ingin diupdate
        ]);

        // Redirect ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Data Temperatur berhasil diperbarui');
    }
}
