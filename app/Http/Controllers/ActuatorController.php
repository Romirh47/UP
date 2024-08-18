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

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'status' => 'required|in:1,0',
        ]);

        Actuator::create($request->all());

        return response()->json(['success' => 'Actuator successfully added!'], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'status' => 'required|in:1,0',
        ]);

        $actuator = Actuator::findOrFail($id);
        $actuator->update($request->all());

        return response()->json(['success' => 'Actuator successfully updated!'], 200);
    }

    public function destroy($id)
    {
        $actuator = Actuator::findOrFail($id);
        $actuator->delete();
        return response()->json(['success' => 'Actuator deleted successfully'], 200);
    }
}
