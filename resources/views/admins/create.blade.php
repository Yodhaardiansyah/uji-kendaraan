{{-- Mewarisi struktur layout utama dari file 'resources/views/layouts/app.blade.php' --}}
@extends('layouts.app')

{{-- Bagian konten utama yang akan disisipkan ke dalam '@yield("content")' pada layout --}}
@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        
        {{-- Card Container: Membungkus form agar tampil lebih rapi dengan efek bayangan (shadow-sm) --}}
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold text-primary"><i class="bi bi-person-plus-fill me-2"></i>Registrasi Admin / Penguji Baru</h6>
            </div>
            
            <div class="card-body p-4">
                {{-- 
                  Form pengisian data admin baru.
                  action: Mengarahkan data ke route 'admins.store' saat tombol submit ditekan.
                  method: Menggunakan POST karena ini adalah proses penyimpanan data baru ke database.
                --}}
                <form action="{{ route('admins.store') }}" method="POST">
                    
                    {{-- 
                      @csrf (Cross-Site Request Forgery) 
                      WAJIB disertakan dalam setiap form ber-method POST/PUT/DELETE di Laravel 
                      sebagai token keamanan untuk mencegah pengiriman data dari situs luar.
                    --}}
                    @csrf
                    
                    <div class="row g-3">
                        
                        {{-- Input untuk Nama Lengkap (Wajib diisi) --}}
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" placeholder="Nama Lengkap & Gelar" required>
                        </div>
                        
                        {{-- Input untuk Email yang nantinya akan digunakan sebagai username login (Wajib diisi) --}}
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Email (Username Login)</label>
                            <input type="email" name="email" class="form-control" placeholder="nama@email.com" required>
                        </div>
                        
                        {{-- Input untuk Nomor Registrasi Penguji (NRP). Sifatnya opsional (tidak ada atribut required) --}}
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">NRP (Nomor Registrasi Penguji)</label>
                            <input type="text" name="nrp" class="form-control" placeholder="Contoh: 1980xxxx">
                        </div>
                        
                        {{-- 
                          Dropdown statis untuk memilih Pangkat Penguji.
                          Sifatnya opsional karena bisa jadi admin yang didaftarkan 
                          bukanlah seorang penguji (misal: admin pendaftaran biasa).
                        --}}
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Pangkat Penguji</label>
                            <select name="pangkat" class="form-select">
                                <option value="">-- Pilih Pangkat --</option>
                                <option value="Pembantu Penguji">Pembantu Penguji</option>
                                <option value="Penguji Pemula">Penguji Pemula</option>
                                <option value="Penguji Tingkat Satu">Penguji Tingkat Satu</option>
                                <option value="Penguji Tingkat Dua">Penguji Tingkat Dua</option>
                                <option value="Penguji Tingkat Tiga">Penguji Tingkat Tiga</option>
                                <option value="Penguji Tingkat Empat">Penguji Tingkat Empat</option>
                                <option value="Penguji Tingkat Lima">Penguji Tingkat Lima</option>
                                <option value="Master Penguji">Master Penguji</option>
                            </select>
                        </div>
                        
                        {{-- 
                          Dropdown untuk menentukan Role (Hak Akses). 
                          Wajib diisi untuk membedakan apa saja menu yang bisa diakses user ini nantinya.
                        --}}
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Role Akses</label>
                            <select name="role" class="form-select" required>
                                <option value="admin">Admin Wilayah (Petugas)</option>
                                <option value="superadmin">Super Admin (Pusat)</option>
                            </select>
                        </div>
                        
                        {{-- 
                          Dropdown dinamis untuk Penempatan Dishub.
                          Menghubungkan akun admin ini dengan id cabang Dishub tertentu di database.
                        --}}
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Penempatan Dishub</label>
                            <select name="dishub_id" class="form-select" required>
                                <option value="">-- Pilih Lokasi Tugas --</option>
                                
                                {{-- 
                                  Melakukan looping data $dishubs yang dikirim dari AdminController@create.
                                  Menjadikan ID dishub sebagai 'value' yang disimpan, dan nama dishub sebagai teks yang tampil.
                                --}}
                                @foreach($dishubs as $d)
                                    <option value="{{ $d->id }}">{{ $d->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        {{-- 
                          Input untuk password awal admin.
                          Sebaiknya dibuat dengan minimal karakter tertentu (misal: 6 atau 8 karakter) 
                          sesuai validasi di Controller.
                        --}}
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Password Default</label>
                            <input type="password" name="password" class="form-control" required placeholder="Minimal 6 Karakter">
                        </div>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="mt-4 pt-3 border-top text-end">
                        {{-- Tombol Batal: Mengembalikan user ke halaman daftar admin (admins.index) tanpa menyimpan --}}
                        <a href="{{ route('admins.index') }}" class="btn btn-light px-4">Batal</a>
                        
                        {{-- Tombol Submit: Memproses form dan mengirim data via POST ke route tujuan --}}
                        <button type="submit" class="btn btn-primary px-4">Simpan Akun Admin</button>
                    </div>
                </form>
            </div>
        </div>
        
    </div>
</div>
@endsection