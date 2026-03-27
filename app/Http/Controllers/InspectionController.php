<?php

namespace App\Http\Controllers;

use App\Models\Inspection;
use App\Models\Rfid;
use App\Models\Dishub;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

/**
 * Class InspectionController
 * 
 * Controller ini digunakan untuk mengelola proses pengujian kendaraan,
 * meliputi:
 * - Menampilkan riwayat uji berdasarkan RFID
 * - Menambahkan data uji baru
 * - Menampilkan detail hasil uji
 * - Cetak hasil uji
 * - Menghapus data uji beserta dokumentasi
 */
class InspectionController extends Controller
{
    /**
     * Menampilkan daftar riwayat inspeksi berdasarkan RFID
     * 
     * @param \App\Models\Rfid $rfid
     * @return \Illuminate\View\View
     */
    public function index(Rfid $rfid)
    {
        /**
         * Ambil data kendaraan dari relasi RFID
         */
        $vehicle = $rfid->vehicle;

        /**
         * Ambil semua data inspeksi berdasarkan RFID ini
         * - diurutkan dari yang paling lama
         */
        $inspections = Inspection::where('rfid_id', $rfid->id)
                        ->oldest()
                        ->get();

        return view('inspections.index', compact('rfid', 'vehicle', 'inspections'));
    }

    /**
     * Menampilkan form untuk menambahkan uji baru
     * 
     * @param \App\Models\Rfid $rfid
     * @return \Illuminate\View\View
     */
    public function create(Rfid $rfid)
    {
        /**
         * Ambil data kendaraan dari RFID
         */
        $vehicle = $rfid->vehicle;

        /**
         * Ambil data Dishub berdasarkan wilayah kendaraan
         * - digunakan untuk auto-fill data pejabat/petugas
         */
        $dishub = Dishub::where('nama', $vehicle->wilayah)->first();
        
        return view('inspections.create', compact('rfid', 'vehicle', 'dishub'));
    }

    /**
     * Menyimpan hasil inspeksi ke database
     * 
     * Proses:
     * 1. Validasi data input
     * 2. Ambil data request
     * 3. Tambahkan admin_id
     * 4. Upload foto (jika ada)
     * 5. Proses checkbox (boolean)
     * 6. Hitung masa berlaku
     * 7. Simpan ke database
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        /**
         * 1. Validasi Data
         * - numeric dibuat nullable agar fleksibel
         * - validasi file gambar maksimal 2MB
         */
        $request->validate([
            'rfid_id'      => 'required|exists:rfids,id',
            'tgl_uji'      => 'required|date',
            'hasil'        => 'required|in:Lolos Uji Berkala,Tidak Lolos Uji Berkala,Menunggu Hasil Uji',
            'nrp'          => 'required|string',
            'nama_petugas' => 'required|string',
            
            // Validasi Foto
            'foto_depan'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_belakang' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_kanan'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_kiri'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

            // Validasi Alat Uji (Float)
            'emisi_solar'         => 'nullable|numeric',
            'emisi_co'            => 'nullable|numeric',
            'rem_utama_total'     => 'nullable|numeric',
            'rem_utama_selisih_1' => 'nullable|numeric',
            'rem_utama_selisih_2' => 'nullable|numeric',
            'rem_utama_selisih_3' => 'nullable|numeric',
            'rem_utama_selisih_4' => 'nullable|numeric',
            'rem_parkir_tangan'   => 'nullable|numeric',
            'rem_parkir_kaki'     => 'nullable|numeric',
            'kincup_roda_depan'   => 'nullable|numeric',
            'deviasi_kanan'       => 'nullable|numeric',
            'deviasi_kiri'        => 'nullable|numeric',
            'speed_deviasi'       => 'nullable|numeric',
            'alur_ban'            => 'nullable|numeric',

            // Validasi Integer
            'emisi_hc'    => 'nullable|integer',
            'kebisingan'  => 'nullable|integer',
            'lampu_kanan' => 'nullable|integer',
            'lampu_kiri'  => 'nullable|integer',
        ]);

        /**
         * 2. Ambil data request kecuali file
         */
        $data = $request->except(['foto_depan', 'foto_belakang', 'foto_kanan', 'foto_kiri']);

