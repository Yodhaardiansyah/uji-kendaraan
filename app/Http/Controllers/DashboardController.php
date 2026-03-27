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
 * 
 * Controller ini digunakan untuk menampilkan dashboard berdasarkan role:
 * - Admin (petugas lapangan)
 * - Superadmin (pusat)
 * 
 * Data yang ditampilkan meliputi statistik, aktivitas terbaru,
 * serta agregasi data untuk visualisasi (grafik).
 */
class DashboardController extends Controller
{
    /**
     * Dashboard untuk Admin (Petugas Lapangan / Cabang)
     * 
     * Menampilkan:
     * - Statistik kinerja admin hari ini
     * - Total data global (kendaraan & RFID)
     * - Riwayat inspeksi terbaru
     * - Data kendaraan berdasarkan wilayah
     * 
     * @return \Illuminate\View\View
     */
    public function admin()
    {
        /**
         * Ambil data admin yang sedang login
         */
        $admin = Auth::guard('admin')->user();

        /**
         * Statistik Hari Ini (berdasarkan admin yang login)
         */

        // Jumlah inspeksi yang dilakukan hari ini
        $ujiHariIni = Inspection::where('admin_id', $admin->id)
                        ->whereDate('created_at', Carbon::today())
                        ->count();
        
        // Jumlah kendaraan yang lulus uji hari ini
        $lulusUji = Inspection::where('admin_id', $admin->id)
                        ->where('hasil', 'Lolos Uji Berkala')
                        ->whereDate('created_at', Carbon::today())
                        ->count();

        /**
         * Statistik Global
         */

        // Total seluruh kendaraan yang terdaftar
        $totalKendaraan = Vehicle::count();

        // Jumlah RFID yang aktif
        $rfidAktif = Rfid::where('is_active', true)->count();

        /**
         * Riwayat Inspeksi Terbaru
         * - mengambil 5 data terakhir
         * - dengan relasi ke RFID dan Vehicle (eager loading)
         */
        $recentInspections = Inspection::with('rfid.vehicle')
                                ->where('admin_id', $admin->id)
                                ->latest()
                                ->take(5)
                                ->get();

        /**
         * Pengelompokan Kendaraan Berdasarkan Wilayah
         * - digunakan untuk tampilan ringkasan per wilayah
         * - fallback jika wilayah null
         */
        $vehiclesByRegion = Vehicle::latest()->get()->groupBy(function($item) {
            return $item->wilayah ?? 'Wilayah Tidak Diketahui';
        });

        /**
         * Kirim data ke view dashboard admin
         */
        return view('admin.dashboard', compact(
            'admin',
            'ujiHariIni',
            'lulusUji',
            'totalKendaraan',
            'rfidAktif',
            'recentInspections',
            'vehiclesByRegion'
        ));
    }

    /**
     * Dashboard untuk Superadmin (Pusat)
     * 
     * Menampilkan:
     * - Statistik global seluruh sistem
     * - Data agregasi untuk grafik (jumlah admin per Dishub)
     * - Data kendaraan berdasarkan wilayah
     * 
     * @return \Illuminate\View\View
     */
    public function superadmin()
    {
        /**
         * Ambil data admin (superadmin) yang sedang login
         */
        $admin = Auth::guard('admin')->user();

        /**
         * Statistik Global Sistem
         */

        // Total jumlah Dishub (cabang/wilayah)
        $totalDishub = Dishub::count();

        // Total admin (tidak termasuk superadmin)
        $totalAdmin = Admin::where('role', 'admin')->count();

        // Total kendaraan
        $totalKendaraan = Vehicle::count();

        // Total RFID (aktif + nonaktif)
        $totalRfid = Rfid::count();

        /**
         * Data untuk Grafik
         * - jumlah admin pada setiap Dishub
         */

        // Ambil Dishub beserta jumlah admin-nya
        $dishubStats = Dishub::withCount('admins')->get();
        
        /**
         * Format data untuk chart (biasanya digunakan di Chart.js / frontend)
         * - labels: nama/singkatan wilayah
         * - data: jumlah admin
         */
        $chartLabels = $dishubStats->pluck('singkatan')->toJson();
        $chartData = $dishubStats->pluck('admins_count')->toJson();

        /**
         * Pengelompokan kendaraan berdasarkan wilayah
         */
        $vehiclesByRegion = Vehicle::latest()->get()->groupBy(function($item) {
            return $item->wilayah ?? 'Wilayah Tidak Diketahui';
        });

        /**
         * Kirim data ke view dashboard superadmin
         */
        return view('superadmin.dashboard', compact(
            'admin',
            'totalDishub',
            'totalAdmin',
            'totalKendaraan',
            'totalRfid',
            'chartLabels',
            'chartData',
            'vehiclesByRegion'
        ));
    }
}