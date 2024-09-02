<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use App\Models\Actuator;
use App\Models\SensorData;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil semua sensor beserta nilai sensor terbaru
        $sensors = Sensor::with('sensorData')->get();
        $sensorValues = $sensors->mapWithKeys(function ($sensor) {
            $latestValue = $sensor->sensorData->sortByDesc('created_at')->first();
            return [$sensor->id => $latestValue];
        });

        // Ambil semua aktuator beserta nilai aktuator terbaru
        $actuators = Actuator::with('actuatorValues')->get();
        $actuatorValues = $actuators->mapWithKeys(function ($actuator) {
            $latestValue = $actuator->actuatorValues->sortByDesc('created_at')->first();
            return [$actuator->id => $latestValue];
        });

        // Definisikan kategori dan data seri untuk grafik
        $categories = $sensors->pluck('name'); // Misalnya nama sensor sebagai kategori
        $series = $sensors->map(function ($sensor) {
            return [
                'name' => $sensor->name,
                'data' => $sensor->sensorData->sortByDesc('created_at')->pluck('value')
            ];
        });

        // Kirim data ke view
        return view('dashboard', [
            'sensors' => $sensors,
            'sensorValues' => $sensorValues,
            'actuators' => $actuators,
            'actuatorValues' => $actuatorValues,
            'categories' => $categories, // Tambahkan variabel kategori
            'series' => $series // Tambahkan variabel seri
        ]);
    }
}
