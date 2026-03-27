<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * Class AdminAuthController
 * 
 * Controller ini digunakan untuk menangani proses autentikasi admin,
 * mulai dari menampilkan halaman login, proses login, hingga logout.
 */
class AdminAuthController extends Controller
{
    /**
     * Menampilkan halaman form login admin
     * 
     * @return \Illuminate\View\View
     */
    public function showLogin()
    {
        return view('auth.admin-login');
    }

    /**
     * Menangani proses login admin
     * 
     * Alur:
     * 1. Validasi input (email & password)
     * 2. Attempt login menggunakan guard 'admin'
     * 3. Regenerasi session untuk keamanan (hindari session fixation)
     * 4. Redirect berdasarkan role admin
     * 5. Jika gagal, lempar error validasi
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        /**
         * 1. Validasi Input Form
         * - email wajib dan harus format email
         * - password wajib berupa string
         * - custom message disediakan untuk UX yang lebih baik
         */
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email admin wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        /**
         * 2. Percobaan Login menggunakan guard 'admin'
         * - guard 'admin' harus sudah dikonfigurasi di config/auth.php
         * - parameter kedua untuk "remember me"
         */
        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            
            /**
             * 3. Proteksi Session Fixation
             * - regenerate session ID setelah login
             * - penting untuk keamanan agar session lama tidak disalahgunakan
             */
            $request->session()->regenerate();

            /**
             * Ambil data admin yang sedang login
             */
            $admin = Auth::guard('admin')->user();

            /**
             * 4. Redirect berdasarkan Role
             * - jika superadmin → dashboard khusus
             * - jika admin biasa → dashboard umum
             */
            if ($admin->role === 'superadmin') {
                return redirect()->intended('/admin/super-dashboard')
                    ->with('success', 'Selamat datang Super Admin!');
            }

            return redirect()->intended('/admin/dashboard')
                ->with('success', 'Login berhasil sebagai Admin.');
        }

        /**
         * 5. Feedback jika login gagal
         * - menggunakan ValidationException agar bisa langsung tampil di form
         */
        throw ValidationException::withMessages([
            'email' => ['Kredensial yang diberikan tidak cocok dengan data kami.'],
        ]);
    }

    /**
     * Menangani proses logout admin
     * 
     * Alur:
     * 1. Logout dari guard 'admin'
     * 2. Invalidate session (hapus semua data session)
     * 3. Regenerate CSRF token untuk keamanan
     * 4. Redirect ke halaman login
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        /**
         * Logout dari guard admin
         */
        Auth::guard('admin')->logout();

        /**
         * Bersihkan session sepenuhnya
         */
        $request->session()->invalidate();

        /**
         * Generate ulang CSRF token
         */
        $request->session()->regenerateToken();

        /**
         * Redirect ke halaman login dengan pesan sukses
         */
        return redirect('/admin/login')->with('success', 'Berhasil logout dari sistem admin.');
    }
}