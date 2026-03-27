{{-- Mewarisi kerangka layout utama aplikasi --}}
@extends('layouts.app')

{{-- Membuka bagian konten utama --}}
@section('content')

{{-- Card Container: Membungkus tabel dengan gaya modern (bayangan halus dan tanpa border tebal) --}}
<div class="card shadow-sm border-0">
    
    {{-- HEADER KARTU: Berisi Judul dan Tombol Aksi Utama --}}
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-geo-alt me-2"></i>Daftar Wilayah Dishub</h5>
        
        {{-- 
          OTORISASI TAMPILAN (Role-Based UI)
          Mengecek apakah user yang sedang login di guard 'admin' memiliki role 'superadmin'.
          Jika iya, maka tombol "Tambah Wilayah" akan ditampilkan. Jika admin biasa, tombol ini disembunyikan.
        --}}
        @if(Auth::guard('admin')->user()->role === 'superadmin')
            <a href="{{ route('dishubs.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> Tambah Wilayah
            </a>
        @endif
    </div>
    
    <div class="card-body">
        {{-- table-responsive: Membuat tabel bisa di-scroll ke samping di layar HP (Mobile Friendly) --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nama Wilayah</th>
                        <th>Singkatan</th>
                        <th>Provinsi</th>
                        <th>Kepala Dinas</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Looping untuk menampilkan setiap baris data wilayah dishub --}}
                    @foreach($dishubs as $dishub)
                    <tr>
                        <td class="fw-bold">{{ $dishub->nama }}</td>
                        <td><span class="badge bg-secondary">{{ $dishub->singkatan }}</span></td>
                        <td>{{ $dishub->provinsi }}</td>
                        <td>{{ $dishub->kepala_dinas_nama }}</td>
                        
                        {{-- KOLOM AKSI --}}
                        <td class="text-center">
                            
                            {{-- 
                              TOMBOL TRIGGER MODAL (Bisa diakses oleh semua admin)
                              Atribut data-bs-target menunjuk ke ID modal yang spesifik (#modalDetail + ID Dishub).
                              Hal ini memastikan modal yang terbuka memuat data yang sesuai dengan baris yang di-klik.
                            --}}
                            <button type="button" class="btn btn-info btn-sm text-white" data-bs-toggle="modal" data-bs-target="#modalDetail{{ $dishub->id }}" title="Detail">
                                <i class="bi bi-eye"></i>
                            </button>

                            {{-- 
                              OTORISASI TOMBOL EDIT & HAPUS
                              Sama seperti tombol Tambah, hanya 'superadmin' yang berhak melihat dan melakukan Edit/Hapus.
                            --}}
                            @if(Auth::guard('admin')->user()->role === 'superadmin')
                                
                                {{-- Tombol menuju halaman Form Edit --}}
                                <a href="{{ route('dishubs.edit', $dishub->id) }}" class="btn btn-warning btn-sm text-white" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                
                                {{-- 
                                  Form Hapus Data
                                  Menggunakan method POST lalu di-spoof menjadi DELETE.
                                  Event onsubmit memunculkan peringatan JS sebelum data benar-benar dihapus.
                                --}}
                                <form action="{{ route('dishubs.destroy', $dishub->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus wilayah ini?')">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                                </form>
                            @endif
                        </td>
                    </tr>

                    {{-- 
                      START MODAL DETAIL 
                      Catatan Teknis: Modal ini berada DI DALAM looping @foreach. 
                      Artinya, jika ada 100 data dishub, HTML akan membuat 100 script modal yang disembunyikan.
                      Atribut id="modalDetail{{ $dishub->id }}" adalah kunci agar ID modal ini unik per baris data.
                    --}}
                    <div class="modal fade" id="modalDetail{{ $dishub->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content border-0 shadow">
                                
                                {{-- Bagian Atas Modal --}}
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title"><i class="bi bi-building me-2"></i>Profil {{ $dishub->singkatan }}</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                
                                {{-- Isi Data Modal --}}
                                <div class="modal-body p-4">
                                    <div class="row">
                                        {{-- Kolom Kiri: Info Wilayah --}}
                                        <div class="col-md-6 border-end">
                                            <p class="text-muted mb-1 small fw-bold">NAMA INSTANSI</p>
                                            <p class="fw-bold text-primary">{{ $dishub->nama }}</p>
                                            
                                            <p class="text-muted mb-1 small fw-bold">LOKASI</p>
                                            <p class="mb-0">{{ $dishub->kecamatan }}, {{ $dishub->kota }}</p>
                                            <p>{{ $dishub->provinsi }}</p>
                                        </div>
                                        
                                        {{-- Kolom Kanan: Info Pejabat --}}
                                        {{-- Menggunakan null coalescing (?? '-') untuk menghindari error jika data Direktur kosong --}}
                                        <div class="col-md-6 px-4">
                                            <p class="text-muted mb-1 small fw-bold">KEPALA DINAS</p>
                                            <p class="fw-bold mb-0">{{ $dishub->kepala_dinas_nama }}</p>
                                            <p class="small text-muted mb-3">NIP: {{ $dishub->kepala_dinas_nip }}</p>

                                            <p class="text-muted mb-1 small fw-bold">DIREKTUR</p>
                                            <p class="fw-bold mb-0">{{ $dishub->direktur_nama ?? '-' }}</p>
                                            <p class="small text-muted">NIP: {{ $dishub->direktur_nip ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                {{-- Bagian Bawah Modal (Tombol Tutup & Edit Lanjutan) --}}
                                <div class="modal-footer bg-light py-2">
                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                                    
                                    {{-- Otorisasi lagi di dalam modal: Hanya superadmin yang bisa melihat tombol jalan pintas untuk edit --}}
                                    @if(Auth::guard('admin')->user()->role === 'superadmin')
                                        <a href="{{ route('dishubs.edit', $dishub->id) }}" class="btn btn-warning btn-sm text-white">Edit Wilayah</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- END MODAL --}}

                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection