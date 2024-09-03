<?php

namespace App\Http\Controllers;

use App\Models\ActuatorValue;
use App\Models\Actuator;
use Illuminate\Http\Request;

class ActuatorValueController extends Controller
{
    // Menampilkan halaman index dengan view
    public function index()
    {
        // Mengambil semua actuator dan mengirimnya ke view
        $actuators = Actuator::all();
        return view('pages.actuatorvalues', compact('actuators'));
    }

    // API untuk mendapatkan data actuator dengan nama actuator
    public function apiIndex()
    {
        try {
            $actuatorValues = ActuatorValue::with('actuator')->orderBy('created_at', 'desc')->paginate(50);
            return response()->json($actuatorValues);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan saat memuat data: ' . $e->getMessage()
            ], 500);
        }
    }

    // API untuk mendapatkan daftar nama actuator
    public function getActuators()
    {
        try {
            $actuators = Actuator::all(); // Mendapatkan semua actuator
            return response()->json($actuators);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan saat memuat daftar actuator: ' . $e->getMessage()
            ], 500);
        }
    }

    // Menyimpan data actuator baru
    public function store(Request $request)
    {
        $request->validate([
            'actuator_id' => 'required|exists:actuators,id', // Validasi ID actuator
            'value' => 'required|boolean', // Validasi nilai hanya boolean
        ]);

        try {
            $actuatorValue = ActuatorValue::create([
                'actuator_id' => $request->actuator_id,
                'value' => $request->value,
            ]);



            return response()->json([
                'success' => 'Data actuator berhasil disimpan.',
                'data' => $actuatorValue
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    // Menampilkan data actuator tertentu
    public function show($id)
    {
        try {
            $actuatorValue = ActuatorValue::with('actuator')->findOrFail($id);
            return response()->json($actuatorValue);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Data actuator tidak ditemukan atau terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    // Memperbarui data actuator
    public function update(Request $request, $id)
    {
        $request->validate([
            'actuator_id' => 'required|exists:actuators,id', // Validasi ID actuator
            'value' => 'required|boolean', // Validasi nilai hanya boolean
        ]);

        try {
            $actuatorValue = ActuatorValue::findOrFail($id);
            $actuatorValue->update([
                'actuator_id' => $request->actuator_id,
                'value' => $request->value,
            ]);



            return response()->json([
                'success' => 'Data actuator berhasil diperbarui.',
                'data' => $actuatorValue
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage()
            ], 500);
        }
    }

    // Menghapus data actuator berdasarkan ID
    public function destroy($id = null)
{
    if ($id === 'all') {
        // Hapus semua data actuator
        ActuatorValue::truncate(); // Menghapus semua data di tabel
        return response()->json(['success' => 'Semua data actuator berhasil dihapus.']);
    } else {
        // Hapus data actuator berdasarkan ID
        $actuatorValue = ActuatorValue::findOrFail($id);
        $actuatorValue->delete();
        return response()->json(['success' => 'Data actuator berhasil dihapus.']);
    }
}

}
