<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    /**
     * Menampilkan dashboard user dengan daftar kendaraan miliknya.
     */
    public function index()
    {
        // 1. Ambil seluruh data profil user yang sedang login
        $user = Auth::user();

        // 2. Ambil data kendaraan yang user_id nya sesuai dengan user yang login
        $vehicles = Vehicle::where('user_id', $user->id)
            ->with(['rfids' => function($query) {
                // Tambahkan withCount agar view bisa mendeteksi jumlah riwayat uji
                $query->withCount('inspections')->latest(); 
            }])
            ->latest()
            ->get();

        // 3. Kirim data $user dan $vehicles ke view
        return view('user.dashboard', compact('user', 'vehicles'));
    }
}