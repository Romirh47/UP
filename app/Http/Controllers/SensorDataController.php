<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use App\Models\SensorData;
use Illuminate\Http\Request;

class SensorDataController extends Controller
{
    // Menampilkan semua data sensor dalam format tampilan web
    public function indexWeb()
    {
        $sensorData = SensorData::all(); // Ambil semua data sensor
        $sensors = Sensor::all(); // Ambil semua sensor

        return view('pages.sensordata', compact('sensorData', 'sensors'));
    }

    // Menampilkan semua data sensor dalam format JSON untuk API
    public function indexApi()
    {
        $sensorData = SensorData::all(); // Ambil semua data sensor
        return response()->json($sensorData); // Kembalikan data dalam format JSON
    }

    // Menyimpan data sensor baru melalui API
    public function store(Request $request)
    {
        $request->validate([
            'sensor_id' => 'required|exists:sensors,id',
            'value' => 'required',
        ]);

        $sensorData = SensorData::create([
            'sensor_id' => $request->sensor_id,
            'value' => $request->value,
        ]);

        return response()->json(['success' => 'Data sensor berhasil disimpan.', 'data' => $sensorData], 201);
    }

    // Memperbarui data sensor melalui API
    public function update(Request $request, $id)
    {
        $request->validate([
            'sensor_id' => 'required|exists:sensors,id',
            'value' => 'required',
        ]);

        $sensorData = SensorData::findOrFail($id);
        $sensorData->update([
            'sensor_id' => $request->sensor_id,
            'value' => $request->value,
        ]);

        return response()->json(['success' => 'Data sensor berhasil diperbarui.', 'data' => $sensorData]);
    }

    // Menghapus data sensor melalui API
    public function destroy($id)
    {
        $sensorData = SensorData::findOrFail($id);
        $sensorData->delete();

        return response()->json(['success' => 'Data sensor berhasil dihapus.']);
    }
}
