<?php

namespace App\Http\Controllers;

use App\Models\Rfid;
use App\Models\Inspection;
use Illuminate\Http\Request;
use Illuminate\Support\Str; 

/**
 * Class RfidScanController
 * 
 * Controller ini digunakan untuk fitur publik (tanpa login),
 * yaitu:
 * - Mengecek data kendaraan melalui scan RFID / QR Code
 * - Menampilkan hasil uji kendaraan terbaru
 * 
 * Fitur ini biasanya digunakan oleh masyarakat umum
 * untuk memverifikasi status uji kendaraan.
 */
class RfidScanController extends Controller
{
    /**
     * Menampilkan halaman input/scan RFID (public)
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('public.check');
    }

    /**
     * Memproses input dari form atau scanner
     * 
     * Proses:
     * 1. Validasi input
     * 2. Normalisasi input (jika berupa URL hasil QR)
     * 3. Redirect ke halaman hasil
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function search(Request $request)
    {
        /**
         * 1. Validasi input
         * - minimal panjang 4 karakter
         */
        $request->validate([
            'kode' => 'required|string|min:4'
        ], [
            'kode.required' => 'Silakan masukkan kode kartu atau tempelkan kartu pada scanner.'
        ]);

        /**
         * Ambil input dari user
         */
        $input = $request->kode;

        /**
         * 2. Normalisasi input
         * - jika input berupa URL (hasil QR Code)
         * - ambil bagian terakhir sebagai kode RFID
         */
        if (Str::contains($input, 'http')) {
            $input = basename(parse_url($input, PHP_URL_PATH));
        }

        /**
         * 3. Redirect ke halaman hasil
         * - menggunakan route publik
         */
        return redirect()->route('rfid.public.check', $input);
    }

    /**
     * Menampilkan hasil scan RFID (public view)
     * 
     * Proses:
     * 1. Cari RFID berdasarkan kode
     * 2. Ambil data inspeksi terbaru
     * 3. Ambil riwayat inspeksi
     * 4. Tampilkan hasil
     * 
     * @param string $kode
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function show($kode)
    {
        /**
         * 1. Cari RFID berdasarkan kode
         * - eager loading relasi vehicle dan user
         */
        $rfid = Rfid::with('vehicle.user')
                    ->where('kode_rfid', $kode)
                    ->first();

        /**
         * Jika RFID tidak ditemukan
         */
        if (!$rfid) {
            return redirect()->route('public.index')
                             ->with('error', 'Kartu RFID (' . $kode . ') tidak terdaftar dalam sistem kami.');
        }

        /**
         * 2. Ambil data inspeksi terbaru
         * - berdasarkan waktu created_at
         */
        $inspection = Inspection::with('admin')
                                ->where('rfid_id', $rfid->id)
                                ->latest('created_at')
                                ->first();

        /**
         * Jika belum pernah diuji
         */
        if (!$inspection) {
            return redirect()->route('public.index')
                             ->with('error', 'Kartu terdaftar, namun kendaraan ini belum memiliki riwayat hasil uji.');
        }

        /**
         * Ambil relasi tambahan
         */
        $vehicle = $rfid->vehicle;
        $user = $vehicle->user;

        /**
         * 3. Ambil semua riwayat inspeksi
         * - digunakan untuk fitur "penanda buku" / histori
         */
        $history = Inspection::where('rfid_id', $rfid->id)
                            ->oldest()
                            ->get();

        /**
         * 4. Tampilkan halaman hasil (sertifikat uji)
         */
        return view('inspections.show', compact(
            'inspection',
            'vehicle',
            'rfid',
            'user',
            'history'
        ));
    }
}