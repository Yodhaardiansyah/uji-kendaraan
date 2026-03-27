{{-- Mewarisi kerangka layout aplikasi utama --}}
@extends('layouts.app')

{{-- Menentukan judul halaman untuk tab browser --}}
@section('title', 'Dashboard Pemilik Kendaraan')

{{-- Memulai bagian konten utama --}}
@section('content')

{{-- ================= HEADER: KARTU SELAMAT DATANG (HERO CARD) ================= --}}
<div class="row mb-4">
    <div class="col-12">
        {{-- 
          Membuat kartu berwarna biru (bg-primary) yang menonjol sebagai sambutan awal.
          overflow-hidden penting agar ikon transparan besar di dalamnya tidak keluar batas kotak.
        --}}
        <div class="card border-0 shadow-sm bg-primary text-white overflow-hidden rounded-3">
            <div class="card-body p-4 position-relative">
                
                {{-- 
                  IKON WATERMARK (Latar Belakang)
                  Menggunakan position-absolute agar ikon bisa ditempatkan bebas tanpa mengganggu teks.
                  opacity: 0.1 membuatnya sangat transparan sehingga terlihat seperti watermark.
                --}}
                <div style="position: absolute; top: -20px; right: -20px; opacity: 0.1; transform: scale(2);">
                    <i class="bi bi-truck" style="font-size: 10rem;"></i>
                </div>
                
                {{-- Menyapa pengguna dengan nama yang diambil dari data login (Auth::user) --}}
                <h4 class="fw-bold mb-1">Selamat datang, {{ $user->nama }}!</h4>
                
                {{-- Menampilkan NIK dan Alamat pengguna --}}
                <p class="mb-0 opacity-75"><i class="bi bi-card-heading me-1"></i>NIK: {{ $user->nomor_identitas }} | <i class="bi bi-geo-alt ms-2 me-1"></i>{{ $user->alamat }}</p>
            </div>
        </div>
    </div>
</div>

{{-- Judul Seksi Daftar Kendaraan --}}
<h5 class="fw-bold text-dark mb-3"><i class="bi bi-collection me-2 text-primary"></i>Garasi Kendaraan Anda</h5>

