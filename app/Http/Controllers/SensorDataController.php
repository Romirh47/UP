<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use App\Models\SensorData;
use Illuminate\Http\Request;

class SensorDataController extends Controller
{
    public function index()
    {
        $sensorData = SensorData::with('sensor')->get();
        $sensors = Sensor::all(); // Ambil semua sensor

        return view('pages.sensordata', compact('sensorData', 'sensors'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'sensor_id' => 'required|exists:sensors,id',
            'value' => 'required|numeric',
        ]);

        SensorData::create([
            'sensor_id' => $request->sensor_id,
            'value' => $request->value,
        ]);

        return response()->json(['success' => 'Data sensor berhasil disimpan.']);
    }

    public function destroy($id)
    {
        $sensorData = SensorData::findOrFail($id);
        $sensorData->delete();

        return response()->json(['success' => 'Data sensor berhasil dihapus.']);
    }

    public function edit($id)
    {
        $sensorData = SensorData::findOrFail($id);
        return response()->json($sensorData);
    }

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

        return response()->json(['success' => 'Data sensor berhasil diperbarui.']);
    }
}
