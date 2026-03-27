<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;

/**
 * Class UserDashboardController
 * 
 * Controller ini digunakan untuk menampilkan dashboard user (pemilik kendaraan),
 * yang berisi:
 * - Informasi profil user
 * - Daftar kendaraan milik user
 * - Informasi RFID dan jumlah riwayat uji tiap kendaraan
 */
class UserDashboardController extends Controller
{
    /**
     * Menampilkan dashboard user
     * 
     * Fitur:
     * - Menampilkan data user yang login
     * - Menampilkan daftar kendaraan milik user
     * - Menampilkan RFID beserta jumlah inspeksi
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        /**
         * 1. Ambil data user yang sedang login
         */
        $user = Auth::user();

        /**
         * 2. Ambil data kendaraan milik user
         * - berdasarkan user_id
         * - eager loading relasi RFID
         * - dengan tambahan withCount('inspections') untuk menghitung jumlah uji
         * - diurutkan dari data terbaru
         */
        $vehicles = Vehicle::where('user_id', $user->id)
            ->with(['rfids' => function($query) {

                /**
                 * Tambahkan jumlah riwayat inspeksi pada setiap RFID
                 * - akan menghasilkan field: inspections_count
                 */
                $query->withCount('inspections')
                      ->latest(); 
            }])
            ->latest()
            ->get();

        /**
         * 3. Kirim data ke view dashboard user
         */
        return view('user.dashboard', compact('user', 'vehicles'));
    }
}