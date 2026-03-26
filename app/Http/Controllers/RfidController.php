<?php

namespace App\Http\Controllers;

use App\Models\Rfid;
use App\Models\Vehicle; 
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Pastikan ini ada di atas

class RfidController extends Controller
{
    /**
     * Menampilkan semua daftar RFID
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Jika pencarian global mengandung URL, bersihkan dulu
        if (Str::contains($search, 'http')) {
            $search = basename(parse_url($search, PHP_URL_PATH));
        }

        $query = Vehicle::with(['user', 'rfids'])->whereHas('rfids');

        if (\Illuminate\Support\Facades\Auth::guard('admin')->check()) {
            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }
            $usersList = \App\Models\User::orderBy('nama', 'asc')->get(); 
            $vehiclesList = Vehicle::all(); 
        } else {
            $query->where('user_id', \Illuminate\Support\Facades\Auth::id());
            $usersList = collect(); 
            $vehiclesList = collect(); 
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('no_kendaraan', 'like', "%{$search}%")
                ->orWhere('no_uji', 'like', "%{$search}%")
                ->orWhereHas('user', function($qU) use ($search) {
                    $qU->where('nama', 'like', "%{$search}%")
                        ->orWhere('nomor_identitas', 'like', "%{$search}%");
                })
                ->orWhereHas('rfids', function($qR) use ($search) {
                    $qR->where('kode_rfid', 'like', "%{$search}%");
                });
            });
        }

        $vehicles = $query->latest()->paginate(10)->withQueryString();

        return view('rfids.index', compact('vehicles', 'usersList', 'vehiclesList'));
    }

    /**
     * Mendaftarkan kartu baru & menonaktifkan yang lama
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_rfid' => 'required',
            'vehicle_id' => 'required|exists:vehicles,id'
        ]);

        $kode = $request->kode_rfid;

        // --- VALIDASI URL PADA INPUT PENDAFTARAN ---
        if (Str::contains($kode, 'http')) {
            $kode = basename(parse_url($kode, PHP_URL_PATH));
        }
        // -------------------------------------------

        // 2. CEK DUPLIKASI MANUAL (Menggunakan kode yang sudah bersih)
        $existingRfid = Rfid::with('vehicle.user')
                            ->where('kode_rfid', $kode)
                            ->first();

        if ($existingRfid) {
            $platKendaraan = $existingRfid->vehicle->no_kendaraan ?? 'Tidak terdata';
            $namaPemilik = $existingRfid->vehicle->user->nama ?? 'Tidak diketahui';

            return back()->with('error', "Pendaftaran Gagal! Kartu RFID ({$kode}) sudah terdaftar pada kendaraan berplat {$platKendaraan} milik {$namaPemilik}.");
        }

        // 3. JIKA AMAN, LANJUTKAN
        Rfid::where('vehicle_id', $request->vehicle_id)
            ->update(['is_active' => false]);

        Rfid::create([
            'kode_rfid' => $kode, // Simpan kode yang sudah bersih
            'vehicle_id' => $request->vehicle_id,
            'is_active' => true 
        ]);

        return back()->with('success', 'Kartu RFID baru berhasil diaktifkan.');
    }

    /**
     * Berpindah status kartu (Aktif/Non-aktif)
     */
    public function toggleStatus($id)
    {
        $rfid = Rfid::findOrFail($id);
        
        if (!$rfid->is_active) {
            Rfid::where('vehicle_id', $rfid->vehicle_id)
                ->update(['is_active' => false]);
            
            $rfid->update(['is_active' => true]);
        } else {
            $rfid->update(['is_active' => false]);
        }

        return back()->with('success', 'Status kartu berhasil diperbarui.');
    }

    /**
     * Menghapus riwayat kartu
     */
    public function destroy(Rfid $rfid)
    {
        $rfid->delete();
        return back()->with('success', 'Riwayat kartu RFID berhasil dihapus.');
    }

    /**
     * Fungsi Redirect untuk fitur SCAN CEPAT di Dashboard Admin
     */
    public function searchRedirect(Request $request)
    {
        $request->validate([
            'kode_rfid' => 'required'
        ]);

        $kode = $request->kode_rfid;

        // --- VALIDASI URL PADA SCAN DASHBOARD ---
        if (Str::contains($kode, 'http')) {
            $kode = basename(parse_url($kode, PHP_URL_PATH));
        }
        // ----------------------------------------

        $rfid = Rfid::where('kode_rfid', $kode)->first();

        if ($rfid) {
            return redirect()->route('inspections.index', $rfid->id);
        }

        return redirect()->back()->with('error', 'Kartu RFID (' . $kode . ') tidak terdaftar.');
    }
}