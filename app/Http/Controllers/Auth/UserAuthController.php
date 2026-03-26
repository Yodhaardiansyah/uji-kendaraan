<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.user-login');
    }

    public function login(Request $request)
    {
        // 1. Validasi Input
        // Memastikan email dan password diisi sesuai format sebelum diproses
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            // Kustomisasi pesan error validasi (Opsional)
            'email.required' => 'Kolom email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kolom password wajib diisi.',
        ]);

        // 2. Proses Autentikasi
        // Perhatikan fitur "Remember Me" juga bisa ditambahkan jika ada checkbox di form
        $remember = $request->boolean('remember'); 

        if (Auth::attempt($credentials, $remember)) {
            // 3. Keamanan Session (Penting!)
            // Mencegah serangan Session Fixation setelah login berhasil
            $request->session()->regenerate();

            // Redirect ke halaman yang dituju sebelum login, atau ke dashboard sebagai default
            return redirect()->intended('/dashboard')->with('success', 'Selamat datang kembali!');
        }

        // 4. Feedback Jika Login Gagal
        // Catatan Keamanan: Jangan beritahu secara spesifik apakah "email" atau "password" yang salah 
        // untuk mencegah peretas menebak-nebak email yang terdaftar.
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email'); // Mengembalikan input email agar user tidak perlu mengetik ulang
    }

    public function logout(Request $request)
    {
        Auth::logout();

        // 5. Keamanan Logout
        // Menghapus data session dan token CSRF lama
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Anda telah berhasil logout.');
    }
}