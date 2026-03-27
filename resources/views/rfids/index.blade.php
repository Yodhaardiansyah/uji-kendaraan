{{-- Mewarisi kerangka utama aplikasi --}}
@extends('layouts.app')

{{-- Mengatur judul halaman --}}
@section('title', 'Data RFID - Dishub System')

{{-- Membuka blok konten utama --}}
@section('content')

{{-- ================= HEADER HALAMAN ================= --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0 text-primary"><i class="bi bi-credit-card-2-front me-2"></i>Manajemen Kartu RFID</h4>
    
    {{-- 
      PENGECEKAN ROLE
      Hanya Admin yang melihat tombol "Daftarkan Kartu Baru".
      User biasa (Pemilik) hanya bisa melihat riwayat kartu mereka sendiri.
    --}}
    @if(Auth::guard('admin')->check())
        {{-- Tombol ini akan membuka (trigger) modal dengan ID #modalTambahRfid yang ada di bawah --}}
        <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahRfid">
            <i class="bi bi-plus-circle me-1"></i> Daftarkan Kartu Baru
        </button>
    @endif
</div>

{{-- ================= FORM PENCARIAN & FILTER ================= --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        
        {{-- Form GET yang mengarah ke index untuk mem-filter data --}}
        <form action="{{ route('rfids.index') }}" method="GET" class="row g-2">
            
            {{-- 
              INPUT PENCARIAN GLOBAL
              Ukuran kolom responsif: Jika admin (ada filter pemilik) lebarnya col-5, 
              jika user biasa (tidak ada filter) lebarnya jadi col-10.
            --}}
            <div class="col-md-{{ Auth::guard('admin')->check() ? '5' : '10' }}">
                <div class="input-group shadow-sm h-100">
                    <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari Kode RFID, No Uji, Plat, NIK, Nama..." value="{{ request('search') }}">
                </div>
            </div>

            {{-- 
              FILTER PEMILIK (Khusus Admin)
              Memungkinkan admin mencari RFID berdasarkan satu nama user/pemilik kendaraan.
            --}}
            @if(Auth::guard('admin')->check())
            <div class="col-md-4">
                {{-- Elemen <select> ini akan dimodifikasi oleh library Select2 di bagian script bawah --}}
                <select name="user_id" id="filterPemilik" class="form-select shadow-sm h-100">
                    <option value="">-- Filter Semua Pemilik --</option>
                    @foreach($usersList as $u)
                        <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                            {{ $u->nama }} (NIK: {{ $u->nomor_identitas }})
                        </option>
                    @endforeach
                </select>
            </div>
            @endif

            {{-- AREA TOMBOL AKSI --}}
            <div class="col-md-{{ Auth::guard('admin')->check() ? '3' : '2' }} d-flex gap-2">
                <button type="submit" class="btn btn-secondary shadow-sm fw-bold grow w-100">
                    <i class="bi bi-search me-1"></i> Cari
                </button>
                
                {{-- Tombol 'Reset' (X merah) hanya muncul jika form pencarian sedang diisi sesuatu --}}
                @if(request('search') || request('user_id'))
                    <a href="{{ route('rfids.index') }}" class="btn btn-outline-danger shadow-sm" title="Reset Pencarian">
                        <i class="bi bi-x-circle"></i>
                    </a>
                @endif
            </div>
        </form>
        
    </div>
</div>

{{-- ================= DAFTAR KENDARAAN & LOG KARTUNYA (ACCORDION) ================= --}}
<div class="accordion shadow-sm" id="accordionRfids">
    
    {{-- Looping melalui setiap Kendaraan (Vehicle) --}}
    @forelse($vehicles as $vehicle)
        @php 
            // Ambil semua RFID yang pernah ditugaskan ke kendaraan ini, urutkan dari yang terbaru (sortByDesc)
            $rfids = $vehicle->rfids->sortByDesc('created_at');
            // ID unik untuk sistem buka-tutup Accordion
            $collapseId = 'collapseVehicle' . $vehicle->id;
        @endphp

        <div class="accordion-item border-0 mb-3 rounded-3 overflow-hidden">
            
            {{-- HEADER ACCORDION (Baris yang bisa di-klik) --}}
            <h2 class="accordion-header" id="heading{{ $collapseId }}">
                <button class="accordion-button collapsed bg-white fw-bold text-dark py-3" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}" aria-expanded="false">
                    <div class="d-flex align-items-center w-100 pe-3">
                        <div class="bg-light p-2 rounded-circle me-3">
                            <i class="bi bi-truck fs-5 text-primary"></i>
                        </div>
                        
                        {{-- Data Utama Kendaraan --}}
                        <div>
                            <div class="fs-6 text-uppercase">{{ $vehicle->no_kendaraan }} <span class="text-muted mx-1">|</span> {{ $vehicle->no_uji }}</div>
                            <small class="text-muted fw-normal">
                                <i class="bi bi-person me-1"></i>Pemilik: <b>{{ $vehicle->user->nama ?? '-' }}</b> 
                                {{-- d-none d-md-inline: Sembunyikan NIK di HP agar tidak terlalu panjang --}}
                                <span class="ms-2 d-none d-md-inline small">(NIK: {{ $vehicle->user->nomor_identitas ?? '-' }})</span>
                            </small>
                        </div>
                        
                        {{-- Lencana/Badge Statistik di Sebelah Kanan --}}
                        <div class="ms-auto d-flex gap-2 align-items-center">
                            {{-- Menghitung spesifik berapa kartu yang statusnya is_active == true (seharusnya hanya 1 per kendaraan) --}}
                            @php $activeCount = $rfids->where('is_active', true)->count(); @endphp
                            
                            @if($activeCount > 0)
                                <span class="badge bg-success rounded-pill px-3 py-2 shadow-sm">{{ $activeCount }} Aktif</span>
                            @endif
                            <span class="badge bg-secondary rounded-pill px-3 py-2 shadow-sm">{{ $rfids->count() }} Kartu</span>
                        </div>
                    </div>
                </button>
            </h2>
            
            {{-- BODY ACCORDION (Isi Tabel Sejarah RFID Kendaraan) --}}
            <div id="{{ $collapseId }}" class="accordion-collapse collapse" data-bs-parent="#accordionRfids">
                <div class="accordion-body p-0 border-top border-light">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4 py-3">Kode Kartu RFID</th>
                                    <th>Status</th>
                                    <th>Tanggal Aktivasi</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Looping Riwayat Kartu per Kendaraan --}}
                                @foreach($rfids as $rfid)
                                    {{-- Jika tidak aktif, baris diredupkan dengan class text-muted --}}
                                    <tr class="{{ $rfid->is_active ? '' : 'text-muted' }}">
                                        
                                        <td class="ps-4 fw-bold {{ $rfid->is_active ? 'text-primary' : 'text-secondary' }}">
                                            <i class="bi bi-upc-scan me-1"></i> {{ $rfid->kode_rfid }}
                                        </td>
                                        
                                        <td>
                                            @if($rfid->is_active)
                                                <span class="badge bg-success shadow-sm">Aktif</span>
                                            @else
                                                <span class="badge bg-secondary shadow-sm">Non-Aktif</span>
                                            @endif
                                        </td>
                                        
                                        <td>{{ $rfid->created_at->format('d M Y, H:i') }}</td>
                                        
                                        {{-- KOLOM AKSI --}}
                                        <td class="text-center text-nowrap">
                                            <div class="d-flex justify-content-center align-items-center gap-1">
                                                
                                                {{-- Tombol Aksi Khusus Admin --}}
                                                @if(Auth::guard('admin')->check())
                                                    {{-- Tombol Lihat Riwayat Uji (Semua kartu, baik aktif maupun tidak) --}}
                                                    <a href="{{ route('inspections.index', $rfid->id) }}" class="btn btn-sm btn-info text-white shadow-sm" title="Lihat Daftar Uji">
                                                        <i class="bi bi-journal-text"></i>
                                                    </a>
                                                    
                                                    {{-- Tombol Toggle Status (Nyala/Mati) --}}
                                                    <form action="{{ route('rfids.toggle', $rfid->id) }}" method="POST" class="m-0 p-0 d-flex">
                                                        @csrf @method('PATCH')
                                                        {{-- Warnanya otomatis berubah: Merah jika sedang aktif (untuk dimatikan), Hijau jika sedang mati (untuk dinyalakan) --}}
                                                        <button type="submit" class="btn btn-sm shadow-sm {{ $rfid->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}" title="Ubah Status">
                                                            <i class="bi bi-power"></i>
                                                        </button>
                                                    </form>
                                                    
                                                    {{-- Tombol Hapus Permanen --}}
                                                    <form action="{{ route('rfids.destroy', $rfid->id) }}" method="POST" class="m-0 p-0 d-flex" onsubmit="return confirm('Hapus Permanen Data Kartu Ini?')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-secondary btn-sm shadow-sm"><i class="bi bi-trash"></i></button>
                                                    </form>
                                                
                                                {{-- Tombol Aksi Khusus User Biasa (Pemilik) --}}
                                                @else
                                                    <a href="{{ route('inspections.index', $rfid->id) }}" class="btn btn-sm btn-primary shadow-sm rounded-pill px-3">
                                                        <i class="bi bi-journal-check me-1"></i> Daftar Uji
                                                    </a>
                                                @endif

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
    
    {{-- Kondisi jika data $vehicles kosong --}}
    @empty
        <div class="card border-0 shadow-sm text-center p-5">
            <h5 class="text-muted">Data kendaraan tidak ditemukan.</h5>
        </div>
    @endforelse
</div>

{{-- ================= PAGINATION ================= --}}
<div class="d-flex justify-content-end mb-4 mt-3">
    {{-- 
      Menampilkan navigasi halaman (halaman 1,2,3).
      Wajib menggunakan provider Bootstrap 5 agar tampilannya sesuai tema.
    --}}
    {{ $vehicles->links('pagination::bootstrap-5') }}
</div>

{{-- ================= MODAL TAMBAH KARTU (Hanya Untuk Admin) ================= --}}
@if(Auth::guard('admin')->check())
<div class="modal fade" id="modalTambahRfid" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        
        {{-- Form akan mengirim data ke RfidController@store --}}
        <form action="{{ route('rfids.store') }}" method="POST" class="modal-content border-0 shadow-lg">
            @csrf
            
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2"></i>Daftarkan Kartu RFID</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body bg-light">
                
                {{-- Input Kode RFID (Untuk disorot Scanner) --}}
                <div class="mb-4">
                    <label class="form-label fw-bold text-secondary">Scan / Input Kode RFID <span class="text-danger">*</span></label>
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-white"><i class="bi bi-upc-scan"></i></span>
                        <input type="text" name="kode_rfid" class="form-control" placeholder="Arahkan kursor ke sini, lalu scan kartu..." required autofocus>
                    </div>
                </div>

                {{-- Input Pilihan Kendaraan --}}
                <div class="mb-3">
                    <label class="form-label fw-bold text-secondary">Pilih Kendaraan Tujuan <span class="text-danger">*</span></label>
                    
                    {{-- Select ini akan ditimpa menjadi fitur Search canggih oleh JavaScript Select2 --}}
                    <select name="vehicle_id" id="selectKendaraan" class="form-select shadow-sm" required>
                        <option value="">-- Ketik/Pilih No Uji atau Plat Kendaraan --</option>
                        @foreach($vehicles as $v)
                            <option value="{{ $v->id }}">{{ $v->no_uji }} - {{ $v->no_kendaraan }}</option>
                        @endforeach
                    </select>
                    
                    <div class="form-text mt-2"><i class="bi bi-info-circle me-1"></i>Jika kendaraan sudah memiliki RFID aktif, sistem otomatis mematikan RFID lama.</div>
                </div>

            </div>
            
            <div class="modal-footer bg-white">
                <button type="button" class="btn btn-secondary shadow-sm px-4" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary shadow-sm px-4 fw-bold"><i class="bi bi-save me-1"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>
@endif

@endsection

{{-- ================= LIBRARY EKSTERNAL: SELECT2 & JQUERY ================= --}}
{{-- Select2 CSS --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
{{-- Select2 Theme (Agar bentuknya mirip Bootstrap 5) --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

{{-- Wajib load jQuery dulu, karena Select2 dibangun di atas jQuery --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
{{-- Script fungsionalitas Select2 --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

{{-- ================= JAVASCRIPT CUSTOM ================= --}}
<script>
    // $(document).ready() memastikan HTML sudah selesai dimuat sebelum menjalankan plugin
    $(document).ready(function() {
        
        // 1. Inisialisasi Select2 pada dropdown Form Modal Tambah
        $('#selectKendaraan').select2({
            theme: 'bootstrap-5', // Menggunakan style Bootstrap
            // dropdownParent WAJIB ada jika select2 diletakkan di dalam Bootstrap Modal. 
            // Jika tidak, kotak pencariannya tidak akan bisa diketik.
            dropdownParent: $('#modalTambahRfid'), 
            placeholder: '-- Ketik/Pilih No Uji atau Plat Kendaraan --',
            width: '100%' // Memaksa agar panjangnya penuh mengikuti kolom induknya
        });

        // 2. Inisialisasi Select2 pada filter pencarian di halaman utama
        $('#filterPemilik').select2({
            theme: 'bootstrap-5',
            placeholder: '-- Filter Semua Pemilik --',
            width: '100%'
        });

        // 3. UX Tambahan: Autofocus Scanner
        // Saat modal Bootstrap bernama '#modalTambahRfid' selesai terbuka ('shown.bs.modal')
        $('#modalTambahRfid').on('shown.bs.modal', function () {
            // Paksa kursor untuk langsung berkedip di dalam kotak input 'kode_rfid'.
            // Petugas cukup membuka modal, lalu langsung nembak scanner tanpa harus klik mouse lagi.
            $('input[name="kode_rfid"]').trigger('focus');
        });
    });
</script>