{{-- ================= ACCORDION DAFTAR KENDARAAN ================= --}}
{{-- Menggunakan komponen Accordion Bootstrap agar tampilan rapi jika pengguna memiliki banyak kendaraan --}}
<div class="accordion shadow-sm" id="accordionKendaraan">
    
    {{-- Looping melalui setiap kendaraan yang dimiliki oleh pengguna ini ($vehicles) --}}
    @forelse($vehicles as $vehicle)
        @php 
            // Membuat ID unik untuk setiap item accordion berdasarkan ID kendaraan
            $collapseId = 'collapseVehicle' . $vehicle->id;
            
            // Mencari DARI array RFID, apakah ada yang berstatus aktif (is_active = true)
            // first() akan mengembalikan objek jika ada, dan mengembalikan 'null' jika tidak ada.
            $activeRfid = $vehicle->rfids->where('is_active', true)->first();
        @endphp

        <div class="accordion-item border-0 mb-3 rounded-3 overflow-hidden">
            
            {{-- HEADER ACCORDION (Baris yang bisa di-klik untuk membuka detail) --}}
            <h2 class="accordion-header" id="heading{{ $collapseId }}">
                <button class="accordion-button collapsed bg-white fw-bold text-dark py-3" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}" aria-expanded="false">
                    
                    {{-- d-flex memastikan konten berjejer secara horizontal --}}
                    <div class="d-flex align-items-center w-100 pe-3">
                        <div class="bg-light p-3 rounded-circle me-3">
                            <i class="bi bi-truck-front-fill fs-4 text-primary"></i>
                        </div>
                        
                        <div>
                            {{-- Plat nomor (Huruf Kapital) --}}
                            <div class="fs-5 text-uppercase">{{ $vehicle->no_kendaraan }}</div>
                            {{-- Merk dan tipe kendaraan --}}
                            <small class="text-muted fw-normal">{{ $vehicle->merk }} {{ $vehicle->tipe }}</small>
                        </div>
                        
                        {{-- STATUS KARTU RFID (Indikator Cepat di sebelah kanan) --}}
                        <div class="ms-auto text-end">
                            @if($activeRfid)
                                {{-- Jika ada kartu RFID aktif, tampilkan badge hijau --}}
                                <span class="badge bg-success rounded-pill px-3 py-2 shadow-sm d-none d-md-inline-block">RFID Aktif</span>
                            @else
                                {{-- Jika tidak ada kartu aktif (atau belum dipasang sama sekali), tampilkan badge merah --}}
                                <span class="badge bg-danger rounded-pill px-3 py-2 shadow-sm d-none d-md-inline-block">Tidak Ada RFID Aktif</span>
                            @endif
                        </div>
                    </div>
                    
                </button>
            </h2>
            
            {{-- BODY ACCORDION (Isi detail yang terbuka saat header diklik) --}}
            <div id="{{ $collapseId }}" class="accordion-collapse collapse" data-bs-parent="#accordionKendaraan">
                <div class="accordion-body p-0 border-top border-light">
                    
                    {{-- INFO SINGKAT KENDARAAN --}}
                    <div class="bg-light p-3 border-bottom d-flex flex-wrap gap-4 small">
                        <div><span class="text-muted">No. Uji:</span> <span class="fw-bold">{{ $vehicle->no_uji }}</span></div>
                        <div><span class="text-muted">Jenis:</span> <span class="fw-bold">{{ $vehicle->jenis }}</span></div>
                        <div><span class="text-muted">Wilayah:</span> <span class="fw-bold">{{ $vehicle->wilayah }}</span></div>
                    </div>

                    {{-- TABEL DAFTAR KARTU RFID --}}
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4 py-3">Kode Kartu (RFID)</th>
                                    <th>Status Kartu</th>
                                    <th>Total Riwayat Uji</th>
                                    <th class="text-center pe-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- 
                                  Looping melalui setiap kartu RFID yang pernah ditugaskan ke kendaraan ini.
                                  (Biasanya ada beberapa jika kartu lama hilang/rusak dan diganti baru).
                                --}}
                                @forelse($vehicle->rfids as $rfid)
                                    {{-- Highlight baris tabel dengan warna hijau jika kartunya sedang aktif --}}
                                    <tr class="{{ $rfid->is_active ? 'table-success' : '' }}">
                                        <td class="ps-4 fw-bold {{ $rfid->is_active ? 'text-primary' : 'text-secondary' }}">
                                            <i class="bi bi-upc-scan me-1"></i> {{ $rfid->kode_rfid }}
                                            {{-- Label Aktif versi Mobile (d-md-none: hanya muncul di layar kecil) --}}
                                            @if($rfid->is_active) <span class="badge bg-success ms-2 small d-md-none">Aktif</span> @endif
                                        </td>
                                        
                                        {{-- Kolom Status Kartu --}}
                                        <td>
                                            @if($rfid->is_active)
                                                <span class="badge bg-success shadow-sm">Sedang Digunakan</span>
                                            @else
                                                <span class="badge bg-secondary shadow-sm">Non-Aktif / Lama</span>
                                            @endif
                                            {{-- Menampilkan tanggal pendaftaran kartu --}}
                                            <div class="small text-muted mt-1">Didaftarkan: {{ $rfid->created_at->format('d M Y') }}</div>
                                        </td>
                                        
                                        {{-- Kolom Total Riwayat Uji (Menggunakan fitur withCount() dari Controller) --}}
                                        <td>
                                            <span class="fw-bold">{{ $rfid->inspections_count }}</span> Kali Pengujian
                                        </td>
                                        
                                        {{-- KOLOM AKSI --}}
                                        <td class="text-center pe-4">
                                            {{-- 
                                              Hanya tampilkan tombol "Buka Hasil Uji" JIKA kartu RFID ini benar-benar memiliki data uji.
                                              Jika kartu masih baru dan belum pernah diuji, tombolnya dimatikan (disabled).
                                            --}}
                                            @if($rfid->inspections_count > 0)
                                                <a href="{{ route('inspections.index', $rfid->id) }}" class="btn btn-sm btn-primary shadow-sm rounded-pill px-3 fw-bold">
                                                    <i class="bi bi-journal-check me-1"></i> Buka Hasil Uji
                                                </a>
                                            @else
                                                <button class="btn btn-sm btn-outline-secondary shadow-sm rounded-pill px-3" disabled>
                                                    Belum Ada Uji
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                    
                                {{-- Jika Kendaraan belum dipasangi RFID sama sekali --}}
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">
                                            <i class="bi bi-credit-card-x fs-2 d-block mb-2"></i>
                                            Kendaraan ini belum dipasangi kartu RFID oleh petugas.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
        
    {{-- Kondisi jika user/pemilik ini belum memiliki data kendaraan sama sekali di sistem --}}
    @empty
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body text-center py-5 text-muted">
                <i class="bi bi-truck-flatbed fs-1 d-block mb-3"></i>
                <h5 class="fw-bold text-dark">Belum Ada Kendaraan</h5>
                <p class="mb-0">Anda belum mendaftarkan kendaraan apa pun di sistem ini.</p>
            </div>
        </div>
    @endforelse
</div>
@endsection