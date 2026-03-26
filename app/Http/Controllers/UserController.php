<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Gunakan middleware auth:admin jika di web.php sudah dikelompokkan, 
    // jika belum, tambahkan manual di constructor atau method.

    public function index(Request $request)
    {
        $search = $request->input('search');

        // Mulai menyusun query, pastikan hanya mengambil yang role-nya 'user'
        $query = User::where('role', 'user');

        // Jika ada pencarian, tambahkan filter pencarian
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nomor_identitas', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        // Terapkan paginasi (10 data per halaman) dan withQueryString() agar saat pindah page, keyword pencariannya tidak hilang
        $users = $query->latest()->paginate(10)->withQueryString();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'nomor_identitas' => 'required|unique:users,nomor_identitas',
            'alamat' => 'required',
        ]);

        User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nomor_identitas' => $request->nomor_identitas,
            'alamat' => $request->alamat,
            'role' => 'user', // Otomatis set sebagai user
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'nomor_identitas' => 'required|unique:users,nomor_identitas,'.$user->id,
        ]);

        $data = $request->only(['nama', 'email', 'nomor_identitas', 'alamat']);
        
        // Jika password diisi, maka update password
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Data user diperbarui');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'User berhasil dihapus');
    }
}