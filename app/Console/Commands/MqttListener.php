<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpMqtt\Client\Facades\MQTT;
use App\Models\SensorData;
use App\Models\ActuatorValue;

class MqttListener extends Command
{
    protected $signature = 'mqtt:listen';
    protected $description = 'Listen to MQTT messages and process them';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Inisialisasi MQTT Client
        $client = MQTT::connection();  // Gunakan facade jika Anda telah mendaftarkan service provider untuk MQTT

        // Subscribe ke topik sensor utama
        $client->subscribe('IOT/SENSORS', function (string $topic, string $message) {
            $this->info("Received message on topic [$topic]: $message");

            // Parse JSON message
            $data = json_decode($message, true);

            if (isset($data['sensor_id']) && isset($data['value'])) {
                // Simpan ke tabel sensor_data
                SensorData::create([
                    'sensor_id' => $data['sensor_id'],
                    'value' => $data['value'],
                ]);

                // Cek jumlah data sensor
                $dataCount = SensorData::count();

                // Jika jumlah data sensor melebihi batas, hapus data yang paling lama
                if ($dataCount > 500) {
                    SensorData::orderBy('created_at', 'asc')
                        ->limit($dataCount - 500)
                        ->delete();
                }

                $this->info("Sensor data saved to database for sensor_id: {$data['sensor_id']}");
            } else {
                $this->error("Invalid sensor data received: $message");
            }
        }, 1); // QoS Level 1

        // Subscribe ke topik actuator utama
        $client->subscribe('IOT/ACTUATORS', function (string $topic, string $message) {
            $this->info("Received message on topic [$topic]: $message");

            // Parse JSON message
            $data = json_decode($message, true);

            if (isset($data['actuator_id']) && isset($data['value'])) {
                // Check if actuator_values table has more than 500 entries
                if (ActuatorValue::count() >= 500) {
                    // Delete the oldest entry to keep the number of entries below 500
                    ActuatorValue::oldest()->first()->delete();
                }

                // Save new actuator data to the table
                ActuatorValue::create([
                    'actuator_id' => $data['actuator_id'],
                    'value' => $data['value'],
                ]);

                $this->info("Actuator data saved to database for actuator_id: {$data['actuator_id']}");
            } else {
                $this->error("Invalid actuator data received: $message");
            }
        }, 1); // QoS Level 1


        $client->loop(true);  // Memulai listener loop
    }
}
