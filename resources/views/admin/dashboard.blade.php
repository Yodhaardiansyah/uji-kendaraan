@extends('layouts.app')

{{-- 
    Judul halaman (akan dipanggil di layout utama)
--}}
@section('title', 'Dashboard Petugas - Dishub System')

@section('content')

{{-- ================= HEADER DASHBOARD (WELCOME CARD) ================= --}}
<div class="row mb-4">
    <div class="col-12">
        {{-- 
            Card utama:
            - Menampilkan sapaan ke admin
            - Menampilkan info wilayah & NRP
            - Shortcut aksi cepat (scan & tambah kendaraan)
        --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden" 
             style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%); color: white;">
            
            <div class="card-body p-4 p-md-5 position-relative">

                {{-- Icon background (dekorasi visual) --}}
                <i class="bi bi-person-badge position-absolute" 
                   style="font-size: 12rem; right: -20px; top: -30px; opacity: 0.1;"></i>

                {{-- Nama admin --}}
                <h3 class="fw-bold mb-1">
                    Selamat Bertugas, {{ $admin->nama }}!
                </h3>

                {{-- Informasi tambahan admin --}}
                <p class="mb-0 opacity-75">
                    <i class="bi bi-geo-alt-fill me-1"></i> 
                    {{ $admin->dishub->nama ?? 'Wilayah Tidak Diketahui' }} 
                    | NRP: {{ $admin->nrp }}
                </p>
                
                {{-- Tombol aksi cepat --}}
                <div class="mt-4">
                    
                    {{-- Tombol buka modal scan RFID --}}
                    <button type="button" 
                            class="btn btn-light fw-bold shadow-sm rounded-pill px-4 me-2" 
                            data-bs-toggle="modal" 
                            data-bs-target="#scanRfidModal">
                        <i class="bi bi-upc-scan me-1"></i> Scan RFID Kendaraan
                    </button>

                    {{-- Tombol tambah kendaraan --}}
                    <a href="{{ route('vehicles.create') }}" 
                       class="btn btn-outline-light fw-bold rounded-pill px-4">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Kendaraan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ================= METRIK HARIAN ================= --}}
{{-- Menampilkan performa admin hari ini --}}
<h6 class="fw-bold text-secondary text-uppercase mb-3">
    Performa Anda Hari Ini
</h6>

<div class="row g-3 mb-4">

    {{-- Total uji hari ini --}}
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100 border-bottom border-primary">
            <div class="card-body">
                <div class="text-muted small fw-bold mb-2">Uji Hari Ini</div>
                <h2 class="fw-bold text-primary">{{ $ujiHariIni }}</h2>
            </div>
        </div>
    </div>

    {{-- Jumlah lulus uji --}}
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100 border-bottom border-success">
            <div class="card-body"> 
                <div class="text-muted small fw-bold mb-2">Lolos Uji</div>
                <h2 class="fw-bold text-success">{{ $lulusUji }}</h2>
            </div>
        </div>
    </div>

    {{-- Total kendaraan global --}}
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100 border-bottom border-warning">
            <div class="card-body">
                <div class="text-muted small fw-bold mb-2">Total Kendaraan</div>
                <h2 class="fw-bold text-warning">
                    {{ number_format($totalKendaraan) }}
                </h2>
            </div>
        </div>
    </div>

    {{-- Total RFID aktif --}}
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100 border-bottom border-info">
            <div class="card-body">
                <div class="text-muted small fw-bold mb-2">RFID Aktif</div>
                <h2 class="fw-bold text-info">
                    {{ number_format($rfidAktif) }}
                </h2>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-5">

    {{-- ================= KOLOM KIRI ================= --}}
    {{-- Tabel 5 riwayat inspeksi terakhir --}}
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm h-100">

            <div class="card-header bg-white py-3 border-0">
                <h6 class="fw-bold">
                    5 Pengujian Terakhir Anda
                </h6>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">

                    {{-- Header tabel --}}
                    <thead class="table-light">
                        <tr>
                            <th>No. Plat</th>
                            <th>Waktu</th>
                            <th>Hasil</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    {{-- Data --}}
                    <tbody>
                        @forelse($recentInspections as $uji)
                        <tr>

                            {{-- Nomor kendaraan --}}
                            <td class="fw-bold">
                                {{ $uji->rfid->vehicle->no_kendaraan ?? '-' }}
                            </td>

                            {{-- Waktu relatif --}}
                            <td>
                                {{ $uji->created_at->diffForHumans() }}
                            </td>

                            {{-- Status hasil --}}
                            <td>
                                @if($uji->hasil == 'Lolos Uji Berkala')
                                    <span class="text-success">Lolos</span>
                                @else
                                    <span class="text-danger">Gagal</span>
                                @endif
                            </td>

                            {{-- Tombol cetak --}}
                            <td class="text-center">
                                <a href="{{ route('inspections.show', $uji->id) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    Cetak
                                </a>
                            </td>
                        </tr>

                        {{-- Jika kosong --}}
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">
                                Belum ada pengujian hari ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </div>

    {{-- ================= KOLOM KANAN ================= --}}
    {{-- Persebaran kendaraan per wilayah --}}
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm h-100">

            <div class="card-header bg-white py-3 border-0">
                <h6 class="fw-bold">Persebaran Kendaraan</h6>
            </div>

            {{-- Accordion wilayah --}}
            <div class="card-body">

                @forelse($vehiclesByRegion as $wilayah => $vehicles)

                    {{-- Judul wilayah --}}
                    <h6>{{ $wilayah }} ({{ $vehicles->count() }})</h6>

                    {{-- List kendaraan --}}
                    @foreach($vehicles as $v)
                        <span class="badge bg-dark">
                            {{ $v->no_kendaraan }}
                        </span>
                    @endforeach

                @empty
                    <p class="text-muted">Tidak ada data kendaraan</p>
                @endforelse

            </div>
        </div>
    </div>
</div>

{{-- ================= MODAL SCAN RFID ================= --}}
<div class="modal fade" id="scanRfidModal">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            {{-- Header modal --}}
            <div class="modal-header bg-primary text-white">
                <h5>Scan Kartu RFID</h5>
            </div>

            <div class="modal-body text-center">

                {{-- Form scan RFID --}}
                <form action="{{ route('admin.rfids.search_redirect') }}" method="POST">
                    @csrf

                    {{-- Input scanner --}}
                    <input type="text" name="kode_rfid" 
                           id="rfid_input"
                           class="form-control text-center"
                           placeholder="Scan RFID..." required>

                    {{-- Submit --}}
                    <button class="btn btn-primary mt-3">
                        Periksa Kendaraan
                    </button>
                </form>

            </div>
        </div>
    </div>
</div>

{{-- ================= SCRIPT ================= --}}
<script>
    /*
        Auto focus ke input saat modal dibuka
        Berguna untuk scanner RFID (langsung input tanpa klik)
    */
    const scanModal = document.getElementById('scanRfidModal');

    scanModal.addEventListener('shown.bs.modal', () => {
        document.getElementById('rfid_input').focus();
    });
</script>

@endsection