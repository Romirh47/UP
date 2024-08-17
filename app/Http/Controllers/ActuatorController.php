<?php

namespace App\Http\Controllers;

use App\Models\Actuator;
use Illuminate\Http\Request;

class ActuatorController extends Controller
{
    public function index()
    {
        $actuators = Actuator::all();
        return view('pages.actuators', compact('actuators'));
    }

    public function create()
    {
        return view('pages.actuators.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'status' => 'required|in:1,0',
        ]);

        Actuator::create($request->all());

        return redirect()->route('actuators.index')->with('success', 'Actuator berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $actuator = Actuator::findOrFail($id);
        return view('pages.actuators.edit', compact('actuator'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'status' => 'required|in:1,0',
        ]);

        $actuator = Actuator::findOrFail($id);
        $actuator->update($request->all());

        return redirect()->route('actuators.index')->with('success', 'Actuator berhasil diperbarui.');
    }

    // Metode untuk menghapus actuator
    public function destroy(Actuator $actuator)
    {
        $actuator->delete();
        return response()->json(['success' => 'Actuator deleted successfully'], 200);
    }

    // Metode untuk memperbarui status aktuator dari dashboard
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:0,1',
        ]);

        $actuator = Actuator::findOrFail($id);
        $actuator->status = $request->status;
        $actuator->save();

        return response()->json(['success' => 'Status berhasil diperbarui.']);
    }

}
