<?php

namespace App\Http\Controllers;

use App\Models\Actuator;
use Illuminate\Http\Request;

class ActuatorController extends Controller
{
    // Menampilkan semua aktuator dalam format tampilan web
    public function index()
    {
        $actuators = Actuator::all();
        return view('pages.actuators', compact('actuators'));
    }

    // Menampilkan semua aktuator dalam format JSON untuk API
    public function apiIndex()
    {
        $actuators = Actuator::all();
        return response()->json($actuators);
    }

    // Menyimpan aktuator baru melalui API
    public function apiStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'status' => 'required|in:1,0',
        ]);

        $actuator = Actuator::create($request->all());

        return response()->json([
            'success' => 'Actuator successfully added!',
            'data' => $actuator
        ], 201);
    }

    // Menampilkan aktuator berdasarkan ID dalam format tampilan web
    public function show($id)
    {
        $actuator = Actuator::find($id);

        if ($actuator) {
            return view('pages.actuator', compact('actuator'));
        }

        return response()->json(['error' => 'Actuator not found'], 404);
    }

    // Menampilkan aktuator berdasarkan ID dalam format JSON untuk API
    public function apiShow($id)
    {
        $actuator = Actuator::find($id);

        if ($actuator) {
            return response()->json($actuator);
        }

        return response()->json(['error' => 'Actuator not found'], 404);
    }

    // Memperbarui aktuator melalui API
    public function apiUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'status' => 'required|in:1,0',
        ]);

        $actuator = Actuator::find($id);

        if ($actuator) {
            $actuator->update($request->all());
            return response()->json([
                'success' => 'Actuator successfully updated!',
                'data' => $actuator
            ], 200);
        }

        return response()->json(['error' => 'Actuator not found'], 404);
    }

    // Menghapus aktuator melalui API
    public function apiDestroy($id)
    {
        $actuator = Actuator::find($id);

        if ($actuator) {
            $actuator->delete();
            return response()->json(['success' => 'Actuator deleted successfully'], 200);
        }

        return response()->json(['error' => 'Actuator not found'], 404);
    }
}
