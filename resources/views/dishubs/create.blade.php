{{-- Mewarisi struktur layout utama dari file 'resources/views/layouts/app.blade.php' --}}
@extends('layouts.app')

{{-- Bagian konten utama --}}
@section('content')

{{-- Menggunakan Grid System Bootstrap untuk memusatkan form (justify-content-center) --}}
<div class="row justify-content-center">
    
    {{-- Lebar kolom diset ke ukuran 10 dari 12 kolom agar tidak terlalu penuh ke pinggir layar --}}
    <div class="col-md-10">
        
        {{-- Card Container: Membungkus form dengan gaya bersih dan efek bayangan --}}
        <div class="card shadow-sm border-0">
            
            {{-- Header Kartu (Judul Form) --}}
            <div class="card-header bg-white py-3 text-primary fw-bold">
                <i class="bi bi-plus-circle me-2"></i>Tambah Wilayah Dishub Baru
            </div>
            
            <div class="card-body">
                
                {{-- 
                  FORM PENYIMPANAN DATA
                  action: Mengarahkan data yang disubmit ke route 'dishubs.store'.
                  method: Menggunakan POST karena operasi ini adalah untuk membuat/menyimpan data baru ke dalam database.
                --}}
                <form action="{{ route('dishubs.store') }}" method="POST">
                    
                    {{-- @csrf: Token keamanan wajib Laravel untuk mencegah celah keamanan eksploitasi form --}}
                    @csrf
                    
                    {{-- class 'g-3' (gap-3) dari Bootstrap berfungsi untuk memberi jarak spasi merata antar kolom input --}}
                    <div class="row g-3">
                        
                        {{-- ================= BAGIAN 1: DATA WILAYAH ================= --}}
                        {{-- Judul Seksi/Grup --}}
                        <div class="col-md-12">
                            <h6 class="text-muted border-bottom pb-2">Informasi Wilayah</h6>
                        </div>
                        
                        {{-- Input: Nama Lengkap Satuan Kerja --}}
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap Satuan Kerja</label>
                            <input type="text" name="nama" class="form-control" placeholder="Dishub Kota X" required>
                        </div>
                        
                        {{-- Input: Singkatan --}}
                        <div class="col-md-6">
                            <label class="form-label">Singkatan</label>
                            <input type="text" name="singkatan" class="form-control" placeholder="DISHUBKOTX" required>
                        </div>
                        
                        {{-- Input: Provinsi (Wajib diisi / required) --}}
                        <div class="col-md-4">
                            <label class="form-label">Provinsi</label>
                            <input type="text" name="provinsi" class="form-control" required>
                        </div>
                        
                        {{-- Input: Kota (Opsional) --}}
                        <div class="col-md-4">
                            <label class="form-label">Kota</label>
                            <input type="text" name="kota" class="form-control">
                        </div>
                        
                        {{-- Input: Kecamatan (Opsional) --}}
                        <div class="col-md-4">
                            <label class="form-label">Kecamatan</label>
                            <input type="text" name="kecamatan" class="form-control">
                        </div>

                        {{-- ================= BAGIAN 2: DATA PEJABAT ================= --}}
                        {{-- mt-4 (margin-top-4) memberikan jarak yang cukup jelas dari grup input sebelumnya --}}
                        <div class="col-md-12 mt-4">
                            <h6 class="text-muted border-bottom pb-2">Informasi Pejabat (Penandatangan)</h6>
                        </div>
                        
                        {{-- 
                          Grup input pejabat ini biasanya sifatnya dinamis dan bisa kosong jika pejabatnya belum dilantik.
                          Oleh karena itu, atribut 'required' sengaja tidak ditambahkan di HTML.
                        --}}
                        
                        {{-- Input: Nama Kepala Dinas --}}
                        <div class="col-md-6">
                            <label class="form-label">Nama Kepala Dinas</label>
                            <input type="text" name="kepala_dinas_nama" class="form-control">
                        </div>
                        
                        {{-- Input: NIP Kepala Dinas --}}
                        <div class="col-md-6">
                            <label class="form-label">NIP Kepala Dinas</label>
                            <input type="text" name="kepala_dinas_nip" class="form-control">
                        </div>
                        
                        {{-- Input: Nama Direktur --}}
                        <div class="col-md-6">
                            <label class="form-label">Nama Direktur</label>
                            <input type="text" name="direktur_nama" class="form-control">
                        </div>
                        
                        {{-- Input: NIP Direktur --}}
                        <div class="col-md-6">
                            <label class="form-label">NIP Direktur</label>
                            <input type="text" name="direktur_nip" class="form-control">
                        </div>
                    </div>

                    {{-- ================= AREA TOMBOL AKSI ================= --}}
                    {{-- text-end: Memaksa tombol untuk rata kanan --}}
                    <div class="mt-4 text-end">
                        
                        {{-- Tombol Batal: Mengembalikan user ke halaman utama (index) data Dishub --}}
                        <a href="{{ route('dishubs.index') }}" class="btn btn-light me-2">Batal</a>
                        
                        {{-- Tombol Submit --}}
                        <button type="submit" class="btn btn-primary px-4">Simpan Wilayah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection