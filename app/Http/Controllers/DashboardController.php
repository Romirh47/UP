<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use App\Models\SensorData;
use App\Models\Actuator;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil semua sensor
        $sensors = Sensor::all();

        // Ambil semua data untuk setiap sensor
        $sensorData = $sensors->mapWithKeys(function ($sensor) {
            $data = SensorData::where('sensor_id', $sensor->id)->orderBy('created_at', 'asc')->get();
            return [$sensor->id => $data];
        });

        // Ambil semua aktuator
        $actuators = Actuator::all();

        // Data untuk grafik
        $categories = $this->generateCategories(); // Sesuaikan dengan data Anda
        $series = $sensors->map(function ($sensor) use ($sensorData) {
            return [
                'name' => $sensor->name,
                'data' => $sensorData[$sensor->id]->pluck('value')->toArray()
            ];
        });

        // Kirim data ke view
        return view('dashboard', [
            'sensors' => $sensors,
            'sensorData' => $sensorData,
            'actuators' => $actuators,
            'categories' => $categories,
            'series' => $series,
        ]);
    }

    private function generateCategories()
    {
        // Generate categories for the x-axis based on your data
        return SensorData::orderBy('created_at', 'asc')
            ->distinct()
            ->pluck('created_at')
            ->map(function ($date) {
                return $date->format('Y-m-d H:i:s');
            })
            ->toArray();
    }
}
