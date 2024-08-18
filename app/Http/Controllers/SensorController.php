<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use App\Models\SensorData; // Tambahkan model SensorData
use Illuminate\Http\Request;
use PhpMqtt\Client\Facades\MQTT;

class SensorController extends Controller
{
    public function index()
    {
        $sensors = Sensor::all();
        return view('pages.sensors', compact('sensors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Sensor::create([
            'name' => $request->name,
            'type' => $request->type,
            'description' => $request->description,
        ]);

        return response()->json(['success' => 'Sensor berhasil disimpan.']);
    }

    public function destroy($id)
    {
        $sensor = Sensor::findOrFail($id);
        $sensor->delete();

        return response()->json(['success' => 'Sensor berhasil dihapus.']);
    }

    public function edit($id)
    {
        $sensor = Sensor::findOrFail($id);
        return response()->json($sensor);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $sensor = Sensor::findOrFail($id);
        $sensor->update([
            'name' => $request->name,
            'type' => $request->type,
            'description' => $request->description,
        ]);

        return response()->json(['success' => 'Sensor berhasil diperbarui.']);
    }

    public function publishSensorData()
    {
        set_time_limit(120); // Meningkatkan waktu eksekusi maksimum menjadi 120 detik

        // Ambil semua sensor dari database
        $sensors = Sensor::all();
        $mqtt = MQTT::connection();

        foreach ($sensors as $sensor) {
            // Ambil nilai sensor terbaru dari SensorData
            $sensorData = SensorData::where('sensor_id', $sensor->id)->latest()->first();

            if ($sensorData) {
                // Buat topik berdasarkan nama sensor
                $topic = 'sensors/' . $sensor->name;

                // Buat pesan berdasarkan data sensor
                $message = 'Value of ' . $sensor->name . ': ' . $sensorData->value;

                // Publish pesan ke broker MQTT
                $mqtt->publish($topic, $message, 1); // QoS level 1
                $mqtt->publish($topic, $message, 2, true); // QoS level 2 dengan retain flag
            }
        }

        // Jalankan event loop untuk memastikan pesan diterima
        $mqtt->loop(true);

        return response()->json(['message' => 'Messages published successfully'], 200);
    }
}
