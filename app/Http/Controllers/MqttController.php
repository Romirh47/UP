<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use App\Models\SensorData;
use Illuminate\Http\Request;
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\SubscriptionHandler;

class MqttController extends Controller
{
    protected $mqttClient;

    public function __construct()
    {
        // Inisialisasi MQTT client
        $this->mqttClient = new MqttClient(env('MQTT_BROKER_HOST', 'mqtt.server.com'), env('MQTT_BROKER_PORT', 1883), env('MQTT_CLIENT_ID', 'client_id'));
    }

    // Route untuk subscribe ke topik sensor
    public function subscribe(Request $request)
    {
        $this->mqttClient->connect();
        $sensors = Sensor::all();

        foreach ($sensors as $sensor) {
            $topic = "sensors/{$sensor->id}/data";
            $this->mqttClient->subscribe($topic, function ($topic, $message) use ($sensor) {
                $this->handleMessage($message);
            });
        }

        $this->mqttClient->loop(true);
        return response()->json(['success' => 'Berhasil subscribe ke topik sensor.']);
    }

    // Fungsi untuk menangani pesan MQTT
    protected function handleMessage($message)
    {
        $data = json_decode($message, true);

        if (isset($data['sensor_id']) && isset($data['value'])) {
            SensorData::updateOrCreate(
                ['sensor_id' => $data['sensor_id']],
                ['value' => $data['value']]
            );
        }
    }

    // Route untuk berhenti langganan dari topik sensor
    public function unsubscribe(Request $request)
    {
        $this->mqttClient->disconnect();
        return response()->json(['success' => 'Berhasil berhenti dari langganan topik sensor.']);
    }
}
