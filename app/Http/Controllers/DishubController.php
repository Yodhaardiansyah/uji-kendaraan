<?php

namespace App\Http\Controllers;

use App\Models\Dishub;
use Illuminate\Http\Request;

class DishubController extends Controller
{
    private function checkSuperadmin()
    {
        if (auth()->guard('admin')->user()->role !== 'superadmin') {
            abort(403, 'Akses hanya untuk Superadmin.');
        }
    }

    public function index()
    {
        $dishubs = Dishub::latest()->get();
        return view('dishubs.index', compact('dishubs'));
    }

    public function create()
    {
        $this->checkSuperadmin();
        return view('dishubs.create');
    }

    public function store(Request $request)
    {
        $this->checkSuperadmin();

        $request->validate([
            'nama' => 'required|unique:dishubs,nama',
            'singkatan' => 'required',
            'provinsi' => 'required',
        ]);

        Dishub::create($request->all());

        return redirect()->route('dishubs.index')
            ->with('success', 'Data Wilayah Dishub berhasil ditambahkan');
    }
    public function show(Dishub $dishub)
    {
        // Mengambil data wilayah tertentu berdasarkan ID ($dishub)
        return view('dishubs.show', compact('dishub'));
    }

    public function edit(Dishub $dishub)
    {
        $this->checkSuperadmin();
        return view('dishubs.edit', compact('dishub'));
    }

    public function update(Request $request, Dishub $dishub)
    {
        $this->checkSuperadmin();

        $request->validate([
            'nama' => 'required|unique:dishubs,nama,' . $dishub->id,
            'singkatan' => 'required',
            'provinsi' => 'required',
        ]);

        $dishub->update($request->all());

        return redirect()->route('dishubs.index')
            ->with('success', 'Data Wilayah berhasil diupdate');
    }

    public function destroy(Dishub $dishub)
    {
        $this->checkSuperadmin();

        $dishub->delete();
        return back()->with('success', 'Data Wilayah dihapus');
    }
}