{{-- Mewarisi kerangka layout utama aplikasi --}}
@extends('layouts.app')

{{-- Membuka blok konten utama --}}
@section('content')

{{-- ================= KARTU UTAMA ================= --}}
<div class="card shadow-sm border-0 mb-4">
    
    {{-- HEADER KARTU (Judul & Tombol Tambah) --}}
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-people me-2"></i>Manajemen User</h5>
        
        {{-- Tombol mengarah ke form pembuatan user baru (UserController@create) --}}
        <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm shadow-sm">
            <i class="bi bi-person-plus me-1"></i> Tambah User
        </a>
    </div>
    
    <div class="card-body">
        
        {{-- ================= FORM PENCARIAN ================= --}}
        {{-- 
          Menggunakan method GET agar parameter pencarian masuk ke URL (?search=keyword).
          Hal ini memudahkan user jika ingin membagikan link hasil pencarian ke orang lain.
        --}}
        <form action="{{ route('users.index') }}" method="GET" class="mb-4">
            <div class="input-group shadow-sm">
                <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-search"></i></span>
                
                {{-- value="{{ request('search') }}" memastikan kata kunci yang diketik tidak hilang setelah tombol Cari ditekan --}}
                <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari Nama, Email, NIK, atau Alamat..." value="{{ request('search') }}">
                
                <button class="btn btn-secondary px-4 fw-bold" type="submit">Cari</button>
                
                {{-- Tombol Reset (X) hanya muncul jika ada parameter 'search' di URL --}}
                @if(request('search'))
                    <a href="{{ route('users.index') }}" class="btn btn-outline-danger" title="Reset Pencarian">
                        <i class="bi bi-x-circle"></i>
                    </a>
                @endif
            </div>
        </form>

        {{-- ================= TABEL USER ================= --}}
        <div class="table-responsive border rounded">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Nama</th>
                        <th>Email</th>
                        <th>No. Identitas</th>
                        <th>Alamat</th>
                        <th class="text-center pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- 
                      Looping data user. Jika $users kosong, 
                      langsung lompat ke bagian @empty di bawah.
                    --}}
                    @forelse($users as $user)
                    <tr>
                        <td class="fw-bold ps-3">{{ $user->nama }}</td>
                        <td>{{ $user->email }}</td>
                        <td><span class="badge bg-light text-dark border">{{ $user->nomor_identitas }}</span></td>
                        
                        {{-- 
                          Str::limit() memotong teks alamat jika lebih dari 40 karakter.
                          Sangat berguna agar tabel tidak menjadi berantakan/terlalu tinggi 
                          jika ada user yang memasukkan alamat sangat panjang.
                        --}}
                        <td><small class="text-muted">{{ Str::limit($user->alamat, 40) }}</small></td>
                        
                        {{-- KOLOM AKSI --}}
                        <td class="text-center pe-3 text-nowrap">
                            <div class="d-flex justify-content-center gap-1">
                                
                                {{-- Tombol menuju form Edit --}}
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm text-dark shadow-sm" title="Edit User">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                
                                {{-- Form Delete dengan metode Spoofing (@method('DELETE')) --}}
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline m-0 p-0" onsubmit="return confirm('Hapus user ini secara permanen?')">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm shadow-sm" title="Hapus User">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    
                    {{-- Blok yang dieksekusi jika data tidak ditemukan --}}
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-search fs-1 d-block mb-3"></i>
                            
                            {{-- Pesan error yang dinamis tergantung apakah user sedang mencari sesuatu atau memang database masih kosong --}}
                            @if(request('search'))
                                User dengan kata kunci "<b>{{ request('search') }}</b>" tidak ditemukan.
                            @else
                                Belum ada data user yang terdaftar di sistem.
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ================= PAGINATION ================= --}}
<div class="d-flex justify-content-end mb-4">
    {{-- 
      withQueryString() adalah fungsi krusial di sini.
      Jika user mencari "Budi", lalu melihat hasil di halaman 2, 
      fungsi ini memastikan URL tetap menjadi "?search=Budi&page=2" 
      sehingga filter pencariannya tidak ter-reset.
    --}}
    {{ $users->withQueryString()->links('pagination::bootstrap-5') }}
</div>
@endsection