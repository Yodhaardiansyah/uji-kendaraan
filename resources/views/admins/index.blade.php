{{-- 
  Mewarisi (inherit) struktur template utama dari file 'resources/views/layouts/app.blade.php'.
  Semua konten di dalam file ini akan disisipkan ke layout utama tersebut.
--}}
@extends('layouts.app')

{{-- Menentukan nilai/judul untuk bagian '@yield("title")' di layout utama --}}
@section('title', 'Manajemen Admin - Dishub System')

{{-- Membuka bagian/section konten yang akan dimasukkan ke dalam '@yield("content")' di layout utama --}}
@section('content')

{{-- ================= HEADER & TOMBOL TAMBAH ================= --}}
{{-- Container flex untuk menata judul di kiri dan tombol di kanan --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0 text-primary"><i class="bi bi-shield-lock me-2"></i>Manajemen Admin & Superadmin</h4>
    
    {{-- Tombol untuk menuju ke halaman form tambah admin baru (method create di AdminController) --}}
    <a href="{{ route('admins.create') }}" class="btn btn-primary shadow-sm">
        <i class="bi bi-person-plus-fill me-1"></i> Tambah Admin
    </a>
</div>

{{-- ================= FORM PENCARIAN ================= --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        {{-- 
          Form pencarian mengirimkan request GET ke route 'admins.index'.
          Artinya, hasil pencarian akan ditangani oleh method index pada AdminController.
        --}}
        <form action="{{ route('admins.index') }}" method="GET" class="d-flex gap-2">
            <div class="input-group shadow-sm">
                <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-search"></i></span>
                
                {{-- 
                  Input pencarian. 
                  value="{{ request('search') }}" digunakan agar teks yang baru saja diketik 
                  tidak hilang/terhapus setelah tombol 'Cari' ditekan.
                --}}
                <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari Nama Admin, NRP, Email, atau Cabang Dishub..." value="{{ request('search') }}">
            </div>
            
            <button type="submit" class="btn btn-secondary px-4 shadow-sm fw-bold">Cari</button>
            
            {{-- 
              Tombol Reset hanya akan muncul JIKA ada parameter 'search' di URL.
              Tombol ini pada dasarnya mengarahkan kembali ke halaman index tanpa parameter (kondisi awal).
            --}}
            @if(request('search'))
                <a href="{{ route('admins.index') }}" class="btn btn-outline-danger shadow-sm"><i class="bi bi-x-circle"></i> Reset</a>
            @endif
        </form>
    </div>
</div>

{{-- ================= DAFTAR ADMIN (GROUPED BY DISHUB) ================= --}}
{{-- Menggunakan komponen Accordion (buka-tutup) dari Bootstrap --}}
<div class="accordion shadow-sm" id="accordionAdmins">
    
    {{-- 
      @forelse akan me-looping data wilayah/cabang ($dishubs). 
      Jika data kosong, kode akan melompat langsung ke bagian @empty di bawah.
    --}}
    @forelse($dishubs as $dishub)
        
        {{-- 
          Mendefinisikan variabel lokal:
          - $branchAdmins: Mengambil relasi data admin yang terhubung ke dishub ini.
          - $collapseId: Membuat ID unik untuk efek buka-tutup accordion (misal: collapseDishub1).
        --}}
        @php 
            $branchAdmins = $dishub->admins; 
            $collapseId = 'collapseDishub' . $dishub->id;
        @endphp

        <div class="accordion-item border-0 mb-3 rounded-3 overflow-hidden">
            
            {{-- HEADER ACCORDION (Bagian yang bisa di-klik untuk buka/tutup) --}}
            <h2 class="accordion-header" id="heading{{ $collapseId }}">
                {{-- Atribut data-bs-target menggunakan variabel unik $collapseId --}}
                <button class="accordion-button collapsed bg-white fw-bold text-dark py-3" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}" aria-expanded="false">
                    <div class="d-flex align-items-center w-100 pe-3">
                        <div class="bg-light p-2 rounded-circle me-3">
                            <i class="bi bi-building fs-5 text-primary"></i>
                        </div>
                        
                        {{-- Menampilkan Nama dan Singkatan Dishub --}}
                        <div>
                            <div class="fs-6">{{ $dishub->nama ?? 'Dishub Tidak Diketahui' }}</div>
                            <small class="text-muted fw-normal"><i class="bi bi-geo-alt me-1"></i>{{ $dishub->singkatan ?? '-' }}</small>
                        </div>
                        
                        {{-- Bagian Lencana (Badge) Indikator Jumlah Admin --}}
                        <div class="ms-auto d-flex gap-2 align-items-center">
                            
                            {{-- Menghitung spesifik berapa admin di cabang ini yang memiliki role 'superadmin' --}}
                            @php $superCount = $branchAdmins->where('role', 'superadmin')->count(); @endphp
                            
                            {{-- Jika ada superadmin, tampilkan badge merah --}}
                            @if($superCount > 0)
                                <span class="badge bg-danger rounded-pill px-3 py-2 shadow-sm">{{ $superCount }} Superadmin</span>
                            @endif
                            
                            {{-- Menampilkan total keseluruhan admin di cabang tersebut --}}
                            <span class="badge bg-primary rounded-pill px-3 py-2 shadow-sm">{{ $branchAdmins->count() }} Total Admin</span>
                        </div>
                    </div>
                </button>
            </h2>
            
            {{-- BODY ACCORDION (Isi tabel admin yang akan muncul saat header di-klik) --}}
            <div id="{{ $collapseId }}" class="accordion-collapse collapse" data-bs-parent="#accordionAdmins">
                <div class="accordion-body p-0 border-top border-light">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4 py-3" style="width: 30%;">Nama / NRP</th>
                                    <th style="width: 25%;">Email</th>
                                    <th style="width: 20%;">Pangkat</th>
                                    <th style="width: 10%;">Role</th>
                                    <th class="text-center" style="width: 15%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Looping untuk menampilkan setiap admin di dalam cabang Dishub ini --}}
                                @foreach($branchAdmins as $admin)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold text-dark">{{ $admin->nama }}</div>
                                            <small class="text-muted">NRP: {{ $admin->nrp ?? '-' }}</small>
                                        </td>
                                        <td>{{ $admin->email }}</td>
                                        <td><small class="text-muted fw-bold">{{ $admin->pangkat ?? '-' }}</small></td>
                                        <td>
                                            {{-- Penyesuaian warna badge berdasarkan role admin --}}
                                            <span class="badge {{ $admin->role == 'superadmin' ? 'bg-danger' : 'bg-success' }} shadow-sm">
                                                {{ strtoupper($admin->role) }}
                                            </span>
                                        </td>
                                        
                                        {{-- KOLOM AKSI (Edit & Hapus) --}}
                                        <td class="text-center text-nowrap">
                                            <div class="d-flex justify-content-center align-items-center gap-1">
                                                
                                                {{-- Tombol menuju halaman form edit (method edit di AdminController) --}}
                                                <a href="{{ route('admins.edit', $admin->id) }}" class="btn btn-warning btn-sm text-dark shadow-sm" title="Edit Data">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                
                                                {{-- 
                                                  Form untuk menghapus data (method destroy di AdminController).
                                                  Di HTML standar form tidak mensupport method DELETE, 
                                                  maka digunakan @method('DELETE') sebagai spoofing Laravel.
                                                  @csrf WAJIB ada di setiap form POST/PUT/DELETE untuk keamanan.
                                                  Event onsubmit() memunculkan pop-up konfirmasi javascript sebelum eksekusi.
                                                --}}
                                                <form action="{{ route('admins.destroy', $admin->id) }}" method="POST" class="m-0 p-0 d-flex" onsubmit="return confirm('Yakin ingin menghapus admin ini secara permanen?')">
                                                    @csrf 
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm shadow-sm" title="Hapus Akun">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
    {{-- Kondisi ini tereksekusi JIKA data $dishubs kosong (misal karena hasil pencarian tidak ketemu) --}}
    @empty
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5 text-muted">
                <i class="bi bi-search fs-1 d-block mb-3"></i>
                
                {{-- Pesan error dibedakan: apakah kosong karena pencarian atau memang belum ada data sama sekali --}}
                @if(request('search'))
                    Data cabang atau admin dengan kata kunci "<b>{{ request('search') }}</b>" tidak ditemukan.
                @else
                    Belum ada data admin cabang lain yang terdaftar di sistem.
                @endif
            </div>
        </div>
    @endforelse
</div>

{{-- ================= PAGINATION ================= --}}
{{-- 
  Menampilkan tombol halaman (1, 2, 3, Next, Prev). 
  Method links() otomatis membuat UI pagination jika $dishubs adalah hasil dari paginate() di Controller.
--}}
<div class="d-flex justify-content-end mb-4 mt-3">
    {{ $dishubs->links() }}
</div>

{{-- Menutup section 'content' --}}
@endsection