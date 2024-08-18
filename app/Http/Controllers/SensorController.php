<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use App\Models\SensorData;
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
        // Validasi input untuk memastikan 'name' unik
        $request->validate([
            'name' => 'required|string|max:255|unique:sensors,name', // Validasi unik untuk 'name'
            'type' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Buat sensor baru
        Sensor::create([
            'name' => $request->name,
            'type' => $request->type,
            'description' => $request->description,
        ]);

        // Kembalikan response untuk SweetAlert
        return response()->json([
            'success' => true,
            'message' => 'Sensor berhasil disimpan.',
        ]);
    }

    public function destroy($id)
    {
        $sensor = Sensor::findOrFail($id);
        $sensor->delete();

        // Kembalikan response untuk SweetAlert
        return response()->json([
            'success' => true,
            'message' => 'Sensor berhasil dihapus.',
        ]);
    }

    public function edit($id)
    {
        $sensor = Sensor::findOrFail($id);
        return response()->json($sensor);
    }

    public function update(Request $request, $id)
    {
        // Validasi input untuk memastikan 'name' unik, kecuali untuk sensor saat ini
        $request->validate([
            'name' => 'required|string|max:255|unique:sensors,name,' . $id, // Validasi unik untuk 'name', kecuali untuk ID saat ini
            'type' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Temukan sensor dan perbarui informasinya
        $sensor = Sensor::findOrFail($id);
        $sensor->update([
            'name' => $request->name,
            'type' => $request->type,
            'description' => $request->description,
        ]);

        // Kembalikan response untuk SweetAlert
        return response()->json([
            'success' => true,
            'message' => 'Sensor berhasil diperbarui.',
        ]);
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

        // Kembalikan response untuk SweetAlert
        return response()->json([
            'success' => true,
            'message' => 'Messages published successfully',
        ]);
    }
}
