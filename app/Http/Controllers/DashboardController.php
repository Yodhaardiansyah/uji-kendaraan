<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Rfid;
use App\Models\Inspection;
use App\Models\Admin;
use App\Models\Dishub;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Dashboard Untuk Admin (Petugas Lapangan / Cabang)
     */
    /**
     * Dashboard Untuk Admin (Petugas Lapangan / Cabang)
     */
    public function admin()
    {
        $admin = Auth::guard('admin')->user();

        // Statistik Hari Ini (Khusus kinerja admin yang login)
        $ujiHariIni = Inspection::where('admin_id', $admin->id)
                        ->whereDate('created_at', Carbon::today())
                        ->count();
        
        $lulusUji = Inspection::where('admin_id', $admin->id)
                        ->where('hasil', 'Lolos Uji Berkala')
                        ->whereDate('created_at', Carbon::today())
                        ->count();

        // Total Global
        $totalKendaraan = Vehicle::count();
        $rfidAktif = Rfid::where('is_active', true)->count();

        // 5 Riwayat Uji Terakhir yang dilakukan admin ini
        $recentInspections = Inspection::with('rfid.vehicle')
                                ->where('admin_id', $admin->id)
                                ->latest()
                                ->take(5)
                                ->get();

        // Mengambil semua kendaraan dan Dikelompokkan berdasarkan WILAYAH DISHUB
        $vehiclesByRegion = Vehicle::latest()->get()->groupBy(function($item) {
            return $item->wilayah ?? 'Wilayah Tidak Diketahui';
        });

        return view('admin.dashboard', compact(
            'admin', 'ujiHariIni', 'lulusUji', 'totalKendaraan', 'rfidAktif', 
            'recentInspections', 'vehiclesByRegion'
        ));
    }


    /**
     * Dashboard Untuk Superadmin (Pusat)
     */
    public function superadmin()
    {
        $admin = Auth::guard('admin')->user();

        // Kartu Indikator Global
        $totalDishub = Dishub::count();
        $totalAdmin = Admin::where('role', 'admin')->count();
        $totalKendaraan = Vehicle::count();
        $totalRfid = Rfid::count();

        // Data Untuk Grafik (Jumlah Petugas per Wilayah)
        $dishubStats = Dishub::withCount('admins')->get();
        
        $chartLabels = $dishubStats->pluck('singkatan')->toJson();
        $chartData = $dishubStats->pluck('admins_count')->toJson();

        // Mengambil semua kendaraan dan Dikelompokkan berdasarkan Wilayah
        $vehiclesByRegion = Vehicle::latest()->get()->groupBy(function($item) {
            return $item->wilayah ?? 'Wilayah Tidak Diketahui';
        });

        return view('superadmin.dashboard', compact(
            'admin', 'totalDishub', 'totalAdmin', 'totalKendaraan', 'totalRfid', 
            'chartLabels', 'chartData', 'vehiclesByRegion'
        ));
    }
}