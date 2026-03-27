<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

/**
 * Class ProfileController
 * 
 * Controller ini digunakan untuk mengelola pengaturan akun (profile),
 * baik untuk:
 * - Admin (guard: admin)
 * - User biasa (guard: web)
 * 
 * Fitur:
 * - Menampilkan halaman pengaturan akun
 * - Update email
 * - Update password dengan verifikasi password lama
 */
class ProfileController extends Controller
{
    /**
     * Menampilkan halaman pengaturan profile
     * 
     * Sistem akan mendeteksi apakah yang login adalah admin atau user biasa
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        /**
         * Ambil data user yang sedang login
         * - jika login sebagai admin → gunakan guard 'admin'
         * - jika login sebagai user → gunakan default guard 'web'
         */
        $user = Auth::guard('admin')->check()
                ? Auth::guard('admin')->user()
                : Auth::user();

        return view('profile.setting', compact('user'));
    }

    /**
     * Memperbarui data profile (email & password)
     * 
     * Proses:
     * 1. Tentukan guard aktif (admin / user)
     * 2. Validasi input
     * 3. Update email
     * 4. Update password (jika diisi)
     * 5. Simpan perubahan
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        /**
         * 1. Tentukan guard yang aktif
         */
        $guard = Auth::guard('admin')->check() ? 'admin' : 'web';

        /**
         * Ambil user berdasarkan guard aktif
         * 
         * @var \App\Models\User|\App\Models\Admin $user
         */
        $user = Auth::guard($guard)->user();

        /**
         * 2. Validasi input
         * 
         * - email harus unik di tabel users & admins
         * - current_password wajib dan harus cocok dengan password lama
         * - password baru opsional, minimal 8 karakter dan harus dikonfirmasi
         */
        $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id . '|unique:admins,email,' . $user->id,
            'current_password' => 'required|current_password',
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ], [
            'current_password.current_password' => 'Password lama yang Anda masukkan salah.'
        ]);

        /**
         * 3. Update email
         */
        $user->email = $request->email;

        /**
         * 4. Update password jika diisi
         * - dienkripsi menggunakan Hash
         */
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        /**
         * 5. Simpan perubahan ke database
         */
        $user->save();

        return back()->with('success', 'Pengaturan akun berhasil diperbarui.');
    }
}