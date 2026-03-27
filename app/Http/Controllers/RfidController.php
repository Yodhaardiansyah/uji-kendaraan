<?php

namespace App\Http\Controllers;

use App\Models\Rfid;
use App\Models\Vehicle; 
use Illuminate\Http\Request;
use Illuminate\Support\Str; 

/**
 * Class RfidController
 * 
 * Controller ini digunakan untuk mengelola data kartu RFID,
 * meliputi:
 * - Menampilkan daftar RFID
 * - Registrasi kartu RFID baru
 * - Aktivasi / non-aktif kartu
 * - Hapus data RFID
 * - Redirect hasil scan RFID (fitur scan cepat)
 */
class RfidController extends Controller
{
    /**
     * Menampilkan daftar RFID berdasarkan kendaraan
     * 
     * Fitur:
     * - Pencarian global (plat, no uji, user, RFID)
     * - Filter berdasarkan user (khusus admin)
     * - Pagination
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        /**
         * Ambil input pencarian
         */
        $search = $request->input('search');

        /**
         * Normalisasi input jika berupa URL hasil scan QR
         * - ambil bagian akhir path sebagai kode RFID
         */
        if (Str::contains($search, 'http')) {
            $search = basename(parse_url($search, PHP_URL_PATH));
        }

        /**
         * Query utama:
         * - ambil kendaraan yang memiliki RFID
         * - eager loading relasi user dan rfids
         */
        $query = Vehicle::with(['user', 'rfids'])->whereHas('rfids');

        /**
         * Role-based filtering
         * - Admin: bisa filter berdasarkan user
         * - User: hanya melihat data miliknya sendiri
         */
        if (\Illuminate\Support\Facades\Auth::guard('admin')->check()) {

            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }

            // Data untuk dropdown/filter
            $usersList = \App\Models\User::orderBy('nama', 'asc')->get(); 
            $vehiclesList = Vehicle::all(); 

        } else {
            $query->where('user_id', \Illuminate\Support\Facades\Auth::id());

            $usersList = collect(); 
            $vehiclesList = collect(); 
        }

        /**
         * Logika pencarian global
         */
        if ($search) {
            $query->where(function($q) use ($search) {

                // Cari berdasarkan data kendaraan
                $q->where('no_kendaraan', 'like', "%{$search}%")
                  ->orWhere('no_uji', 'like', "%{$search}%")

                  // Cari berdasarkan data user
                  ->orWhereHas('user', function($qU) use ($search) {
                      $qU->where('nama', 'like', "%{$search}%")
                         ->orWhere('nomor_identitas', 'like', "%{$search}%");
                  })

                  // Cari berdasarkan kode RFID
                  ->orWhereHas('rfids', function($qR) use ($search) {
                      $qR->where('kode_rfid', 'like', "%{$search}%");
                  });
            });
        }

        /**
         * Pagination data
         */
        $vehicles = $query->latest()
                          ->paginate(10)
                          ->withQueryString();

        return view('rfids.index', compact('vehicles', 'usersList', 'vehiclesList'));
    }

    /**
     * Menyimpan / registrasi kartu RFID baru
     * 
     * Proses:
     * 1. Validasi input
     * 2. Normalisasi kode RFID (jika berupa URL)
     * 3. Cek duplikasi kartu
     * 4. Nonaktifkan kartu lama pada kendaraan
     * 5. Simpan kartu baru sebagai aktif
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        /**
         * 1. Validasi input
         */
        $request->validate([
            'kode_rfid' => 'required',
            'vehicle_id' => 'required|exists:vehicles,id'
        ]);

        /**
         * 2. Normalisasi kode RFID
         */
        $kode = $request->kode_rfid;

        if (Str::contains($kode, 'http')) {
            $kode = basename(parse_url($kode, PHP_URL_PATH));
        }

        /**
         * 3. Cek duplikasi RFID
         * - jika sudah ada, tampilkan error detail
         */
        $existingRfid = Rfid::with('vehicle.user')
                            ->where('kode_rfid', $kode)
                            ->first();

        if ($existingRfid) {
            $platKendaraan = $existingRfid->vehicle->no_kendaraan ?? 'Tidak terdata';
            $namaPemilik = $existingRfid->vehicle->user->nama ?? 'Tidak diketahui';

            return back()->with('error', 
                "Pendaftaran Gagal! Kartu RFID ({$kode}) sudah terdaftar pada kendaraan berplat {$platKendaraan} milik {$namaPemilik}."
            );
        }

        /**
         * 4. Nonaktifkan semua RFID lama pada kendaraan ini
         */
        Rfid::where('vehicle_id', $request->vehicle_id)
            ->update(['is_active' => false]);

        /**
         * 5. Simpan RFID baru sebagai aktif
         */
        Rfid::create([
            'kode_rfid' => $kode,
            'vehicle_id' => $request->vehicle_id,
            'is_active' => true 
        ]);

        return back()->with('success', 'Kartu RFID baru berhasil diaktifkan.');
    }

    /**
     * Mengubah status kartu RFID (aktif / non-aktif)
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleStatus($id)
    {
        /**
         * Ambil data RFID berdasarkan ID
         */
        $rfid = Rfid::findOrFail($id);
        
        /**
         * Jika ingin mengaktifkan:
         * - nonaktifkan semua RFID lain pada kendaraan
         * - aktifkan RFID ini
         */
        if (!$rfid->is_active) {

            Rfid::where('vehicle_id', $rfid->vehicle_id)
                ->update(['is_active' => false]);
            
            $rfid->update(['is_active' => true]);

        } else {
            /**
             * Jika sudah aktif → nonaktifkan
             */
            $rfid->update(['is_active' => false]);
        }

        return back()->with('success', 'Status kartu berhasil diperbarui.');
    }

    /**
     * Menghapus data RFID
     * 
     * @param \App\Models\Rfid $rfid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Rfid $rfid)
    {
        /**
         * Hapus data RFID dari database
         */
        $rfid->delete();

        return back()->with('success', 'Riwayat kartu RFID berhasil dihapus.');
    }

    /**
     * Redirect hasil scan RFID (fitur scan cepat)
     * 
     * Proses:
     * 1. Validasi input scan
     * 2. Normalisasi jika berupa URL
     * 3. Cari RFID
     * 4. Redirect ke halaman inspeksi
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function searchRedirect(Request $request)
    {
        /**
         * 1. Validasi input
         */
        $request->validate([
            'kode_rfid' => 'required'
        ]);

        /**
         * 2. Normalisasi input scan
         */
        $kode = $request->kode_rfid;

        if (Str::contains($kode, 'http')) {
            $kode = basename(parse_url($kode, PHP_URL_PATH));
        }

        /**
         * 3. Cari RFID berdasarkan kode
         */
        $rfid = Rfid::where('kode_rfid', $kode)->first();

        /**
         * 4. Jika ditemukan → redirect ke halaman inspeksi
         */
        if ($rfid) {
            return redirect()->route('inspections.index', $rfid->id);
        }

        /**
         * Jika tidak ditemukan → tampilkan error
         */
        return redirect()->back()
            ->with('error', 'Kartu RFID (' . $kode . ') tidak terdaftar.');
    }
}