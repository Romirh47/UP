<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sensor;
use PhpMqtt\Client\Facades\MQTT;

class MqttController extends Controller
{
    public function publishSensorData()
    {
        // Ambil semua sensor dari database
        $sensors = Sensor::all();

        // Inisialisasi koneksi MQTT
        $mqtt = MQTT::connection();

        // Loop melalui setiap sensor
        foreach ($sensors as $sensor) {
            // Tentukan topik berdasarkan nama sensor
            $topic = 'sensors/' . $sensor->name;

            // Tentukan nilai pesan
            $message = 'Value of ' . $sensor->name . ': ' . $sensor->value;

            // Terbitkan pesan ke topik dengan QoS level 1
            $mqtt->publish($topic, $message, 1);

            // Terbitkan pesan dengan QoS level 2 dan opsi retain
            $mqtt->publish($topic, $message, 2, true);
        }

        // Jalankan loop untuk memastikan pesan diterima
        $mqtt->loop(true);

        return response()->json(['message' => 'Messages published successfully'], 200);
    }
}
