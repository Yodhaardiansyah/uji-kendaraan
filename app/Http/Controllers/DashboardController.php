<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Rfid;
use App\Models\Inspection;
use App\Models\Admin;
use App\Models\Dishub;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

/**
 * Class DashboardController
 * * Controller ini digunakan untuk menampilkan dashboard berdasarkan role:
 * - Admin (petugas lapangan)
 * - Superadmin (pusat)
 * * Data yang ditampilkan meliputi statistik, aktivitas terbaru,
 * serta agregasi data untuk visualisasi (grafik).
 */
class DashboardController extends Controller
{
    /**
     * Dashboard untuk Admin (Petugas Lapangan / Cabang)
     */
    public function admin()
    {
        $admin = Auth::guard('admin')->user();

        // Statistik Hari Ini (berdasarkan admin yang login)
        $ujiHariIni = Inspection::where('admin_id', $admin->id)
                        ->whereDate('created_at', Carbon::today())
                        ->count();
        
        $lulusUji = Inspection::where('admin_id', $admin->id)
                        ->where('hasil', 'Lolos Uji Berkala')
                        ->whereDate('created_at', Carbon::today())
                        ->count();

        // Statistik Global
        $totalKendaraan = Vehicle::count();
        $rfidAktif = Rfid::where('is_active', true)->count();

        // Riwayat Inspeksi Terbaru
        $recentInspections = Inspection::with('rfid.vehicle')
                                ->where('admin_id', $admin->id)
                                ->latest()
                                ->take(5)
                                ->get();

        // Pengelompokan Kendaraan Berdasarkan Wilayah
        $vehiclesByRegion = Vehicle::latest()->get()->groupBy(function($item) {
            return $item->wilayah ?? 'Wilayah Tidak Diketahui';
        });

        return view('admin.dashboard', compact(
            'admin',
            'ujiHariIni',
            'lulusUji',
            'totalKendaraan',
            'rfidAktif',
            'recentInspections',
            'vehiclesByRegion',
        ));
    }

    /**
     * Dashboard untuk Superadmin (Pusat)
     */
    public function superadmin()
    {
        $admin = Auth::guard('admin')->user();

        // Statistik Global Sistem
        $totalDishub = Dishub::count();
        $totalAdmin = Admin::where('role', 'admin')->count();
        $totalKendaraan = Vehicle::count();
        $totalRfid = Rfid::count();

        // Data untuk Grafik (jumlah admin per Dishub)
        $dishubStats = Dishub::withCount('admins')->get();
        
        $chartLabels = $dishubStats->pluck('singkatan')->toJson();
        $chartData = $dishubStats->pluck('admins_count')->toJson();

        /**
         * Mengambil SEMUA data kendaraan beserta relasi pemiliknya (user).
         * Menggunakan with('user') agar query database lebih ringan (mencegah N+1 query issue).
         * Variabel ini akan di-grouping bertingkat (Provinsi -> Dishub) langsung di level View Blade.
         */
        /**
         * 1. Ambil data semua kendaraan beserta pemiliknya
         */
        $allVehicles = Vehicle::with('user')->latest()->get();

        /**
         * 2. Ambil data semua Dishub sebagai referensi untuk mencari Provinsi
         */
        $dataDishub = Dishub::all();

        /**
         * 3. Grouping Bertingkat (Kendaraan -> Provinsi -> Wilayah)
         */
        $vehiclesByProvinsi = $allVehicles->groupBy(function($vehicle) use ($dataDishub) {
            // Cari Dishub yang 'nama'-nya sama dengan 'wilayah' kendaraan ini
            $dishub = $dataDishub->where('nama', $vehicle->wilayah)->first();
            
            // Jika ketemu, kembalikan nama provinsinya. Jika tidak, masuk ke 'Lainnya'
            return $dishub ? $dishub->provinsi : 'Provinsi Tidak Diketahui';
        })->map(function($grupProvinsi) {
            // Setelah dikelompokkan per provinsi, pecah lagi per wilayah (Dishub)
            return $grupProvinsi->groupBy('wilayah');
        });

        /**
         * 4. Kirim data ke view dashboard superadmin
         */
        return view('superadmin.dashboard', compact(
            'admin',
            'totalDishub',
            'totalAdmin',
            'totalKendaraan',
            'totalRfid',
            'chartLabels',
            'chartData',
            'vehiclesByProvinsi' // <--- Variabel baru pengganti allVehicles
        ));
    }
}