<?php

namespace App\Http\Controllers;

use App\Models\Inspection;
use App\Models\Rfid;
use App\Models\Dishub;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class InspectionController extends Controller
{
    public function index(Rfid $rfid)
    {
        // Mengambil kendaraan dari relasi RFID
        $vehicle = $rfid->vehicle;

        // Mengambil semua inspeksi yang HANYA terhubung dengan RFID ini
        $inspections = Inspection::where('rfid_id', $rfid->id)
                        ->oldest()
                        ->get();

        return view('inspections.index', compact('rfid', 'vehicle', 'inspections'));
    }

    // Halaman Form Uji Baru (Dipicu dari Tombol di Modal RFID tadi)
    public function create(Rfid $rfid)
    {
        $vehicle = $rfid->vehicle;
        // Ambil data Dishub berdasarkan wilayah kendaraan untuk auto-fill pejabat
        $dishub = Dishub::where('nama', $vehicle->wilayah)->first();
        
        return view('inspections.create', compact('rfid', 'vehicle', 'dishub'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Data
        // PENTING: Semua field angka harus diberi 'nullable|numeric' agar form tidak gagal 
        // jika petugas mengosongkan field tersebut saat pengujian.
        $request->validate([
            'rfid_id'      => 'required|exists:rfids,id',
            'tgl_uji'      => 'required|date',
            'hasil'        => 'required|in:Lolos Uji Berkala,Tidak Lolos Uji Berkala,Menunggu Hasil Uji',
            'nrp'          => 'required|string',
            'nama_petugas' => 'required|string',
            
            // Validasi Foto (Opsional, maksimal 2MB)
            'foto_depan'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_belakang' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_kanan'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_kiri'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

            // Validasi Alat Uji (Angka Desimal / Float)
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

            // Validasi Alat Uji (Angka Bulat / Integer)
            'emisi_hc'    => 'nullable|integer',
            'kebisingan'  => 'nullable|integer',
            'lampu_kanan' => 'nullable|integer',
            'lampu_kiri'  => 'nullable|integer',
        ]);

        // 2. Ambil semua data request kecuali file foto
        $data = $request->except(['foto_depan', 'foto_belakang', 'foto_kanan', 'foto_kiri']);

        // 3. Tambahkan ID Admin yang sedang login
        $data['admin_id'] = Auth::guard('admin')->id();

        // 4. Proses Upload Foto (Jika Ada)
        $fotoFields = ['foto_depan', 'foto_belakang', 'foto_kanan', 'foto_kiri'];
        foreach ($fotoFields as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $request->file($field)->store('inspections', 'public');
            }
        }

        // 5. Proses Checkbox (Visual & Manual)
        // Checkbox di HTML tidak mengirim data jika tidak dicentang.
        // Kita paksa ubah nilainya menjadi true (1) atau false (0) menggunakan $request->has()
        $checkboxes = [
            'rangka', 'mesin', 'tangki', 'pembuangan', 'ban', 'suspensi', 'rem_utama', 'lampu', 'dashboard',
            'spion', 'spakbor', 'bumper', 'perlengkapan', 'teknis', 'darurat', 'badan', 'converter',
            'penerus_daya', 'kemudi', 'rem_parkir', 'lampu_manual', 'wiper', 'kaca', 'klakson', 'sabuk', 'ukuran', 'kursi'
        ];
        foreach ($checkboxes as $cb) {
            $data[$cb] = $request->has($cb);
        }

        // 6. Logika Masa Berlaku Otomatis
        if ($request->hasil == 'Lolos Uji Berkala') {
            // Jika Lolos, masa berlaku = tgl_uji + 6 Bulan
            $data['tgl_berlaku'] = Carbon::parse($request->tgl_uji)->addMonths(6);
        } elseif ($request->hasil == 'Tidak Lolos Uji Berkala') {
            // Jika tidak lolos, masa berlaku dikosongkan (null)
            $data['tgl_berlaku'] = null;
        }

        // 7. Simpan ke Database
        $inspection = Inspection::create($data);

        // 8. Redirect kembali ke halaman Log RFID dengan pesan sukses
        return redirect()->route('inspections.index', $request->rfid_id)
                         ->with('success', 'Data hasil pengujian berhasil disimpan permanen.');
    }

    /**
     * Menampilkan detail hasil uji (Web View)
     */
    public function show(Inspection $inspection)
    {
        // Pastikan memanggil relasi 'user' dari vehicle
        $inspection->load(['rfid.vehicle.user', 'admin']);
        
        $vehicle = $inspection->rfid->vehicle;
        $rfid = $inspection->rfid;
        $user = $vehicle->user; // Mengambil data pemilik dari tabel users

        // Riwayat untuk penanda buku
        $history = Inspection::where('rfid_id', $rfid->id)
                    ->oldest()
                    ->get();

        return view('inspections.show', compact('inspection', 'vehicle', 'rfid', 'user', 'history'));
    }

    /**
     * Menampilkan halaman khusus cetak (Print/PDF View)
     * PERUBAHAN: Method baru ditambahkan di sini.
     */
    public function print(Inspection $inspection)
    {
        $inspection->load(['rfid.vehicle.user', 'admin']);
        
        $vehicle = $inspection->rfid->vehicle;
        $rfid = $inspection->rfid;
        $user = $vehicle->user;

        // Kita tidak butuh variabel $history di tampilan cetak, 
        // jadi tidak perlu di-query lagi.

        return view('inspections.print', compact('inspection', 'vehicle', 'rfid', 'user'));
    }

    /**
     * Menghapus riwayat uji beserta foto dokumentasinya.
     */
    public function destroy(Inspection $inspection)
    {
        // Simpan ID RFID untuk redirect kembali ke log yang benar
        $rfid_id = $inspection->rfid_id;

        // 1. HAPUS FOTO DARI STORAGE (Penyimpanan Server)
        $fotoFields = ['foto_depan', 'foto_belakang', 'foto_kanan', 'foto_kiri'];
        foreach ($fotoFields as $field) {
            // Jika field foto tersebut ada isinya di database
            if ($inspection->$field) {
                // Hapus file fisiknya dari folder storage/app/public/
                Storage::disk('public')->delete($inspection->$field);
            }
        }

        // 2. HAPUS DATA DARI DATABASE
        $inspection->delete();

        // 3. KEMBALIKAN KE HALAMAN INDEX DENGAN PESAN SUKSES
        return redirect()->route('inspections.index', $rfid_id)
                         ->with('success', 'Riwayat uji dan dokumentasi foto berhasil dihapus permanen.');
    }
}