<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use App\Models\SensorData;
use Illuminate\Http\Request;

class SensorDataController extends Controller
{
    // Menampilkan semua data sensor dalam format tampilan web (untuk keperluan tampilan web)
    public function indexWeb()
    {
        $sensorData = SensorData::with('sensor')
            ->orderBy('created_at', 'desc')
            ->paginate(50); // Menambahkan pagination
        $sensors = Sensor::all();

        return view('pages.sensordata', compact('sensorData', 'sensors'));
    }

    // Menampilkan semua data sensor dalam format JSON untuk API
    public function index()
    {
        $sensorData = SensorData::with('sensor')
            ->orderBy('created_at', 'desc')
            ->paginate(50); // Menambahkan pagination
        return response()->json($sensorData); // Kembalikan data dengan format pagination
    }


    public function store(Request $request)
    {
        // Validasi request
        $request->validate([
            'sensor_id' => 'required|exists:sensors,id',
            'value' => 'required|numeric',
        ]);

        // Simpan data sensor ke database
        $sensorData = SensorData::create([
            'sensor_id' => $request->sensor_id,
            'value' => $request->value,
        ]);

        return response()->json(['success' => 'Data sensor berhasil disimpan.', 'data' => $sensorData], 201);
    }



    // Menampilkan data sensor tertentu dalam format JSON
    public function show($id)
    {
        $sensorData = SensorData::findOrFail($id);
        return response()->json($sensorData);
    }

    // Memperbarui data sensor melalui API
    public function update(Request $request, $id)
    {
        $request->validate([
            'sensor_id' => 'required|exists:sensors,id',
            'value' => 'required|numeric',
        ]);

        $sensorData = SensorData::findOrFail($id);
        $sensorData->update([
            'sensor_id' => $request->sensor_id,
            'value' => $request->value,
        ]);

        return response()->json(['success' => 'Data sensor berhasil diperbarui.', 'data' => $sensorData]);
    }

    public function destroy($id = null)
    {
        if ($id === 'all') {
            // Hapus semua data sensor
            SensorData::truncate(); // Menghapus semua data di tabel
            return response()->json(['success' => 'Semua data berhasil dihapus.']);
        } else {
            // Hapus data sensor berdasarkan ID
            $sensorData = SensorData::findOrFail($id);
            $sensorData->delete();
            return response()->json(['success' => 'Data berhasil dihapus.']);
        }
    }

}
