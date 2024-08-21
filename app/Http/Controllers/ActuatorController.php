<?php

namespace App\Http\Controllers;

use App\Models\Actuator;
use Illuminate\Http\Request;

class ActuatorController extends Controller
{
    // Menampilkan semua aktuator dalam format tampilan web
    public function index()
    {
        $actuators = Actuator::orderBy('created_at', 'desc')->paginate(5); // Mengurutkan data terbaru di atas
        return view('pages.actuators', compact('actuators'));
    }

    // Menampilkan semua aktuator dalam format JSON untuk API
    public function apiIndex(Request $request)
    {
        $page = $request->get('page', 1); // Mendapatkan halaman dari query parameter
        $perPage = 5; // Jumlah item per halaman
        $actuators = Actuator::orderBy('created_at', 'desc')->paginate($perPage, ['*'], 'page', $page); // Mengurutkan dan paginasi data aktuator
        return response()->json($actuators); // Mengembalikan data dengan format JSON
    }

    // Menyimpan aktuator baru melalui API
    public function apiStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:actuators',
            'description' => 'nullable|string',
        ]);

        $actuator = Actuator::create($request->only(['name', 'description']));

        return response()->json([
            'message' => 'Actuator berhasil ditambahkan!',
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

        return response()->json(['error' => 'Actuator tidak ditemukan'], 404);
    }

    // Menampilkan aktuator berdasarkan ID dalam format JSON untuk API
    public function apiShow($id)
    {
        $actuator = Actuator::find($id);

        if ($actuator) {
            return response()->json($actuator);
        }

        return response()->json(['error' => 'Actuator tidak ditemukan'], 404);
    }

    // Memperbarui aktuator melalui API
    public function apiUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:actuators,name,' . $id,
            'description' => 'nullable|string',
        ]);

        $actuator = Actuator::find($id);

        if ($actuator) {
            $actuator->update($request->only(['name', 'description']));
            return response()->json([
                'message' => 'Actuator berhasil diperbarui!',
                'data' => $actuator
            ], 200);
        }

        return response()->json(['error' => 'Actuator tidak ditemukan'], 404);
    }

    // Menghapus aktuator melalui API
    public function apiDestroy($id)
    {
        $actuator = Actuator::find($id);

        if ($actuator) {
            $actuator->delete();
            return response()->json(['message' => 'Actuator berhasil dihapus'], 200);
        }

        return response()->json(['error' => 'Actuator tidak ditemukan'], 404);
    }

    // Menampilkan nama-nama aktuator dalam format JSON untuk dropdown
    public function apiList()
    {
        $actuators = Actuator::all();
        return response()->json($actuators);
    }
}
