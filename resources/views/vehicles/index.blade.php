@extends('layouts.app')
@section('title', 'Data Kendaraan - Dishub System')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0 text-primary"><i class="bi bi-truck me-2"></i>Data Kendaraan Bermotor</h4>
    
    @if(Auth::guard('admin')->check())
        <a href="{{ route('vehicles.create') }}" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> Tambah Kendaraan
        </a>
    @endif
</div>

{{-- ================= FORM PENCARIAN ================= --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form action="{{ route('vehicles.index') }}" method="GET" class="d-flex gap-2">
            <div class="input-group shadow-sm">
                <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari No Uji, Plat, Merk, atau Nama Pemilik..." value="{{ request('search') }}">
            </div>
            <button type="submit" class="btn btn-secondary px-4 shadow-sm fw-bold">Cari</button>
            
            @if(request('search'))
                <a href="{{ route('vehicles.index') }}" class="btn btn-outline-danger shadow-sm"><i class="bi bi-x-circle"></i> Reset</a>
            @endif
        </form>
    </div>
</div>

{{-- ================= DAFTAR KENDARAAN (BERDASARKAN PEMILIK) ================= --}}
<div class="accordion shadow-sm" id="accordionVehicles">
    {{-- Kita langsung me-looping variabel $users --}}
    @forelse($users as $owner)
        @php 
            $ownerVehicles = $owner->vehicles;
            $collapseId = 'collapseOwner' . $owner->id;
        @endphp

        <div class="accordion-item border-0 mb-3 rounded-3 overflow-hidden">
            <h2 class="accordion-header" id="heading{{ $collapseId }}">
                <button class="accordion-button collapsed bg-white fw-bold text-dark py-3" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}" aria-expanded="false">
                    <div class="d-flex align-items-center w-100 pe-3">
                        <div class="bg-light p-2 rounded-circle me-3">
                            <i class="bi bi-person-badge fs-5 text-primary"></i>
                        </div>
                        <div>
                            <div class="fs-6">{{ $owner->nama ?? 'Pemilik Tidak Diketahui' }}</div>
                            <small class="text-muted fw-normal"><i class="bi bi-card-heading me-1"></i>NIK: {{ $owner->nomor_identitas ?? '-' }}</small>
                        </div>
                        <div class="ms-auto">
                            <span class="badge bg-primary rounded-pill px-3 py-2 shadow-sm">
                                {{ $ownerVehicles->count() }} Kendaraan
                            </span>
                        </div>
                    </div>
                </button>
            </h2>
            
            <div id="{{ $collapseId }}" class="accordion-collapse collapse" data-bs-parent="#accordionVehicles">
                <div class="accordion-body p-0 border-top border-light">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4 py-3" style="width: 15%;">No. Uji</th>
                                    <th style="width: 20%;">No. Plat</th>
                                    <th style="width: 25%;">Merk / Tipe</th>
                                    <th style="width: 20%;">Jenis</th>
                                    <th class="text-center" style="width: 20%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ownerVehicles as $item)
                                    <tr>
                                        <td class="ps-4 fw-bold text-secondary">{{ $item->no_uji }}</td>
                                        <td><span class="badge bg-dark fs-6 shadow-sm">{{ $item->no_kendaraan }}</span></td>
                                        <td>
                                            <div class="fw-bold">{{ $item->merk }}</div>
                                            <small class="text-muted">{{ $item->tipe }}</small>
                                        </td>
                                        <td>{{ $item->jenis }}</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-info text-white shadow-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalDetail{{ $item->id }}">
                                                <i class="bi bi-eye me-1"></i> View Detail
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5 text-muted">
                <i class="bi bi-search fs-1 d-block mb-3"></i>
                @if(request('search'))
                    Data pemilik/kendaraan dengan kata kunci "<b>{{ request('search') }}</b>" tidak ditemukan.
                @else
                    Belum ada data kendaraan yang terdaftar di sistem.
                @endif
            </div>
        </div>
    @endforelse
</div>

{{-- ================= PAGINATION ================= --}}
<div class="d-flex justify-content-end mb-4 mt-3">
    {{-- Ubah links() menjadi milih $users, bukan $vehicles --}}
    {{ $users->links('pagination::bootstrap-5') }}
</div>


{{-- ================= AREA MODAL VIEW LENGKAP ================= --}}
{{-- Modal dibiarkan rata (flat) seperti aslinya karena dipanggil berdasarkan ID kendaraan ($item->id) --}}
@foreach($vehicles as $item)
    <div class="modal fade" id="modalDetail{{ $item->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg">

                {{-- HEADER MODAL --}}
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-info-circle me-2"></i> Detail Kendaraan: {{ $item->no_kendaraan }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body bg-light p-0">
                    @php
                        $activeRfid = $item->rfids->where('is_active', true)->first();
                    @endphp

                    {{-- 1. TOP BAR: TOMBOL AKSI KENDARAAN --}}
                    @if(Auth::guard('admin')->check())
                        <div class="p-3 bg-white border-bottom d-flex justify-content-end gap-2">
                            <a href="{{ route('vehicles.edit', $item->id) }}" class="btn btn-warning btn-sm text-dark fw-bold shadow-sm">
                                <i class="bi bi-pencil-square"></i> Edit Data Kendaraan
                            </a>
                            <form action="{{ route('vehicles.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus permanen kendaraan ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm fw-bold shadow-sm">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    @endif

                    {{-- 2. DASHBOARD RFID (SELALU MUNCUL DI ATAS) --}}
                    <div class="p-4 bg-light border-bottom">
                        <div class="row g-4">
                            
                           {{-- Kolom Kiri: Kartu RFID Aktif --}}
                            <div class="col-lg-6">
                                <div class="card border-0 shadow-sm h-100 bg-white border-start  {{ $activeRfid ? 'border-success' : 'border-danger' }}">
                                    <div class="card-body d-flex flex-column justify-content-center">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="fw-bold text-muted mb-0">Kartu RFID Aktif Saat Ini</h6>
                                            
                                            <div class="d-flex gap-1">
                                                @if($activeRfid)
                                                    {{-- Tombol Daftar Uji mengarah ke riwayat spesifik KARTU AKTIF ini --}}
                                                    <a href="{{ route('inspections.index', $activeRfid->id) }}" class="btn btn-sm btn-success shadow-sm" title="Lihat Progres Uji Kartu Ini">
                                                        <i class="bi bi-list-check"></i> Daftar Uji
                                                    </a>

                                                    {{-- Tombol Langsung ke Form Input Uji Baru --}}
                                                    <a href="{{ route('inspections.create', $activeRfid->id) }}" class="btn btn-sm btn-primary shadow-sm" title="Input Hasil Uji Baru">
                                                        <i class="bi bi-plus-lg"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        @if($activeRfid)
                                            <h3 class="mb-1 fw-bold text-success">
                                                <i class="bi bi-credit-card-2-front me-2"></i>{{ $activeRfid->kode_rfid }}
                                            </h3>
                                            <span class="text-muted small">Diaktifkan pada: {{ $activeRfid->created_at->format('d M Y, H:i') }}</span>
                                        @else
                                            <h4 class="mb-0 fw-bold text-danger">
                                                <i class="bi bi-x-circle me-2"></i>Belum Ada RFID Aktif
                                            </h4>
                                            <small class="text-muted">Aktivasi kartu di panel kanan untuk mulai melakukan pengujian.</small>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Kolom Kanan: Form Tambah RFID Baru --}}
                            @if(Auth::guard('admin')->check())
                                <div class="col-lg-6">
                                    <div class="card border border-primary shadow-sm h-100">
                                        <div class="card-body bg-white">
                                            <h6 class="fw-bold text-primary mb-2"><i class="bi bi-plus-circle me-1"></i> Aktivasi RFID Baru</h6>
                                            <form action="{{ route('rfids.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="vehicle_id" value="{{ $item->id }}">
                                                <div class="input-group mb-2 shadow-sm">
                                                    <span class="input-group-text bg-light"><i class="bi bi-upc-scan"></i></span>
                                                    <input type="text" name="kode_rfid" class="form-control" placeholder="Scan kode kartu..." required>
                                                    <button class="btn btn-primary fw-bold px-3" type="submit">Aktivasi</button>
                                                </div>
                                                <small class="text-danger" style="font-size: 0.75rem;"><i class="bi bi-exclamation-triangle me-1"></i>Aktivasi kartu baru otomatis menonaktifkan kartu lama.</small>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>

                    {{-- 3. NAV TABS UNTUK DATA & RIWAYAT --}}
                    <ul class="nav nav-tabs pt-3 px-3 bg-white border-bottom-0" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active fw-bold text-primary" data-bs-toggle="tab" data-bs-target="#tab-info-{{ $item->id }}" type="button" role="tab">
                                <i class="bi bi-file-earmark-text me-1"></i> Spesifikasi Kendaraan
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold text-secondary" data-bs-toggle="tab" data-bs-target="#tab-history-{{ $item->id }}" type="button" role="tab">
                                <i class="bi bi-clock-history me-1"></i> Riwayat RFID
                            </button>
                        </li>
                    </ul>

                    {{-- TAB CONTENTS --}}
                    <div class="tab-content p-4 bg-white">
                        
                        {{-- ================= TAB 1: DATA LENGKAP KENDARAAN ================= --}}
                        <div class="tab-pane fade show active" id="tab-info-{{ $item->id }}" role="tabpanel">
                            <div class="row g-4">
                                {{-- Kolom Kiri --}}
                                <div class="col-lg-6">
                                    <div class="card shadow-sm border-0 mb-3 bg-light">
                                        <div class="card-header bg-transparent border-bottom-0 fw-bold text-primary pb-0">A & B. Identitas</div>
                                        <div class="card-body">
                                            <dl class="row mb-0 small">
                                                <dt class="col-sm-4 text-muted">Nama Pemilik</dt><dd class="col-sm-8 fw-bold">{{ $item->user->nama ?? '-' }}</dd>
                                                <dt class="col-sm-4 text-muted">No. Uji</dt><dd class="col-sm-8 fw-bold">{{ $item->no_uji }}</dd>
                                                <dt class="col-sm-4 text-muted">No. Kendaraan</dt><dd class="col-sm-8"><span class="badge bg-dark">{{ $item->no_kendaraan }}</span></dd>
                                                <dt class="col-sm-4 text-muted">No. Rangka</dt><dd class="col-sm-8">{{ $item->no_rangka }}</dd>
                                                <dt class="col-sm-4 text-muted">No. Mesin</dt><dd class="col-sm-8">{{ $item->no_mesin }}</dd>
                                                <dt class="col-sm-4 text-muted">No. SRUT</dt><dd class="col-sm-8">{{ $item->no_srut ?? '-' }}</dd>
                                                <dt class="col-sm-4 text-muted">Tgl SRUT</dt><dd class="col-sm-8">{{ $item->tgl_srut ? $item->tgl_srut->format('d M Y') : '-' }}</dd>
                                            </dl>
                                        </div>
                                    </div>

                                    <div class="card shadow-sm border-0 bg-light">
                                        <div class="card-header bg-transparent border-bottom-0 fw-bold text-primary pb-0">C. Spesifikasi Mesin & Berat</div>
                                        <div class="card-body">
                                            <dl class="row mb-0 small">
                                                <dt class="col-sm-4 text-muted">Merk / Tipe</dt><dd class="col-sm-8">{{ $item->merk }} / {{ $item->tipe }}</dd>
                                                <dt class="col-sm-4 text-muted">Jenis</dt><dd class="col-sm-8">{{ $item->jenis }}</dd>
                                                <dt class="col-sm-4 text-muted">Tahun</dt><dd class="col-sm-8">{{ $item->tahun }}</dd>
                                                <dt class="col-sm-4 text-muted">Bahan Bakar</dt><dd class="col-sm-8">{{ $item->bahan_bakar }}</dd>
                                                <dt class="col-sm-4 text-muted">Isi Silinder (CC)</dt><dd class="col-sm-8">{{ $item->cc ?? '-' }} CC</dd>
                                                <dt class="col-sm-4 text-muted">Daya Motor</dt><dd class="col-sm-8">{{ $item->daya_hp ?? '-' }} HP</dd>
                                                <hr class="my-2 border-secondary">
                                                <dt class="col-sm-4 text-muted">JBB / JBKB</dt><dd class="col-sm-8">{{ $item->jbb ?? '-' }} Kg / {{ $item->jbkb ?? '-' }} Kg</dd>
                                                <dt class="col-sm-4 text-muted">JBI / JBKI</dt><dd class="col-sm-8">{{ $item->jbi ?? '-' }} Kg / {{ $item->jbki ?? '-' }} Kg</dd>
                                                <dt class="col-sm-4 text-muted">MST / Kosong</dt><dd class="col-sm-8">{{ $item->mst ?? '-' }} Kg / {{ $item->berat_kosong ?? '-' }} Kg</dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>

                                {{-- Kolom Kanan --}}
                                <div class="col-lg-6">
                                    <div class="card shadow-sm border-0 mb-3 bg-light">
                                        <div class="card-header bg-transparent border-bottom-0 fw-bold text-primary pb-0">C. Roda, Ban & Dimensi</div>
                                        <div class="card-body">
                                            <dl class="row mb-0 small">
                                                <dt class="col-sm-5 text-muted">Konfig Sumbu</dt><dd class="col-sm-7">{{ $item->konfigurasi_sumbu ?? '-' }}</dd>
                                                <dt class="col-sm-5 text-muted">Ban (Depan/Blk/Ring)</dt><dd class="col-sm-7">{{ $item->ban_depan ?? '-' }} / {{ $item->ban_belakang ?? '-' }} / R{{ $item->ban_ring ?? '-' }}</dd>
                                                <dt class="col-sm-5 text-muted">Sumbu (1-2 / 2-3 / 3-4)</dt><dd class="col-sm-7">{{ $item->sumbu_1_2 ?? '-' }} / {{ $item->sumbu_2_3 ?? '-' }} / {{ $item->sumbu_3_4 ?? '-' }} mm</dd>
                                                <dt class="col-sm-5 text-muted">Jalur (Depan / Blk)</dt><dd class="col-sm-7">{{ $item->jalur_depan ?? '-' }} / {{ $item->jalur_belakang ?? '-' }} mm</dd>
                                                <hr class="my-2 border-secondary">
                                                <dt class="col-sm-5 text-muted">Dimensi (P x L x T)</dt><dd class="col-sm-7">{{ $item->panjang ?? '-' }} x {{ $item->lebar ?? '-' }} x {{ $item->tinggi ?? '-' }} mm</dd>
                                                <dt class="col-sm-5 text-muted">Dim Bak (P x L x T)</dt><dd class="col-sm-7">{{ $item->panjang_bak ?? '-' }} x {{ $item->lebar_bak ?? '-' }} x {{ $item->tinggi_bak ?? '-' }} mm</dd>
                                            </dl>
                                        </div>
                                    </div>

                                    <div class="card shadow-sm border-0 bg-light">
                                        <div class="card-header bg-transparent border-bottom-0 fw-bold text-primary pb-0">C & D. Daya Angkut & Wilayah</div>
                                        <div class="card-body">
                                            <dl class="row mb-0 small">
                                                <dt class="col-sm-4 text-muted">Daya Orang</dt><dd class="col-sm-8">{{ $item->daya_orang ?? '-' }} Penumpang</dd>
                                                <dt class="col-sm-4 text-muted">Daya Barang</dt><dd class="col-sm-8">{{ $item->daya_barang ?? '-' }} Kg</dd>
                                                <dt class="col-sm-4 text-muted">Kelas Jalan</dt><dd class="col-sm-8">{{ $item->kelas_jalan ?? '-' }}</dd>
                                                <dt class="col-sm-4 text-muted">Wilayah Asal</dt><dd class="col-sm-8 fw-bold">{{ $item->wilayah ?? '-' }}</dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ================= TAB 2: TABEL RIWAYAT RFID ================= --}}
                        <div class="tab-pane fade" id="tab-history-{{ $item->id }}" role="tabpanel">
                            <div class="card border-0 shadow-sm">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="ps-3">Kode Kartu RFID</th>
                                                <th>Tanggal Aktivasi</th>
                                                <th>Status</th>
                                                <th class="text-center">Riwayat Uji</th>
                                                @if(Auth::guard('admin')->check())
                                                    <th class="text-center">Aksi</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($item->rfids()->latest()->get() as $rfid)
                                                <tr class="{{ $rfid->is_active ? 'table-success' : '' }}">
                                                    <td class="ps-3 fw-bold">{{ $rfid->kode_rfid }}</td>
                                                    <td>{{ $rfid->created_at->format('d M Y, H:i') }}</td>
                                                    <td>
                                                        @if($rfid->is_active)
                                                            <span class="badge bg-success">AKTIF</span>
                                                        @else
                                                            <span class="badge bg-secondary">NONAKTIF</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="{{ route('inspections.index', ['rfid' => $rfid->id]) }}" class="btn btn-sm btn-outline-primary shadow-sm">
                                                            <i class="bi bi-journal-text me-1"></i> Lihat Log Kartu
                                                        </a>
                                                    </td>
                                                    @if(Auth::guard('admin')->check())
                                                        <td class="text-center">
                                                            {{-- Aksi admin untuk toggle status rfid --}}
                                                        </td>
                                                    @endif
                                                </tr>
                                            @empty
                                                <tr><td colspan="5" class="text-center text-muted py-3">Belum ada RFID terdaftar untuk kendaraan ini.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="modal-footer bg-light border-top p-2">
                    <button type="button" class="btn btn-secondary shadow-sm px-4" data-bs-dismiss="modal">Tutup View</button>
                </div>

            </div>
        </div>
    </div>
@endforeach

@endsection