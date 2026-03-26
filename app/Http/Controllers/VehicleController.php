<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\User;
use App\Models\Dishub;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        // 1. Query dari model User (Pemilik), pastikan hanya mengambil user yang punya kendaraan
        $query = \App\Models\User::with(['vehicles.rfids', 'vehicles.user'])->whereHas('vehicles');

        // 2. Jika ada pencarian, cari dari nama/NIK pemilik ATAU dari data kendaraannya
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nomor_identitas', 'like', "%{$search}%")
                  ->orWhereHas('vehicles', function($qVeh) use ($search) {
                      $qVeh->where('no_uji', 'like', "%{$search}%")
                           ->orWhere('no_kendaraan', 'like', "%{$search}%")
                           ->orWhere('merk', 'like', "%{$search}%");
                  });
            });
        }

        // 3. Cek Role dan terapkan Pagination ke tabel USERS (Pemilik)
        if (Auth::guard('admin')->check()) {
            // Paginate berdasarkan Pemilik (Contoh: 10 Pemilik per halaman)
            $users = $query->latest()->paginate(10)->withQueryString();
        } elseif (Auth::check()) {
            // Jika user biasa, hanya ambil dirinya sendiri
            $users = User::with(['vehicles.rfids', 'vehicles.user'])
                         ->where('id', Auth::id())
                         ->paginate(1)->withQueryString();
        } else {
            abort(403, 'Anda belum login.');
        }

        // 4. Trik Rahasia: Ekstrak semua kendaraan dari pemilik yang tampil di halaman ini 
        // untuk di-passing ke perulangan Modal di tampilan (agar Modalnya tetap berfungsi)
        $vehicles = $users->pluck('vehicles')->flatten();

        // Lempar $users (untuk daftar accordion & pagination) dan $vehicles (untuk Modal)
        return view('vehicles.index', compact('users', 'vehicles'));
    }
    
    public function create()
    {
        $this->authorizeAdmin();

        $users = User::where('role', 'user')->get();
        $dishubs = Dishub::all(); 
    
        return view('vehicles.create', compact('users', 'dishubs'));
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $request->validate([
            'user_id' => 'required|exists:users,id',
            // PERBAIKAN: Tambahkan unique:vehicles agar muncul pesan error jika ganda
            'no_uji' => 'required|string|max:50|unique:vehicles,no_uji',
            'no_kendaraan' => 'required|string|max:20|unique:vehicles,no_kendaraan',
            'no_mesin' => 'required|string|unique:vehicles,no_mesin',
            'no_rangka' => 'required|string|unique:vehicles,no_rangka',
            
            'merk' => 'required|string',
            'tipe' => 'required|string',
            'jenis' => 'required|string',
            'tahun' => 'required|integer|min:1900|max:' . date('Y'),
            'bahan_bakar' => 'required|string',
            'wilayah' => 'required|exists:dishubs,nama',
            
            // Kolom lainnya opsional / nullable
            'cc' => 'nullable|numeric',
            'daya_hp' => 'nullable|numeric',
            'jbb' => 'nullable|numeric',
            'jbkb' => 'nullable|numeric',
            'jbi' => 'nullable|numeric',
            'jbki' => 'nullable|numeric',
            'mst' => 'nullable|numeric',
            'panjang' => 'nullable|numeric',
            'lebar' => 'nullable|numeric',
            'tinggi' => 'nullable|numeric',
            'daya_orang' => 'nullable|integer|min:0',
            'daya_barang' => 'nullable|numeric|min:0',
        ]);

        // Karena semua fillable sudah didefinisikan di Model,
        // menggunakan $request->all() lebih bersih daripada mendaftar ulang $request->only([...])
        Vehicle::create($request->all());

        return redirect()->route('vehicles.index')
            ->with('success', 'Kendaraan ditambahkan');
    }

    public function edit(Vehicle $vehicle)
    {
        $this->authorizeAdmin();

        $users = User::where('role', 'user')->get();
        $dishubs = Dishub::all();

        return view('vehicles.edit', compact('vehicle', 'users', 'dishubs'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $this->authorizeAdmin();

        // PERBAIKAN: Validasi saat update jauh lebih longgar di kodemu.
        // Kita juga harus tambahkan unique, tapi abaikan ID saat ini (agar tidak error jika data tak diubah)
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'no_uji' => 'required|string|max:50|unique:vehicles,no_uji,' . $vehicle->id,
            'no_kendaraan' => 'required|string|max:20|unique:vehicles,no_kendaraan,' . $vehicle->id,
            'no_mesin' => 'required|string|unique:vehicles,no_mesin,' . $vehicle->id,
            'no_rangka' => 'required|string|unique:vehicles,no_rangka,' . $vehicle->id,
            'merk' => 'required|string',
            'wilayah' => 'required|exists:dishubs,nama',
        ]);

        $vehicle->update($request->all());

        return redirect()->route('vehicles.index')
            ->with('success', 'Data kendaraan berhasil diperbarui');
    }

    public function destroy(Vehicle $vehicle)
    {
        $this->authorizeAdmin();

        $vehicle->delete();
        return back()->with('success', 'Kendaraan dihapus');
    }

    private function authorizeAdmin()
    {
        if (!Auth::guard('admin')->check()) {
            abort(403, 'Akses ditolak. Fitur ini hanya untuk Admin.');
        }
    }
}