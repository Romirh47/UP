<?php

namespace App\Http\Controllers;

use App\Models\Temperature;
use Illuminate\Http\Request;

class TemperatureController extends Controller
{
    public function index()
    {
        $temperatures = Temperature::all();
        return view('pages.temperatures.temperatures', compact('temperatures'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'value' => 'required|numeric',
        ]);

        Temperature::create([
            'value' => $request->value,
        ]);

        return response()->json(['success' => 'Nilai suhu berhasil disimpan.']);
    }

    public function destroy($id)
    {
        $temperature = Temperature::findOrFail($id);
        $temperature->delete();

        return response()->json(['success' => 'Data suhu berhasil dihapus.']);
    }

    public function edit($id)
    {
        $temperature = Temperature::findOrFail($id);
        return response()->json($temperature);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'value' => 'required|numeric',
        ]);

        $temperature = Temperature::findOrFail($id);
        $temperature->update([
            'value' => $request->value,
        ]);

        return response()->json(['success' => 'Data suhu berhasil diperbarui.']);
    }
}
