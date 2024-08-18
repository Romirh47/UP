<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{
    // Metode untuk menampilkan daftar pengguna
    public function index()
    {
        $users = User::all();
        return view('pages.users.users', compact('users'));
    }

    // Metode untuk menambahkan pengguna baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos', 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'photo' => $photoPath,
        ]);

        return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
    }

    // Metode untuk menampilkan detail pengguna
    public function show(User $user)
    {
        return response()->json(['user' => $user], 200);
    }

    // Metode untuk mengedit pengguna
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $user->photo = $request->file('photo')->store('photos', 'public');
        }

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json(['message' => 'User updated successfully', 'user' => $user], 200);
    }

    // Metode untuk menghapus pengguna
    public function destroy(User $user)
    {
        // Periksa apakah foto ada dan hapus jika ada
        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
        }

        // Hapus pengguna
        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);
    }
}
