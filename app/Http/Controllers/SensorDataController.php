<?php

namespace App\Http\Controllers;

use App\Models\SensorData; // Perbaiki nama model
use Illuminate\Http\Request;

class SensorDataController extends Controller // Perbaiki nama controller
{
    public function index()
    {
        $sensorData = SensorData::all(); // Perbaiki nama model
        return view('pages.data_sensors', compact('sensorData'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sensor_id' => 'required|exists:sensors,id',
            'value' => 'required',
        ]);

        SensorData::create([ // Perbaiki nama model
            'sensor_id' => $request->sensor_id,
            'value' => $request->value,
        ]);

        return response()->json(['success' => 'Data sensor berhasil disimpan.']);
    }

    public function destroy($id)
    {
        $sensorData = SensorData::findOrFail($id); // Perbaiki nama model
        $sensorData->delete();

        return response()->json(['success' => 'Data sensor berhasil dihapus.']);
    }

    public function edit($id)
    {
        $sensorData = SensorData::findOrFail($id); // Perbaiki nama model
        return response()->json($sensorData);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'sensor_id' => 'required|exists:sensors,id',
            'value' => 'required',
        ]);

        $sensorData = SensorData::findOrFail($id); // Perbaiki nama model
        $sensorData->update([
            'sensor_id' => $request->sensor_id,
            'value' => $request->value,
        ]);

        return response()->json(['success' => 'Data sensor berhasil diperbarui.']);
    }
}
