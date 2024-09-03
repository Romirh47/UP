<?php

namespace App\Http\Controllers;

use App\Models\ActuatorValue;
use Illuminate\Http\Request;
use App\Models\Actuator;
use PhpMqtt\Client\Facades\MQTT; // Pastikan Anda menggunakan library MQTT yang sesuai

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
        $actuatorValue = ActuatorValue::create([
            'actuator_id' => $id,
            'value' => $value,
        ]);

        // Publish nilai actuator ke MQTT
        $this->publishActuatorValue($id, $value);

        return response()->json(['success' => 'Status aktuator berhasil diperbarui!']);
    }

    // Fungsi untuk menerbitkan nilai actuator ke topik MQTT
    private function publishActuatorValue($actuatorId, $value)
    {
        // Inisialisasi MQTT Client
        $client = MQTT::connection();

        // Mendapatkan nama actuator dari database
        $actuator = Actuator::find($actuatorId);
        $actuatorName = $actuator ? $actuator->name : 'unknown';

        // Membuat payload
        $payload = json_encode([
            'actuator_id' => $actuatorId,
            'value' => $value,
        ]);

        // Topik actuator
        $actuatorTopic = "IOT/ACTUATORS";

        // Publish pesan
        $client->publish($actuatorTopic, $payload, 1); // QoS Level 1
    }
}
