<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserController
 * 
 * Controller ini digunakan untuk mengelola data user (pemilik kendaraan),
 * meliputi:
 * - Menampilkan daftar user
 * - Menambahkan user
 * - Mengedit user
 * - Menghapus user
 * 
 * Catatan:
 * - Biasanya hanya dapat diakses oleh admin
 */
class UserController extends Controller
{
    /**
     * Menampilkan daftar user
     * 
     * Fitur:
     * - Pencarian berdasarkan nama, email, identitas, alamat
     * - Pagination
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        /**
         * Ambil input pencarian
         */
        $search = $request->input('search');

        /**
         * Query awal:
         * - hanya ambil data dengan role 'user'
         */
        $query = User::where('role', 'user');

        /**
         * Jika ada pencarian, tambahkan filter
         */
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nomor_identitas', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        /**
         * Pagination:
         * - 10 data per halaman
         * - mempertahankan query string (search)
         */
        $users = $query->latest()
                       ->paginate(10)
                       ->withQueryString();

        return view('users.index', compact('users'));
    }

    /**
     * Menampilkan form tambah user
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Menyimpan data user baru
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        /**
         * Validasi input
         * - email & nomor_identitas harus unik
         */
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'nomor_identitas' => 'required|unique:users,nomor_identitas',
            'alamat' => 'required',
        ]);

        /**
         * Simpan data user
         * - password dienkripsi
         * - role otomatis 'user'
         */
        User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nomor_identitas' => $request->nomor_identitas,
            'alamat' => $request->alamat,
            'role' => 'user',
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil ditambahkan');
    }

    /**
     * Menampilkan form edit user
     * 
     * @param \App\Models\User $user
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Memperbarui data user
     * 
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        /**
         * Validasi input
         * - email & nomor_identitas unik kecuali data sendiri
         */
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'nomor_identitas' => 'required|unique:users,nomor_identitas,' . $user->id,
        ]);

        /**
         * Ambil data yang akan diupdate
         */
        $data = $request->only(['nama', 'email', 'nomor_identitas', 'alamat']);
        
        /**
         * Update password jika diisi
         */
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        /**
         * Simpan perubahan
         */
        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', 'Data user diperbarui');
    }

    /**
     * Menghapus data user
     * 
     * @param \App\Models\User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        /**
         * Hapus data dari database
         */
        $user->delete();

        return back()->with('success', 'User berhasil dihapus');
    }
}