<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use App\Models\SensorData;
use App\Models\Actuator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DashboardController extends Controller
{
    // Method untuk menampilkan halaman dashboard
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

    // Method untuk mengambil data dashboard melalui API
    public function getData()
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

        // Format data untuk dikirim ke frontend
        return response()->json([
            'sensors' => $sensors,
            'sensorData' => $sensorData->mapWithKeys(function ($data, $id) {
                return [$id => $data->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'value' => $item->value,
                        'created_at' => $item->created_at->format('Y-m-d H:i:s')
                    ];
                })];
            }),
            'actuators' => $actuators,
        ]);
    }

    // Method untuk memperbarui status aktuator
    public function updateActuatorStatus(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'status' => 'required|boolean',
        ]);

        // Temukan aktuator
        $actuator = Actuator::findOrFail($id);

        // Perbarui status aktuator
        $actuator->status = $request->input('status');
        $actuator->save();

        // Kembalikan respons sukses
        return response()->json(['success' => 'Actuator status updated successfully']);
    }

    // Method untuk menghasilkan kategori untuk grafik
    private function generateCategories()
    {
        // Generate categories for the x-axis based on your data
        return SensorData::orderBy('created_at', 'asc')
            ->distinct('created_at')
            ->pluck('created_at')
            ->map(function ($date) {
                return $date->format('Y-m-d H:i:s');
            })
            ->toArray();
    }
}
