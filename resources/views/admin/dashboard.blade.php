@extends('layouts.app')
@section('title', 'Dashboard Petugas - Dishub System')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%); color: white;">
            <div class="card-body p-4 p-md-5 position-relative">
                <i class="bi bi-person-badge position-absolute" style="font-size: 12rem; right: -20px; top: -30px; opacity: 0.1;"></i>
                <h3 class="fw-bold mb-1">Selamat Bertugas, {{ $admin->nama }}!</h3>
                <p class="mb-0 opacity-75"><i class="bi bi-geo-alt-fill me-1"></i> {{ $admin->dishub->nama ?? 'Wilayah Tidak Diketahui' }} | NRP: {{ $admin->nrp }}</p>
                
                <div class="mt-4">
                    <button type="button" class="btn btn-light fw-bold shadow-sm rounded-pill px-4 me-2" data-bs-toggle="modal" data-bs-target="#scanRfidModal">
                        <i class="bi bi-upc-scan me-1"></i> Scan RFID Kendaraan
                    </button>
                    <a href="{{ route('vehicles.create') }}" class="btn btn-outline-light fw-bold rounded-pill px-4">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Kendaraan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- METRIK HARIAN --}}
<h6 class="fw-bold text-secondary text-uppercase mb-3">Performa Anda Hari Ini</h6>
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-white rounded-3 h-100 border-bottom border-primary">
            <div class="card-body">
                <div class="text-muted small fw-bold mb-2 text-uppercase">Uji Hari Ini</div>
                <div class="d-flex align-items-center">
                    <h2 class="fw-bold mb-0 me-2 text-primary">{{ $ujiHariIni }}</h2>
                    <span class="badge bg-primary-subtle text-primary rounded-pill">Unit</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-white rounded-3 h-100 border-bottom border-success ">
            <div class="card-body"> 
                <div class="text-muted small fw-bold mb-2 text-uppercase">Lolos Uji</div>
                <div class="d-flex align-items-center">
                    <h2 class="fw-bold mb-0 me-2 text-success">{{ $lulusUji }}</h2>
                    <span class="badge bg-success-subtle text-success rounded-pill">Sertifikat</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-white rounded-3 h-100 border-bottom border-warning">
            <div class="card-body">
                <div class="text-muted small fw-bold mb-2 text-uppercase">Total Kendaraan</div>
                <h2 class="fw-bold mb-0 text-warning">{{ number_format($totalKendaraan) }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-white rounded-3 h-100 border-bottom border-info ">
            <div class="card-body">
                <div class="text-muted small fw-bold mb-2 text-uppercase">RFID Aktif</div>
                <h2 class="fw-bold mb-0 text-info">{{ number_format($rfidAktif) }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-5">
    {{-- KOLOM KIRI: TABEL RIWAYAT TERAKHIR --}}
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-0">
                <h6 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2 text-primary"></i>5 Pengujian Terakhir Anda</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">No. Plat</th>
                            <th>Waktu</th>
                            <th>Hasil</th>
                            <th class="text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentInspections as $uji)
                        <tr>
                            <td class="ps-4 fw-bold">
                                <span class="badge bg-dark border shadow-sm px-2 py-1" style="letter-spacing: 1px;">{{ $uji->rfid->vehicle->no_kendaraan ?? '-' }}</span>
                            </td>
                            <td><small class="text-muted">{{ $uji->created_at->diffForHumans() }}</small></td>
                            <td>
                                @if($uji->hasil == 'Lolos Uji Berkala')
                                    <span class="badge bg-success-subtle text-success border border-success px-2 py-1"><i class="bi bi-check-circle me-1"></i>Lolos</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger px-2 py-1"><i class="bi bi-x-circle me-1"></i>Gagal</span>
                                @endif
                            </td>
                            <td class="text-center pe-4 text-nowrap">
                                <a href="{{ route('inspections.show', $uji->id) }}" class="btn btn-sm btn-outline-primary shadow-sm rounded-pill px-3">
                                    <i class="bi bi-printer me-1"></i> Cetak
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-5 text-muted">Belum ada pengujian yang Anda lakukan hari ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- KOLOM KANAN: KENDARAAN PER WILAYAH (ACCORDION) --}}
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="bi bi-geo-alt-fill me-2 text-danger"></i>Persebaran Kendaraan</h6>
            </div>
            
            <div class="card-body p-3 overflow-auto" style="max-height: 450px;">
                <div class="accordion accordion-flush shadow-sm border rounded-3" id="accordionWilayahAdmin">
                    @forelse($vehiclesByRegion as $wilayah => $vehicles)
                        @php 
                            $collapseId = 'collapseWilayahAdmin' . Str::slug($wilayah); 
                        @endphp
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading{{ $collapseId }}">
                                <button class="accordion-button collapsed fw-bold py-3 bg-white text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}" aria-expanded="false">
                                    <i class="bi bi-building text-primary me-2"></i> {{ $wilayah }}
                                    <span class="badge bg-secondary rounded-pill ms-auto me-2 shadow-sm">
                                        {{ $vehicles->count() }} Unit
                                    </span>
                                </button>
                            </h2>
                            <div id="{{ $collapseId }}" class="accordion-collapse collapse" data-bs-parent="#accordionWilayahAdmin">
                                <div class="accordion-body p-3 bg-light">
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($vehicles as $v)
                                            <a href="{{ route('vehicles.index', ['search' => $v->no_kendaraan]) }}" class="text-decoration-none transition-hover" title="Pemilik: {{ $v->user->nama ?? '-' }}">
                                                <span class="badge bg-dark border border-secondary shadow-sm p-2 fw-normal" style="font-size: 0.8rem; letter-spacing: 1px; min-width: 80px;">
                                                    {{ $v->no_kendaraan }}
                                                </span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                            Belum ada data kendaraan terdaftar.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL SCAN RFID --}}
<div class="modal fade" id="scanRfidModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title fw-bold"><i class="bi bi-upc-scan me-2"></i>Scan Kartu RFID</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <form action="{{ route('admin.rfids.search_redirect') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label fw-bold text-secondary mb-3">Tempelkan Kartu pada Scanner</label>
                        <div class="input-group input-group-lg shadow-sm">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-upc-scan text-primary"></i></span>
                            <input type="text" name="kode_rfid" class="form-control border-start-0 fw-bold text-center" 
                                   id="rfid_input" placeholder="Scan Kode RFID..." autocomplete="off" required>
                        </div>
                        <div class="form-text mt-3"><i class="bi bi-info-circle me-1"></i>Sistem akan mencari riwayat inspeksi kendaraan terkait.</div>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow fw-bold">
                            <i class="bi bi-search me-2"></i> Periksa Kendaraan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .transition-hover:hover .badge {
        background-color: #343a40 !important;
        transform: translateY(-2px);
        transition: all 0.2s ease;
    }
</style>

<script>
    const scanModal = document.getElementById('scanRfidModal');
    scanModal.addEventListener('shown.bs.modal', () => {
        document.getElementById('rfid_input').focus();
    });
</script>
@endsection