        /**
         * 3. Tambahkan ID admin yang login
         */
        $data['admin_id'] = Auth::guard('admin')->id();

        /**
         * 4. Upload foto jika ada
         */
        $fotoFields = ['foto_depan', 'foto_belakang', 'foto_kanan', 'foto_kiri'];
        foreach ($fotoFields as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $request->file($field)->store('inspections', 'public');
            }
        }

        /**
         * 5. Proses checkbox menjadi boolean
         * - checkbox tidak mengirim value jika tidak dicentang
         */
        $checkboxes = [
            'rangka', 'mesin', 'tangki', 'pembuangan', 'ban', 'suspensi', 'rem_utama', 'lampu', 'dashboard',
            'spion', 'spakbor', 'bumper', 'perlengkapan', 'teknis', 'darurat', 'badan', 'converter',
            'penerus_daya', 'kemudi', 'rem_parkir', 'lampu_manual', 'wiper', 'kaca', 'klakson', 'sabuk', 'ukuran', 'kursi'
        ];
        foreach ($checkboxes as $cb) {
            $data[$cb] = $request->has($cb);
        }

        /**
         * 6. Logika masa berlaku hasil uji
         */
        if ($request->hasil == 'Lolos Uji Berkala') {
            $data['tgl_berlaku'] = Carbon::parse($request->tgl_uji)->addMonths(6);
        } elseif ($request->hasil == 'Tidak Lolos Uji Berkala') {
            $data['tgl_berlaku'] = null;
        }

        /**
         * 7. Simpan ke database
         */
        $inspection = Inspection::create($data);

        /**
         * 8. Redirect ke halaman riwayat inspeksi RFID
         */
        return redirect()->route('inspections.index', $request->rfid_id)
                         ->with('success', 'Data hasil pengujian berhasil disimpan permanen.');
    }

    /**
     * Menampilkan detail hasil inspeksi
     * 
     * @param \App\Models\Inspection $inspection
     * @return \Illuminate\View\View
     */
    public function show(Inspection $inspection)
    {
        /**
         * Load relasi untuk menghindari N+1 query
         */
        $inspection->load(['rfid.vehicle.user', 'admin']);
        
        $vehicle = $inspection->rfid->vehicle;
        $rfid = $inspection->rfid;
        $user = $vehicle->user;

        /**
         * Ambil riwayat inspeksi untuk RFID ini
         */
        $history = Inspection::where('rfid_id', $rfid->id)
                    ->oldest()
                    ->get();

        return view('inspections.show', compact('inspection', 'vehicle', 'rfid', 'user', 'history'));
    }

    /**
     * Menampilkan halaman cetak hasil inspeksi
     * 
     * @param \App\Models\Inspection $inspection
     * @return \Illuminate\View\View
     */
    public function print(Inspection $inspection)
    {
        /**
         * Load relasi data yang dibutuhkan
         */
        $inspection->load(['rfid.vehicle.user', 'admin']);
        
        $vehicle = $inspection->rfid->vehicle;
        $rfid = $inspection->rfid;
        $user = $vehicle->user;

        return view('inspections.print', compact('inspection', 'vehicle', 'rfid', 'user'));
    }

    /**
     * Menghapus data inspeksi beserta file foto
     * 
     * @param \App\Models\Inspection $inspection
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Inspection $inspection)
    {
        /**
         * Simpan ID RFID untuk redirect
         */
        $rfid_id = $inspection->rfid_id;

        /**
         * 1. Hapus file foto dari storage
         */
        $fotoFields = ['foto_depan', 'foto_belakang', 'foto_kanan', 'foto_kiri'];
        foreach ($fotoFields as $field) {
            if ($inspection->$field) {
                Storage::disk('public')->delete($inspection->$field);
            }
        }

        /**
         * 2. Hapus data dari database
         */
        $inspection->delete();

        /**
         * 3. Redirect kembali ke halaman riwayat
         */
        return redirect()->route('inspections.index', $rfid_id)
                         ->with('success', 'Riwayat uji dan dokumentasi foto berhasil dihapus permanen.');
    }
}