<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class UserAuthController
 * 
 * Controller ini bertanggung jawab untuk menangani proses autentikasi user biasa,
 * meliputi:
 * - Menampilkan halaman login
 * - Memproses login
 * - Logout user
 */
class UserAuthController extends Controller
{
    /**
     * Menampilkan halaman login user
     * 
     * @return \Illuminate\View\View
     */
    public function showLogin()
    {
        return view('auth.user-login');
    }

    /**
     * Menangani proses login user
     * 
     * Alur proses:
     * 1. Validasi input email dan password
     * 2. Ambil nilai "remember me" (jika ada)
     * 3. Proses autentikasi menggunakan Auth
     * 4. Regenerasi session untuk keamanan
     * 5. Redirect ke halaman tujuan
     * 6. Jika gagal, kembalikan error
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        /**
         * 1. Validasi Input
         * - email wajib diisi dan harus format email
         * - password wajib diisi
         * - menggunakan custom error message agar lebih user-friendly
         */
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Kolom email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kolom password wajib diisi.',
        ]);

        /**
         * 2. Ambil nilai "Remember Me"
         * - akan bernilai true jika checkbox dicentang
         * - digunakan agar user tetap login dalam jangka waktu tertentu
         */
        $remember = $request->boolean('remember'); 

        /**
         * 3. Proses Autentikasi
         * - menggunakan Auth default (biasanya guard 'web')
         * - jika berhasil, lanjut ke proses berikutnya
         */
        if (Auth::attempt($credentials, $remember)) {

            /**
             * 4. Regenerasi Session
             * - penting untuk mencegah serangan session fixation
             */
            $request->session()->regenerate();

            /**
             * 5. Redirect Setelah Login
             * - intended(): kembali ke halaman yang sebelumnya diakses sebelum login
             * - fallback ke '/dashboard'
             */
            return redirect()->intended('/dashboard')
                ->with('success', 'Selamat datang kembali!');
        }

        /**
         * 6. Jika Login Gagal
         * - tidak memberikan informasi spesifik (email/password)
         * - untuk menghindari brute force / enumerasi akun
         * - onlyInput('email') agar user tidak perlu input ulang email
         */
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Menangani proses logout user
     * 
     * Alur:
     * 1. Logout user dari sistem
     * 2. Invalidate session (hapus semua data session)
     * 3. Regenerate CSRF token
     * 4. Redirect ke halaman login
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        /**
         * 1. Logout user dari guard default
         */
        Auth::logout();

        /**
         * 2. Hapus seluruh data session
         */
        $request->session()->invalidate();

        /**
         * 3. Generate ulang CSRF token
         */
        $request->session()->regenerateToken();

        /**
         * 4. Redirect ke halaman login dengan pesan sukses
         */
        return redirect('/login')
            ->with('success', 'Anda telah berhasil logout.');
    }
}