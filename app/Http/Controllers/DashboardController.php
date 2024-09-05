<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use App\Models\Actuator;
use App\Models\ActuatorValue;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Ambil semua data sensor dengan data sensornya
            $sensors = Sensor::with('sensorData')->get();

            // Ambil semua actuator
            $actuators = Actuator::all();

            // Ambil nilai actuator terbaru untuk setiap actuator menggunakan subquery
            $subquery = ActuatorValue::select('id', 'actuator_id', 'value', 'created_at')
                ->whereRaw('id IN (SELECT MAX(id) FROM actuator_values GROUP BY actuator_id)')
                ->get()
                ->keyBy('actuator_id');

            $actuatorValues = $subquery->mapWithKeys(function ($item) {
                return [$item->actuator_id => [
                    'value' => (int) $item->value,
                    'created_at' => $item->created_at
                ]];
            });

            if (request()->ajax()) {
                return response()->json([
                    'sensors' => $sensors,
                    'actuators' => $actuators,
                    'actuatorValues' => $actuatorValues,
                ], 200);
            }

            return view('dashboard', compact('sensors', 'actuators', 'actuatorValues'));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function store(Request $request)
    {
        // Validasi data yang dikirim
        $validatedData = $request->validate([
            'actuator_id' => 'required|exists:actuators,id',
            'value' => 'required|boolean'
        ]);

        // Cek apakah actuator sudah memiliki nilai di database
        $actuatorValue = ActuatorValue::where('actuator_id', $validatedData['actuator_id'])->first();

        if ($actuatorValue) {
            // Jika ada, update nilainya
            $actuatorValue->value = $validatedData['value'];
            $actuatorValue->save();
        } else {
            // Jika tidak ada, buat entri baru
            ActuatorValue::create([
                'actuator_id' => $validatedData['actuator_id'],
                'value' => $validatedData['value']
            ]);
        }

        return response()->json(['message' => 'Status actuator berhasil diubah.'], 200);
    }
}
