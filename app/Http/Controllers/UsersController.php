<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{
    // Metode untuk menampilkan daftar pengguna dengan pagination
    public function index()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        // Menentukan jumlah item per halaman
        $users = User::paginate(10); // Mengambil 10 pengguna per halaman
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
            'role' => 'required|in:admin,user', // Validasi role admin atau user
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
            'role' => $request->role, // Simpan role yang diinput
        ]);

        return response()->json(['message' => 'Pengguna berhasil dibuat', 'user' => $user], 201);
    }



    // Metode untuk menampilkan detail pengguna
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Pengguna tidak ditemukan'], 404);
        }

        return response()->json(['user' => $user], 200);
    }

    // Metode untuk mengedit pengguna
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Pengguna tidak ditemukan'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,user', // Validasi role
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
        $user->role = $request->role; // Perbarui role

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json(['message' => 'Pengguna berhasil diperbarui', 'user' => $user], 200);
    }

    // Metode untuk menghapus pengguna
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Pengguna tidak ditemukan'], 404);
        }

        // Periksa apakah foto ada dan hapus jika ada
        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
        }

        // Hapus pengguna
        $user->delete();

        return response()->json(['message' => 'Pengguna berhasil dihapus'], 200);
    }
}
