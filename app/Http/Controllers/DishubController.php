<?php

namespace App\Http\Controllers;

use App\Models\Dishub;
use Illuminate\Http\Request;

/**
 * Class DishubController
 * 
 * Controller ini digunakan untuk mengelola data Dishub (wilayah/cabang),
 * meliputi:
 * - Menampilkan daftar Dishub
 * - Menambah data Dishub
 * - Mengedit data Dishub
 * - Menghapus data Dishub
 * 
 * Catatan:
 * - Akses tertentu dibatasi hanya untuk Superadmin
 */
class DishubController extends Controller
{
    /**
     * Middleware manual untuk memastikan hanya superadmin yang bisa mengakses
     * fitur tertentu (create, update, delete)
     * 
     * Jika bukan superadmin, akan mengembalikan HTTP 403 (Forbidden)
     * 
     * @return void
     */
    private function checkSuperadmin()
    {
        if (auth()->guard('admin')->user()->role !== 'superadmin') {
            abort(403, 'Akses hanya untuk Superadmin.');
        }
    }

    /**
     * Menampilkan daftar semua data Dishub
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        /**
         * Ambil semua data Dishub
         * - diurutkan berdasarkan data terbaru
         */
        $dishubs = Dishub::latest()->get();

        return view('dishubs.index', compact('dishubs'));
    }

    /**
     * Menampilkan form tambah Dishub
     * 
     * Hanya bisa diakses oleh superadmin
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        /**
         * Validasi akses superadmin
         */
        $this->checkSuperadmin();

        return view('dishubs.create');
    }

    /**
     * Menyimpan data Dishub baru
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        /**
         * Validasi akses superadmin
         */
        $this->checkSuperadmin();

        /**
         * Validasi input
         * - nama harus unik
         * - singkatan dan provinsi wajib diisi
         */
        $request->validate([
            'nama' => 'required|unique:dishubs,nama',
            'singkatan' => 'required',
            'provinsi' => 'required',
        ]);

        /**
         * Simpan data ke database
         * - menggunakan mass assignment ($request->all())
         * - pastikan field sudah diatur di $fillable model
         */
        Dishub::create($request->all());

        return redirect()->route('dishubs.index')
            ->with('success', 'Data Wilayah Dishub berhasil ditambahkan');
    }

    /**
     * Menampilkan detail satu data Dishub
     * 
     * @param \App\Models\Dishub $dishub
     * @return \Illuminate\View\View
     */
    public function show(Dishub $dishub)
    {
        /**
         * Route Model Binding:
         * - Laravel otomatis mengambil data berdasarkan ID dari route
         */
        return view('dishubs.show', compact('dishub'));
    }

    /**
     * Menampilkan form edit Dishub
     * 
     * Hanya bisa diakses oleh superadmin
     * 
     * @param \App\Models\Dishub $dishub
     * @return \Illuminate\View\View
     */
    public function edit(Dishub $dishub)
    {
        /**
         * Validasi akses superadmin
         */
        $this->checkSuperadmin();

        return view('dishubs.edit', compact('dishub'));
    }

    /**
     * Memperbarui data Dishub
     * 
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Dishub $dishub
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Dishub $dishub)
    {
        /**
         * Validasi akses superadmin
         */
        $this->checkSuperadmin();

        /**
         * Validasi input
         * - nama harus unik, kecuali data yang sedang diedit
         */
        $request->validate([
            'nama' => 'required|unique:dishubs,nama,' . $dishub->id,
            'singkatan' => 'required',
            'provinsi' => 'required',
        ]);

        /**
         * Update data ke database
         * - menggunakan mass assignment
         */
        $dishub->update($request->all());

        return redirect()->route('dishubs.index')
            ->with('success', 'Data Wilayah berhasil diupdate');
    }

    /**
     * Menghapus data Dishub
     * 
     * Hanya bisa diakses oleh superadmin
     * 
     * @param \App\Models\Dishub $dishub
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Dishub $dishub)
    {
        /**
         * Validasi akses superadmin
         */
        $this->checkSuperadmin();

        /**
         * Hapus data dari database
         */
        $dishub->delete();

        return back()->with('success', 'Data Wilayah dihapus');
    }
}