{{-- Mewarisi kerangka layout aplikasi utama --}}
@extends('layouts.app')

{{-- Mengatur judul halaman --}}
@section('title', 'Pengaturan Akun')

{{-- Membuka section konten utama --}}
@section('content')
<div class="row justify-content-center">
    
    {{-- 
      Membatasi lebar form menjadi setengah layar (col-md-6) pada tampilan desktop.
      Ini membuat form terlihat lebih fokus dan profesional dibandingkan jika membentang penuh.
    --}}
    <div class="col-md-6">
        
        {{-- Card Container dengan sudut yang lebih membulat (rounded-4) --}}
        <div class="card border-0 shadow-sm rounded-4">
            
            {{-- Header Card --}}
            <div class="card-header bg-white py-3 border-0">
                <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-gear-fill me-2"></i>Pengaturan Keamanan</h5>
            </div>
            
            <div class="card-body p-4">
                
                {{-- 
                  FORM UPDATE PROFIL
                  Aksi mengarah ke route 'profile.update'.
                  Method menggunakan POST yang kemudian diubah menjadi PUT via spoofing.
                --}}
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- ================= SEKSI UBAH EMAIL ================= --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold">Alamat Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                            
                            {{-- 
                              Validasi Error Inline:
                              Class '@error('email') is-invalid @enderror' akan otomatis menambahkan 
                              border merah pada input box jika validasi 'email' gagal di Controller.
                            --}}
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email', $user->email) }}" required>
                        </div>
                        
                        {{-- 
                          Menampilkan Pesan Error Spesifik:
                          Jika email sudah dipakai orang lain, atau formatnya salah, 
                          pesan error akan muncul tepat di bawah kolom ini.
                        --}}
                        @error('email')
                            <div class="small text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Garis Pemisah --}}
                    <hr class="my-4 opacity-25">

                    {{-- ================= SEKSI UBAH PASSWORD ================= --}}
                    
                    {{-- 
                      Input 1: Password Saat Ini (Wajib Diisi)
                      Sebagai fitur keamanan standar, sistem mengharuskan user memverifikasi 
                      identitas mereka dengan memasukkan password lama sebelum bisa mengubah apapun.
                    --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Password Saat Ini <span class="text-danger">*</span></label>
                        <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" 
                               placeholder="Konfirmasi password lama Anda" required>
                        
                        {{-- Pesan error jika password lama yang dimasukkan tidak cocok dengan database --}}
                        @error('current_password')
                            <div class="small text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- 
                      Input 2: Password Baru (Opsional)
                      Jika user hanya ingin ganti email, kolom ini dibiarkan kosong.
                    --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Password Baru (Opsional)</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                               placeholder="Kosongkan jika tidak ingin mengubah">
                        
                        {{-- Pesan error misal: "Password terlalu pendek" --}}
                        @error('password')
                            <div class="small text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- 
                      Input 3: Konfirmasi Password Baru
                      Atribut name WAJIB bernama "password_confirmation" agar fitur validasi
                      'confirmed' bawaan Laravel (di Controller) bisa bekerja secara otomatis 
                      mencocokkan kolom ini dengan kolom "password".
                    --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-control" 
                               placeholder="Ketik ulang password baru">
                    </div>

                    {{-- ================= AREA TOMBOL ================= --}}
                    {{-- d-grid gap-2: Membuat tombol menjadi full width (membentang penuh) dan berjejer rapi --}}
                    <div class="d-grid gap-2 mt-5">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold shadow-sm">
                            <i class="bi bi-check-circle me-1"></i> Simpan Perubahan
                        </button>
                        
                        {{-- 
                          Tombol Batal: Menggunakan trik JavaScript `history.back()` 
                          agar user dikembalikan ke halaman tempat mereka berasal (bisa dashboard admin atau dashboard user).
                        --}}
                        <a href="javascript:history.back()" class="btn btn-light rounded-pill px-4">Batal</a>
                    </div>
                </form>

            </div>
        </div>

        {{-- Kotak Informasi Bantuan (Help Alert) di bawah form --}}
        <div class="alert alert-info border-0 shadow-sm mt-4 rounded-4 small">
            <i class="bi bi-info-circle-fill me-2"></i>
            Demi keamanan, sistem mewajibkan Anda memasukkan password lama setiap kali melakukan perubahan email atau password.
        </div>
    </div>
</div>
@endsection