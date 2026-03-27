<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.admin-login');
    }

    public function login(Request $request)
    {
        // 1. Validasi Input Form
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email admin wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // 2. Percobaan Login dengan Guard 'admin'
        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            
            // 3. Proteksi Session Fixation
            $request->session()->regenerate();

            $admin = Auth::guard('admin')->user();

            // 4. Redirection berdasarkan Role
            if ($admin->role === 'superadmin') {
                return redirect()->intended('/admin/super-dashboard')
                    ->with('success', 'Selamat datang Super Admin!');
            }

            return redirect()->intended('/admin/dashboard')
                ->with('success', 'Login berhasil sebagai Admin.');
        }

        // 5. Feedback Gagal Login
        throw ValidationException::withMessages([
            'email' => ['Kredensial yang diberikan tidak cocok dengan data kami.'],
        ]);
    }

    public function logout(Request $request)
    {
        // Logout dari guard admin
        Auth::guard('admin')->logout();

        // Bersihkan session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login')->with('success', 'Berhasil logout dari sistem admin.');
    }
}