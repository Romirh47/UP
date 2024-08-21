<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use Illuminate\Http\Request;

class SensorController extends Controller
{
    // Mengembalikan data sensor untuk tampilan web dan API
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Untuk AJAX request, mengembalikan data dalam format JSON
            $sensors = Sensor::orderBy('created_at', 'desc')->paginate(5); // Mengurutkan data terbaru di atas
            return response()->json($sensors);
        }
        // Untuk non-AJAX request, mengembalikan tampilan dengan data
        $sensors = Sensor::orderBy('created_at', 'desc')->paginate(5); // Mengambil data sensor dengan urutan terbaru di atas
        return view('pages.sensors', compact('sensors')); // Kirimkan data ke tampilan
    }

    // Menyimpan data sensor
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:sensors,name',
            'type' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $sensor = Sensor::create($request->all());

        // Mengembalikan respons JSON untuk AJAX
        return response()->json(['success' => 'Sensor berhasil ditambahkan.', 'data' => $sensor]);
    }

    // Memperbarui data sensor
    public function update(Request $request, Sensor $sensor)
    {
        $request->validate([
            'name' => 'required|string|unique:sensors,name,' . $sensor->id,
            'type' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $sensor->update($request->only(['name', 'type', 'description']));

        // Mengembalikan respons JSON untuk AJAX
        return response()->json(['success' => 'Sensor berhasil diperbarui.', 'data' => $sensor]);
    }

    // Menghapus data sensor
    public function destroy(Sensor $sensor)
    {
        $sensor->delete();

        // Mengembalikan respons JSON untuk AJAX
        return response()->json(['success' => 'Sensor berhasil dihapus.']);
    }
}
