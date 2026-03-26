<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Dishub;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Tampilkan daftar semua admin/petugas.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $currentAdminId = Auth::guard('admin')->id();

        // 1. Ambil data DISHUB beserta relasi ADMIN-nya 
        // (Sembunyikan admin yang sedang login agar tidak bisa menghapus dirinya sendiri)
        $query = Dishub::with(['admins' => function($q) use ($currentAdminId) {
            $q->where('id', '!=', $currentAdminId);
        }])
        // 2. Hanya tampilkan Dishub yang memiliki minimal 1 admin lain
        ->whereHas('admins', function($q) use ($currentAdminId) {
            $q->where('id', '!=', $currentAdminId);
        });

        // 3. Logika Pencarian Global
        if ($search) {
            $query->where(function($q) use ($search, $currentAdminId) {
                // Cari berdasarkan Nama / Singkatan Wilayah Dishub
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('singkatan', 'like', "%{$search}%")
                  // ATAU Cari berdasarkan identitas Admin di dalam Dishub tersebut
                  ->orWhereHas('admins', function($qAdmin) use ($search, $currentAdminId) {
                      $qAdmin->where('id', '!=', $currentAdminId)
                             ->where(function($sub) use ($search) {
                                 $sub->where('nama', 'like', "%{$search}%")
                                     ->orWhere('nrp', 'like', "%{$search}%")
                                     ->orWhere('email', 'like', "%{$search}%");
                             });
                  });
            });
        }

        // 4. Paginate berdasarkan Cabang Dishub
        $dishubs = $query->orderBy('nama', 'asc')->paginate(10)->withQueryString();

        return view('admins.index', compact('dishubs'));
    }

    /**
     * Tampilkan form tambah admin baru.
     */
    public function create()
    {
        $dishubs = Dishub::all();
        return view('admins.create', compact('dishubs'));
    }

    /**
     * Simpan admin baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama'      => 'required|string|max:255',
            'email'     => 'required|email|unique:admins,email',
            'password'  => 'required|min:6',
            'role'      => 'required|in:admin,superadmin',
            'dishub_id' => 'required|exists:dishubs,id',
            'nrp'       => 'nullable|string|max:50',
            'pangkat'   => 'nullable|string',
        ]);

        Admin::create([
            'nama'      => $request->nama,
            'email'     => $request->email,
            'nrp'       => $request->nrp,
            'pangkat'   => $request->pangkat,
            'role'      => $request->role,
            'dishub_id' => $request->dishub_id,
            'password'  => Hash::make($request->password), // Enkripsi password
        ]);

        return redirect()->route('admins.index')
            ->with('success', 'Akun petugas berhasil dibuat.');
    }

    /**
     * Tampilkan detail admin (Jika menggunakan modal, ini opsional).
     */
    public function show(Admin $admin)
    {
        return view('admins.show', compact('admin'));
    }

    /**
     * Tampilkan form edit admin.
     */
    public function edit(Admin $admin)
    {
        $dishubs = Dishub::all();
        return view('admins.edit', compact('admin', 'dishubs'));
    }

    /**
     * Perbarui data admin di database.
     */
    public function update(Request $request, Admin $admin)
    {
        $request->validate([
            'nama'      => 'required|string|max:255',
            // Validasi email unik, abaikan ID admin yang sedang diedit
            'email'     => 'required|email|unique:admins,email,' . $admin->id,
            'role'      => 'required|in:admin,superadmin',
            'dishub_id' => 'required|exists:dishubs,id',
            'password'  => 'nullable|min:6', // Password boleh kosong saat edit
            'nrp'       => 'nullable|string|max:50',
            'pangkat'   => 'nullable|string',
        ]);

        $data = [
            'nama'      => $request->nama,
            'email'     => $request->email,
            'nrp'       => $request->nrp,
            'pangkat'   => $request->pangkat,
            'role'      => $request->role,
            'dishub_id' => $request->dishub_id,
        ];

        // Hanya update password jika input password diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);

        return redirect()->route('admins.index')
            ->with('success', 'Data petugas berhasil diperbarui.');
    }

    /**
     * Hapus akun admin.
     */
    public function destroy(Admin $admin)
    {
        // Keamanan tambahan: jangan izinkan menghapus diri sendiri melalui URL manual
        if ($admin->id === Auth::guard('admin')->id()) {
            return redirect()->route('admins.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $admin->delete();

        return redirect()->route('admins.index')
            ->with('success', 'Akun petugas telah dihapus.');
    }
}