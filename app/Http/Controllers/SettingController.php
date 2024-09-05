<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Sensor;
use App\Models\Actuator;
use App\Models\ActuatorValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpMqtt\Client\Facades\MQTT;

class SettingController extends Controller
{
    // Untuk web
    public function index()
    {
        $settings = Setting::with('sensor', 'actuator')->paginate(10);
        $sensors = Sensor::all();
        $actuators = Actuator::all();

        return view('pages.setting', compact('settings', 'sensors', 'actuators'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'sensor_id' => 'required|exists:sensors,id',
            'actuator_id' => 'required|exists:actuators,id',
            'min_value' => 'required|numeric',
            'max_value' => 'required|numeric|gte:min_value',
            'actuator_action' => 'required|string|in:0,1', // Pastikan hanya '0' atau '1'
        ], [
            'max_value.gte' => 'Nilai maksimum harus lebih besar dari atau sama dengan nilai minimum.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('web.settings.index')
                ->withErrors($validator)
                ->withInput();
        }

        // Menyimpan data setting
        $setting = Setting::create($request->all());

        // Memeriksa dan menangani nilai sensor terbaru
        $this->handleSensorValue($request->sensor_id, $request->actuator_id);

        return redirect()->route('web.settings.index')->with('success', 'Data setting berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'sensor_id' => 'required|exists:sensors,id',
            'actuator_id' => 'required|exists:actuators,id',
            'min_value' => 'required|numeric',
            'max_value' => 'required|numeric|gte:min_value',
            'actuator_action' => 'required|string|in:0,1',
        ], [
            'max_value.gte' => 'Nilai maksimum harus lebih besar dari atau sama dengan nilai minimum.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('web.settings.index')
                ->withErrors($validator)
                ->withInput();
        }

        // Update data setting
        $setting = Setting::findOrFail($id);
        $setting->update($request->all());

        // Memeriksa dan menangani nilai sensor terbaru
        $this->handleSensorValue($request->sensor_id, $request->actuator_id);

        return redirect()->route('web.settings.index')->with('success', 'Data setting berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $setting = Setting::findOrFail($id);
        $setting->delete();

        return redirect()->route('web.settings.index')->with('success', 'Data setting berhasil dihapus.');
    }

    // Metode API
    public function apiIndex()
    {
        return response()->json(Setting::with('sensor', 'actuator')->get());
    }

    public function apiStore(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'sensor_id' => 'required|exists:sensors,id',
            'actuator_id' => 'required|exists:actuators,id',
            'min_value' => 'required|numeric',
            'max_value' => 'required|numeric|gte:min_value',
            'actuator_action' => 'required|string|in:0,1',
        ], [
            'max_value.gte' => 'Nilai maksimum harus lebih besar dari atau sama dengan nilai minimum.',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Menyimpan data
        $setting = Setting::create($request->all());

        // Memeriksa dan menangani nilai sensor terbaru
        $this->handleSensorValue($request->sensor_id, $request->actuator_id);

        return response()->json($setting, 201);
    }

    public function apiUpdate(Request $request, $id)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'sensor_id' => 'required|exists:sensors,id',
            'actuator_id' => 'required|exists:actuators,id',
            'min_value' => 'required|numeric',
            'max_value' => 'required|numeric|gte:min_value',
            'actuator_action' => 'required|string|in:0,1',
        ], [
            'max_value.gte' => 'Nilai maksimum harus lebih besar dari atau sama dengan nilai minimum.',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Update data
        $setting = Setting::findOrFail($id);
        $setting->update($request->all());

        // Memeriksa dan menangani nilai sensor terbaru
        $this->handleSensorValue($request->sensor_id, $request->actuator_id);

        return response()->json($setting);
    }

    public function apiDestroy($id)
    {
        $setting = Setting::findOrFail($id);
        $setting->delete();
        return response()->json(null, 204);
    }

    /**
     * Menangani nilai sensor terbaru berdasarkan pengaturan.
     */
    private function handleSensorValue($sensorId, $actuatorId)
    {
        $sensor = Sensor::find($sensorId);
        $sensorValue = $sensor ? $sensor->latestValue() : null;

        if ($sensorValue !== null) {
            // Ambil pengaturan untuk sensor dan actuator
            $settings = Setting::where('sensor_id', $sensorId)
                               ->where('actuator_id', $actuatorId)
                               ->get();

            foreach ($settings as $setting) {
                // Cek apakah nilai sensor berada dalam rentang yang ditentukan
                if ($sensorValue >= $setting->min_value && $sensorValue <= $setting->max_value) {
                    // Cek apakah sudah ada entri dengan nilai yang sama untuk actuator
                    $existingActuatorValue = ActuatorValue::where('actuator_id', $actuatorId)
                                                          ->where('value', $setting->actuator_action)
                                                          ->latest('created_at')
                                                          ->first();

                    if (!$existingActuatorValue || $existingActuatorValue->created_at->lt(now()->subMinutes(5))) {
                        // Simpan nilai ke actuator_values
                        ActuatorValue::create([
                            'actuator_id' => $actuatorId,
                            'value' => $setting->actuator_action,
                        ]);

                        // Publish nilai actuator ke MQTT
                        $this->publishActuatorValue($actuatorId, $setting->actuator_action);
                    }
                    break; // Hentikan loop setelah aksi dikirim
                }
            }
        }
    }

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
