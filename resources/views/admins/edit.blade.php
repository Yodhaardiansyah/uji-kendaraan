{{-- Mewarisi kerangka utama website dari 'resources/views/layouts/app.blade.php' --}}
@extends('layouts.app')

{{-- Membuka section konten utama --}}
@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        
        {{-- Card Container: Membungkus form dengan tampilan bersih dan bayangan tipis --}}
        <div class="card shadow-sm border-0">
            
            {{-- Bagian Header Card --}}
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold text-primary"><i class="bi bi-pencil-square me-2"></i>Edit Akun Admin / Penguji</h6>
                
                {{-- Menampilkan ID Admin yang sedang diedit sebagai referensi visual --}}
                <span class="badge bg-secondary">ID: #{{ $admin->id }}</span>
            </div>
            
            <div class="card-body p-4">
                {{-- 
                  Form Edit Data.
                  action: Mengarah ke route 'admins.update' dengan membawa parameter ID admin.
                  method: Ditulis 'POST' karena HTML standar tidak mendukung form ber-method PUT/PATCH secara langsung.
                --}}
                <form action="{{ route('admins.update', $admin->id) }}" method="POST">
                    
                    {{-- @csrf: Token wajib untuk keamanan form dari serangan CSRF --}}
                    @csrf
                    
                    {{-- 
                      @method('PUT'): Fitur "Form Method Spoofing" dari Laravel.
                      Memberitahu sistem bahwa meskipun form ini menggunakan POST, 
                      request ini sebenarnya adalah metode PUT (untuk mengupdate data).
                    --}}
                    @method('PUT')
                    
                    <div class="row g-3">
                        
                        {{-- 
                          Input Nama Lengkap.
                          Fungsi old('nama', $admin->nama): 
                          1. Jika ada error validasi saat disubmit, kembalikan inputan user (old).
                          2. Jika baru pertama kali load halaman, tampilkan data dari database ($admin->nama).
                        --}}
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" value="{{ old('nama', $admin->nama) }}" required>
                        </div>
                        
                        {{-- Input Email --}}
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Email (Username Login)</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $admin->email) }}" required>
                        </div>
                        
                        {{-- Input NRP (Opsional) --}}
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">NRP</label>
                            <input type="text" name="nrp" class="form-control" value="{{ old('nrp', $admin->nrp) }}">
                        </div>
                        
                        {{-- 
                          Dropdown Pangkat Penguji.
                          Menggunakan array langsung di dalam foreach untuk menghemat penulisan kode HTML.
                        --}}
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Pangkat Penguji</label>
                            <select name="pangkat" class="form-select">
                                <option value="">-- Pilih Pangkat --</option>
                                
                                @foreach(['Pembantu Penguji', 'Penguji Pemula', 'Penguji Tingkat Satu', 'Penguji Tingkat Dua', 'Penguji Tingkat Tiga', 'Penguji Tingkat Empat', 'Penguji Tingkat Lima', 'Master Penguji'] as $p)
                                    {{-- 
                                      Ternary Operator (Kondisi ? Benar : Salah).
                                      Mengecek apakah pangkat dari database sama dengan pilihan saat ini.
                                      Jika sama, tambahkan atribut 'selected' agar langsung terpilih di dropdown.
                                    --}}
                                    <option value="{{ $p }}" {{ (old('pangkat', $admin->pangkat) == $p) ? 'selected' : '' }}>{{ $p }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        {{-- Dropdown Role Akses --}}
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Role Akses</label>
                            <select name="role" class="form-select" required>
                                {{-- Mengecek status role untuk auto-select --}}
                                <option value="admin" {{ (old('role', $admin->role) == 'admin') ? 'selected' : '' }}>Admin Wilayah</option>
                                <option value="superadmin" {{ (old('role', $admin->role) == 'superadmin') ? 'selected' : '' }}>Super Admin</option>
                            </select>
                        </div>
                        
                        {{-- Dropdown Relasi Penempatan Dishub --}}
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Penempatan Dishub</label>
                            <select name="dishub_id" class="form-select" required>
                                @foreach($dishubs as $d)
                                    {{-- Mengecek id dishub di database admin dengan id dishub di loop saat ini --}}
                                    <option value="{{ $d->id }}" {{ (old('dishub_id', $admin->dishub_id) == $d->id) ? 'selected' : '' }}>{{ $d->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        {{-- 
                          Input Ganti Password.
                          Sengaja dikosongkan (tanpa parameter value dan required).
                          Logikanya: Jika user mengetik sesuatu, maka password akan diubah. 
                          Jika dikosongkan, password lama tetap dipertahankan (Logika ini diatur di Controllernya nanti).
                        --}}
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Ganti Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin ganti">
                            <div class="form-text" style="font-size: 0.75rem;">Minimal 6 karakter jika ingin diubah.</div>
                        </div>
                    </div>

                    {{-- Area Tombol Submit & Batal --}}
                    <div class="mt-4 pt-3 border-top text-end">
                        <a href="{{ route('admins.index') }}" class="btn btn-light px-4">Batal</a>
                        <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection