<?php

namespace App\Http\Controllers;

use App\Models\Rfid;
use App\Models\Inspection;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Tambahkan ini untuk helper string

class RfidScanController extends Controller
{
    public function index()
    {
        return view('public.check');
    }

    /**
     * Memproses Inputan dari Form atau Scanner
     */
    public function search(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|min:4'
        ], [
            'kode.required' => 'Silakan masukkan kode kartu atau tempelkan kartu pada scanner.'
        ]);

        $input = $request->kode;

        // --- LOGIKA VALIDASI URL ---
        // Jika input mengandung 'http', kita ambil bagian setelah '/' terakhir
        if (Str::contains($input, 'http')) {
            // Mengambil teks setelah karakter '/' terakhir
            // Contoh: .../check/043D21 -> 043D21
            $input = basename(parse_url($input, PHP_URL_PATH));
        }
        // ---------------------------

        // Langsung arahkan ke rute 'show' menggunakan kode yang sudah dibersihkan
        return redirect()->route('rfid.public.check', $input);
    }

    public function show($kode)
    {
        // 1. Cari RFID berdasarkan kode yang di-scan
        // Kita gunakan 'where' agar lebih fleksibel mencari kode_rfid
        $rfid = Rfid::with('vehicle.user')->where('kode_rfid', $kode)->first();

        // Jika RFID TIDAK DITEMUKAN
        if (!$rfid) {
            return redirect()->route('public.index')
                             ->with('error', 'Kartu RFID (' . $kode . ') tidak terdaftar dalam sistem kami.');
        }

        // 2. Ambil pengujian TERBARU dari kartu ini
        $inspection = Inspection::with('admin')
                                ->where('rfid_id', $rfid->id)
                                ->latest('created_at')
                                ->first();

        // Jika kartu ada, TAPI belum pernah diuji
        if (!$inspection) {
            return redirect()->route('public.index')
                             ->with('error', 'Kartu terdaftar, namun kendaraan ini belum memiliki riwayat hasil uji.');
        }

        $vehicle = $rfid->vehicle;
        $user = $vehicle->user;

        // 3. Ambil semua riwayat untuk fitur "Penanda Buku"
        $history = Inspection::where('rfid_id', $rfid->id)
                            ->oldest()
                            ->get();

        // 4. Tampilkan halaman sertifikat
        return view('inspections.show', compact('inspection', 'vehicle', 'rfid', 'user', 'history'));
    }
}