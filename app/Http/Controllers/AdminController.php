<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Dishub;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

/**
 * Class AdminController
 * 
 * Controller ini digunakan untuk mengelola data admin/petugas,
 * meliputi:
 * - Menampilkan daftar admin
 * - Menambah admin
 * - Mengedit admin
 * - Menghapus admin
 */
class AdminController extends Controller
{
    /**
     * Menampilkan daftar semua admin/petugas berdasarkan Dishub
     * 
     * Fitur:
     * - Filtering admin berdasarkan Dishub
     * - Pencarian global (Dishub + Admin)
     * - Pagination
     * - Proteksi agar admin tidak bisa melihat/menghapus dirinya sendiri
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        /**
         * Ambil parameter pencarian dari input user
         */
        $search = $request->input('search');

        /**
         * Ambil ID admin yang sedang login
         * digunakan untuk mencegah manipulasi terhadap akun sendiri
         */
        $currentAdminId = Auth::guard('admin')->id();

        /**
         * 1. Query utama: ambil data Dishub beserta relasi Admin
         * - with(): eager loading relasi admins
         * - filter admin agar tidak menyertakan admin yang sedang login
         */
        $query = Dishub::with(['admins' => function($q) use ($currentAdminId) {
            $q->where('id', '!=', $currentAdminId);
        }])
        /**
         * 2. whereHas():
         * hanya tampilkan Dishub yang memiliki minimal 1 admin selain diri sendiri
         */
        ->whereHas('admins', function($q) use ($currentAdminId) {
            $q->where('id', '!=', $currentAdminId);
        });

        /**
         * 3. Logika pencarian global
         * - berdasarkan nama/singkatan Dishub
         * - atau berdasarkan data admin (nama, nrp, email)
         */
        if ($search) {
            $query->where(function($q) use ($search, $currentAdminId) {

                // Pencarian pada tabel Dishub
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('singkatan', 'like', "%{$search}%")

                  // Pencarian pada relasi Admin
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

        /**
         * 4. Pagination data Dishub
         * - urut berdasarkan nama
         * - 10 data per halaman
         * - withQueryString(): menjaga parameter search saat pindah halaman
         */
        $dishubs = $query->orderBy('nama', 'asc')
                         ->paginate(10)
                         ->withQueryString();

        return view('admins.index', compact('dishubs'));
    }

    /**
     * Menampilkan form untuk menambahkan admin baru
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        /**
         * Ambil semua data Dishub
         * digunakan untuk pilihan relasi saat membuat admin
         */
        $dishubs = Dishub::all();

        return view('admins.create', compact('dishubs'));
    }

    /**
     * Menyimpan data admin baru ke database
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        /**
         * Validasi input dari form
         */
        $request->validate([
            'nama'      => 'required|string|max:255',
            'email'     => 'required|email|unique:admins,email',
            'password'  => 'required|min:6',
            'role'      => 'required|in:admin,superadmin',
            'dishub_id' => 'required|exists:dishubs,id',
            'nrp'       => 'nullable|string|max:50',
            'pangkat'   => 'nullable|string',
        ]);

        /**
         * Simpan data ke database
         * - password dienkripsi menggunakan Hash
         */
        Admin::create([
            'nama'      => $request->nama,
            'email'     => $request->email,
            'nrp'       => $request->nrp,
            'pangkat'   => $request->pangkat,
            'role'      => $request->role,
            'dishub_id' => $request->dishub_id,
            'password'  => Hash::make($request->password),
        ]);

        return redirect()->route('admins.index')
            ->with('success', 'Akun petugas berhasil dibuat.');
    }

    /**
     * Menampilkan detail admin (opsional, biasanya untuk modal/detail page)
     * 
     * @param \App\Models\Admin $admin
     * @return \Illuminate\View\View
     */
    public function show(Admin $admin)
    {
        return view('admins.show', compact('admin'));
    }

    /**
     * Menampilkan form edit admin
     * 
     * @param \App\Models\Admin $admin
     * @return \Illuminate\View\View
     */
    public function edit(Admin $admin)
    {
        /**
         * Ambil data Dishub untuk dropdown pilihan
         */
        $dishubs = Dishub::all();

        return view('admins.edit', compact('admin', 'dishubs'));
    }

    /**
     * Memperbarui data admin
     * 
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Admin $admin
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Admin $admin)
    {
        /**
         * Validasi data
         * - email harus unik kecuali milik admin yang sedang diedit
         * - password opsional (boleh kosong)
         */
        $request->validate([
            'nama'      => 'required|string|max:255',
            'email'     => 'required|email|unique:admins,email,' . $admin->id,
            'role'      => 'required|in:admin,superadmin',
            'dishub_id' => 'required|exists:dishubs,id',
            'password'  => 'nullable|min:6',
            'nrp'       => 'nullable|string|max:50',
            'pangkat'   => 'nullable|string',
        ]);

        /**
         * Data yang akan diupdate
         */
        $data = [
            'nama'      => $request->nama,
            'email'     => $request->email,
            'nrp'       => $request->nrp,
            'pangkat'   => $request->pangkat,
            'role'      => $request->role,
            'dishub_id' => $request->dishub_id,
        ];

        /**
         * Update password hanya jika diisi
         */
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        /**
         * Simpan perubahan ke database
         */
        $admin->update($data);

        return redirect()->route('admins.index')
            ->with('success', 'Data petugas berhasil diperbarui.');
    }

    /**
     * Menghapus akun admin
     * 
     * @param \App\Models\Admin $admin
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Admin $admin)
    {
        /**
         * Proteksi:
         * - Admin tidak boleh menghapus dirinya sendiri
         * - Mencegah manipulasi melalui URL/manual request
         */
        if ($admin->id === Auth::guard('admin')->id()) {
            return redirect()->route('admins.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        /**
         * Hapus data admin
         */
        $admin->delete();

        return redirect()->route('admins.index')
            ->with('success', 'Akun petugas telah dihapus.');
    }
}