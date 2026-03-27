{{-- Mewarisi kerangka layout utama dari 'layouts.app' --}}
@extends('layouts.app')

{{-- Membuka bagian konten utama --}}
@section('content')
<div class="row justify-content-center">
    {{-- Membatasi lebar kolom agar form terlihat proporsional di tengah layar --}}
    <div class="col-md-8">
        
        {{-- Container Kartu --}}
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold text-primary">Tambah User Baru</h6>
            </div>
            
            <div class="card-body">
                {{-- 
                  FORM PENDAFTARAN USER
                  action: Mengirim data ke route 'users.store' (UserController@store).
                  method: POST (digunakan untuk operasi pembuatan data baru).
                --}}
                <form action="{{ route('users.store') }}" method="POST">
                    {{-- @csrf: Token keamanan wajib Laravel untuk memvalidasi request form --}}
                    @csrf
                    
                    {{-- Input Nama Lengkap --}}
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    
                    <div class="row mb-3">
                        {{-- Input Email (Akan digunakan user untuk login di Area Pemilik) --}}
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        
                        {{-- Input NIK/No. KTP --}}
                        <div class="col-md-6">
                            <label class="form-label">No. Identitas (KTP/NIK)</label>
                            <input type="text" name="nomor_identitas" class="form-control" required>
                        </div>
                    </div>
                    
                    {{-- Input Alamat (Menggunakan Textarea untuk input teks yang lebih panjang) --}}
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="2"></textarea>
                    </div>
                    
                    {{-- 
                      Input Password
                      Pastikan pada Controller data ini dienkripsi menggunakan Hash::make() 
                      sebelum disimpan ke database.
                    --}}
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required placeholder="Minimal 6 karakter">
                    </div>
                    
                    {{-- Bagian Tombol Aksi --}}
                    <div class="text-end border-top pt-3">
                        {{-- Tombol Batal: Mengarahkan kembali ke daftar user --}}
                        <a href="{{ route('users.index') }}" class="btn btn-light">Batal</a>
                        
                        {{-- Tombol Simpan: Mengirimkan seluruh data form ke server --}}
                        <button type="submit" class="btn btn-primary px-4">Simpan User</button>
                    </div>
                </form>
            </div>
        </div>
        
    </div>
</div>
@endsection