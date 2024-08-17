<?php

namespace App\Http\Controllers;

use App\Models\Humidity;
use Illuminate\Http\Request;

class HumidityController extends Controller
{
    public function index()
    {
        $humidities = Humidity::all();
        return view('pages.humidities.humidities', compact('humidities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'value' => 'required|numeric',
        ]);

        Humidity::create([
            'value' => $request->value,
        ]);

        return response()->json(['success' => 'Nilai kelembapan berhasil disimpan.']);
    }

    public function destroy($id)
    {
        $humidity = Humidity::findOrFail($id);
        $humidity->delete();

        return response()->json(['success' => 'Data Kelembapan berhasil dihapus.']);
    }

    public function edit($id)
    {
        $humidity = Humidity::findOrFail($id);
        return response()->json($humidity);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'value' => 'required|numeric',
        ]);

        $humidity = Humidity::findOrFail($id);
        $humidity->update([
            'value' => $request->value,
        ]);

        return response()->json(['success' => 'Data Kelembapan berhasil diperbarui.']);
    }
}
