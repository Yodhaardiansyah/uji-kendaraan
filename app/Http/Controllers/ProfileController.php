<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function index()
    {
        // Ambil data user/admin yang sedang login
        $user = Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::user();
        return view('profile.setting', compact('user'));
    }

    public function update(Request $request)
    {
        $guard = Auth::guard('admin')->check() ? 'admin' : 'web';

        // 1. TAMBAHKAN BARIS KOMENTAR INI TEPAT DI ATAS VARIABEL $user
        /** @var \App\Models\User|\App\Models\Admin $user */
        $user = Auth::guard($guard)->user();

        $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id . '|unique:admins,email,' . $user->id,
            'current_password' => 'required|current_password',
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ], [
            'current_password.current_password' => 'Password lama yang Anda masukkan salah.'
        ]);

        // Update Email
        $user->email = $request->email;

        // Update Password jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // 2. Sekarang garis merah di bawah ini pasti menghilang!
        $user->save();

        return back()->with('success', 'Pengaturan akun berhasil diperbarui.');
    }
}