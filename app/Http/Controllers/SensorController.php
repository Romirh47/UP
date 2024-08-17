<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use Illuminate\Http\Request;

class SensorController extends Controller
{
    public function index()
    {
        $sensors = Sensor::all();
        return view('pages.sensors', compact('sensors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Sensor::create([
            'name' => $request->name,
            'type' => $request->type,
            'description' => $request->description,
        ]);

        return response()->json(['success' => 'Sensor berhasil disimpan.']);
    }

    public function destroy($id)
    {
        $sensor = Sensor::findOrFail($id);
        $sensor->delete();

        return response()->json(['success' => 'Sensor berhasil dihapus.']);
    }

    public function edit($id)
    {
        $sensor = Sensor::findOrFail($id);
        return response()->json($sensor);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $sensor = Sensor::findOrFail($id);
        $sensor->update([
            'name' => $request->name,
            'type' => $request->type,
            'description' => $request->description,
        ]);

        return response()->json(['success' => 'Sensor berhasil diperbarui.']);
    }
}
