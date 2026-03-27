{{-- Mewarisi kerangka utama website --}}
@extends('layouts.app')

{{-- Mengatur judul tab browser secara dinamis dengan menyematkan nomor kendaraan --}}
@section('title', 'Riwayat Uji - ' . $vehicle->no_kendaraan)

{{-- Membuka bagian konten utama --}}
@section('content')

{{-- ================= HEADER & TOMBOL AKSI ================= --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0 text-primary"><i class="bi bi-journal-check me-2"></i>Riwayat Uji Berkala</h4>
        {{-- Menampilkan informasi dasar kendaraan: Merk, Tipe, dan Plat Nomor --}}
        <p class="text-muted mb-0">{{ $vehicle->merk }} {{ $vehicle->tipe }} | <b>{{ $vehicle->no_kendaraan }}</b></p>
    </div>
    
    <div class="d-flex gap-2">
        {{-- Tombol Kembali: Bisa diakses oleh siapapun (Admin maupun User) --}}
        <a href="{{ route('vehicles.index') }}" class="btn btn-light border shadow-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        
        {{-- 
          PENGECEKAN ROLE (HAK AKSES)
          Auth::guard('admin')->check() memastikan kode di bawahnya HANYA 
          dirender dan ditampilkan jika user yang sedang login adalah seorang Admin.
        --}}
        @if(Auth::guard('admin')->check())
            
            {{-- 
              PENGECEKAN STATUS KARTU RFID
              Admin hanya bisa menambah data uji JIKA kartu RFID saat ini berstatus aktif (True/1).
            --}}
            @if($rfid->is_active)
                <a href="{{ route('inspections.create', $rfid->id) }}" class="btn btn-primary shadow-sm">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Uji Baru
                </a>
            @else
                {{-- Jika kartu non-aktif, tampilkan tombol 'disabled' (tidak bisa diklik) sebagai indikator visual --}}
                <button class="btn btn-secondary shadow-sm" disabled title="Kartu ini sudah Non-Aktif">
                    <i class="bi bi-lock-fill"></i> Tambah Uji Baru
                </button>
            @endif

        @endif
        {{-- AKHIR PENGECEKAN ROLE --}}
        
    </div>
</div>

{{-- ================= KARTU INFORMASI SINGKAT (WIDGETS) ================= --}}
<div class="row g-3 mb-4">
    
    {{-- Info 1: Nomor Uji Kendaraan --}}
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-3 border-start border-primary">
            <small class="text-muted d-block">No. Uji Kendaraan</small>
            <span class="fw-bold fs-5 text-dark">{{ $vehicle->no_uji }}</span>
        </div>
    </div>
    
    {{-- Info 2: Status dan Kode RFID --}}
    <div class="col-md-4">
        {{-- 
          Menggunakan Ternary Operator (Kondisi ? Benar : Salah) untuk mengubah 
          warna border dan teks secara otomatis berdasarkan status kartu (Aktif = Hijau, Non-aktif = Abu-abu).
        --}}
        <div class="card border-0 shadow-sm p-3 border-start {{ $rfid->is_active ? 'border-success' : 'border-secondary' }}">
            <small class="text-muted d-block">Log Kartu RFID {{ !$rfid->is_active ? '(Non-Aktif)' : '' }}</small>
            <span class="fw-bold fs-5 {{ $rfid->is_active ? 'text-success' : 'text-secondary' }}">
                <i class="bi bi-credit-card-2-front me-1"></i> {{ $rfid->kode_rfid }}
            </span>
        </div>
    </div>
    
    {{-- Info 3: Total Uji pada Kartu --}}
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-3 border-start border-info">
            <small class="text-muted d-block">Total Uji Pada Kartu Ini</small>
            {{-- Menghitung jumlah data inspeksi yang ada di dalam variabel $inspections --}}
            <span class="fw-bold fs-5 text-info">{{ $inspections->count() }} Kali</span>
        </div>
    </div>
</div>

{{-- ================= TABEL RIWAYAT UJI ================= --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h6 class="mb-0 fw-bold text-secondary"><i class="bi bi-clock-history me-2"></i>Daftar Pemeriksaan Kartu Ini</h6>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-primary">
                    <tr>
                        <th class="ps-4 py-3" style="width: 100px;">Uji Ke-</th>
                        <th>Tanggal Uji</th>
                        <th>Masa Berlaku</th>
                        <th>Hasil Akhir</th>
                        <th>Petugas Penguji</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    
                    {{-- 
                      @forelse sangat berguna: Jika $inspections ada isinya, lakukan perulangan.
                      Jika kosong, langsung jalankan blok @empty di bawah.
                      $index digunakan untuk membuat nomor urut otomatis.
                    --}}
                    @forelse($inspections as $index => $uji)
                        <tr>
                            {{-- Menampilkan nomor urut (karena array dimulai dari 0, maka ditambah 1) --}}
                            <td class="ps-4 fw-bold text-primary">{{ $index + 1 }}</td>
                            
                            {{-- Format tanggal dari database (YYYY-MM-DD) menjadi format yang lebih mudah dibaca (DD MMM YYYY) --}}
                            <td>{{ $uji->tgl_uji->format('d M Y') }}</td>
                            
                            {{-- Mengecek ketersediaan data tanggal berlaku --}}
                            <td>
                                @if($uji->tgl_berlaku)
                                    <span class="fw-semibold">{{ $uji->tgl_berlaku->format('d M Y') }}</span>
                                @else
                                    <span class="text-muted small italic">Belum ditentukan</span>
                                @endif
                            </td>
                            
                            {{-- 
                              KONDISIONAL BADGE HASIL UJI
                              Membedakan warna lencana (badge) berdasarkan hasil akhir pemeriksaan.
                            --}}
                            <td>
                                @if($uji->hasil == 'Lolos Uji Berkala')
                                    <span class="badge bg-success shadow-sm"><i class="bi bi-check-circle me-1"></i> LOLOS</span>
                                @elseif($uji->hasil == 'Tidak Lolos Uji Berkala')
                                    <span class="badge bg-danger shadow-sm"><i class="bi bi-x-circle me-1"></i> TIDAK LOLOS</span>
                                @else
                                    {{-- Fallback untuk status lain (misalnya 'Menunggu Hasil') --}}
                                    <span class="badge bg-warning text-dark shadow-sm"><i class="bi bi-clock me-1"></i> MENUNGGU</span>
                                @endif
                            </td>
                            
                            {{-- Menampilkan nama dan NRP petugas yang menginput data uji --}}
                            <td>
                                <div class="fw-bold small">{{ $uji->nama_petugas }}</div>
                                <div class="text-muted" style="font-size: 0.75rem;">NRP: {{ $uji->nrp }}</div>
                            </td>
                            
                            {{-- KOLOM AKSI (Tombol Detail & Hapus) --}}
                            <td class="text-center">
                                <div class="btn-group shadow-sm">
                                    
                                    {{-- Tombol Detail/Cetak (Bisa dilihat oleh Admin dan User) --}}
                                    <a href="{{ route('inspections.show', $uji->id) }}" class="btn btn-sm btn-outline-primary" title="Lihat Detail & Cetak">
                                        <i class="bi bi-printer me-1"></i> Detail
                                    </a>
                                    
                                    {{-- 
                                      PENGECEKAN ROLE KEDUA
                                      Tombol hapus hanya dirender jika yang login adalah Admin.
                                      Pemilik kendaraan tidak boleh menghapus riwayat ujinya sendiri.
                                    --}}
                                    @if(Auth::guard('admin')->check())
                                        <form action="{{ route('inspections.destroy', $uji->id) }}" method="POST" onsubmit="return confirm('Hapus riwayat uji ini?')">
                                            @csrf 
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger border-start-0">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                    
                                </div>
                            </td>
                        </tr>
                        
                    {{-- Blok ini dieksekusi jika belum ada riwayat uji pada kartu RFID ini --}}
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-clipboard-x fs-1 d-block mb-3"></i>
                                Belum ada riwayat pengujian yang tercatat pada kartu RFID ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Catatan kaki sebagai informasi tambahan untuk user/admin --}}
<div class="mt-3">
    <small class="text-muted">
        <i class="bi bi-info-circle me-1"></i> 
        Menampilkan riwayat pengujian yang terikat pada kartu <b>{{ $rfid->kode_rfid }}</b>. 
        Riwayat pada kartu lain dapat dilihat melalui tab <i>Riwayat RFID</i> di halaman utama.
    </small>
</div>
@endsection