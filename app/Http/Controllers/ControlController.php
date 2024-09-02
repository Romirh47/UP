<?php

namespace App\Http\Controllers;

use App\Models\ActuatorValue;
use Illuminate\Http\Request;
use App\Models\Actuator;

class ControlController extends Controller
{
    // Menampilkan semua kontrol dalam format tampilan web
    public function index()
    {
        // Ambil semua aktuator
        $actuators = Actuator::all();

        // Ambil nilai terbaru dari setiap aktuator dari tabel actuator_values
        // Urutkan data berdasarkan waktu pembuatan terbaru
        $actuatorValues = ActuatorValue::orderBy('created_at', 'desc')
            ->get()
            ->unique('actuator_id'); // Ambil hanya nilai terbaru untuk setiap aktuator

        // Mengurutkan hasil berdasarkan waktu pembuatan terbaru
        $actuatorValues = $actuatorValues->sortByDesc('created_at');

        return view('pages.controls', compact('actuators', 'actuatorValues'));
    }

    // Memperbarui kontrol dan menambahkan nilai baru di actuator_values
    public function update(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:on,off',
        ]);

        // Konversi action 'on' ke 1 dan 'off' ke 0
        $value = $request->action === 'on' ? 1 : 0;

        // Simpan nilai baru untuk aktuator di tabel actuator_values
        ActuatorValue::create([
            'actuator_id' => $id,
            'value' => $value,
        ]);

        return response()->json(['success' => 'Status aktuator berhasil diperbarui!']);
    }
}
