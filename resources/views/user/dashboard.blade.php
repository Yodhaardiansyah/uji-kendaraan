@extends('layouts.app')
@section('title', 'Dashboard Pemilik Kendaraan')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm bg-primary text-white overflow-hidden rounded-3">
            <div class="card-body p-4 position-relative">
                <div style="position: absolute; top: -20px; right: -20px; opacity: 0.1; transform: scale(2);">
                    <i class="bi bi-truck" style="font-size: 10rem;"></i>
                </div>
                <h4 class="fw-bold mb-1">Selamat datang, {{ $user->nama }}!</h4>
                <p class="mb-0 opacity-75"><i class="bi bi-card-heading me-1"></i>NIK: {{ $user->nomor_identitas }} | <i class="bi bi-geo-alt ms-2 me-1"></i>{{ $user->alamat }}</p>
            </div>
        </div>
    </div>
</div>

<h5 class="fw-bold text-dark mb-3"><i class="bi bi-collection me-2 text-primary"></i>Garasi Kendaraan Anda</h5>

{{-- ================= ACCORDION DAFTAR KENDARAAN ================= --}}
<div class="accordion shadow-sm" id="accordionKendaraan">
    @forelse($vehicles as $vehicle)
        @php 
            $collapseId = 'collapseVehicle' . $vehicle->id;
            $activeRfid = $vehicle->rfids->where('is_active', true)->first();
        @endphp

        <div class="accordion-item border-0 mb-3 rounded-3 overflow-hidden">
            <h2 class="accordion-header" id="heading{{ $collapseId }}">
                <button class="accordion-button collapsed bg-white fw-bold text-dark py-3" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}" aria-expanded="false">
                    <div class="d-flex align-items-center w-100 pe-3">
                        <div class="bg-light p-3 rounded-circle me-3">
                            <i class="bi bi-truck-front-fill fs-4 text-primary"></i>
                        </div>
                        <div>
                            <div class="fs-5 text-uppercase">{{ $vehicle->no_kendaraan }}</div>
                            <small class="text-muted fw-normal">{{ $vehicle->merk }} {{ $vehicle->tipe }}</small>
                        </div>
                        <div class="ms-auto text-end">
                            @if($activeRfid)
                                <span class="badge bg-success rounded-pill px-3 py-2 shadow-sm d-none d-md-inline-block">RFID Aktif</span>
                            @else
                                <span class="badge bg-danger rounded-pill px-3 py-2 shadow-sm d-none d-md-inline-block">Tidak Ada RFID Aktif</span>
                            @endif
                        </div>
                    </div>
                </button>
            </h2>
            
            <div id="{{ $collapseId }}" class="accordion-collapse collapse" data-bs-parent="#accordionKendaraan">
                <div class="accordion-body p-0 border-top border-light">
                    
                    {{-- INFO SINGKAT KENDARAAN --}}
                    <div class="bg-light p-3 border-bottom d-flex flex-wrap gap-4 small">
                        <div><span class="text-muted">No. Uji:</span> <span class="fw-bold">{{ $vehicle->no_uji }}</span></div>
                        <div><span class="text-muted">Jenis:</span> <span class="fw-bold">{{ $vehicle->jenis }}</span></div>
                        <div><span class="text-muted">Wilayah:</span> <span class="fw-bold">{{ $vehicle->wilayah }}</span></div>
                    </div>

                    {{-- TABEL DAFTAR RFID PER KENDARAAN --}}
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
                                @forelse($vehicle->rfids as $rfid)
                                    <tr class="{{ $rfid->is_active ? 'table-success' : '' }}">
                                        <td class="ps-4 fw-bold {{ $rfid->is_active ? 'text-primary' : 'text-secondary' }}">
                                            <i class="bi bi-upc-scan me-1"></i> {{ $rfid->kode_rfid }}
                                            @if($rfid->is_active) <span class="badge bg-success ms-2 small d-md-none">Aktif</span> @endif
                                        </td>
                                        <td>
                                            @if($rfid->is_active)
                                                <span class="badge bg-success shadow-sm">Sedang Digunakan</span>
                                            @else
                                                <span class="badge bg-secondary shadow-sm">Non-Aktif / Lama</span>
                                            @endif
                                            <div class="small text-muted mt-1">Didaftarkan: {{ $rfid->created_at->format('d M Y') }}</div>
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ $rfid->inspections_count }}</span> Kali Pengujian
                                        </td>
                                        <td class="text-center pe-4">
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