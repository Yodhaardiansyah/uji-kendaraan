{{-- Mewarisi kerangka utama website dari 'layouts.app' --}}
@extends('layouts.app')

{{-- Menentukan judul halaman untuk tab browser --}}
@section('title', 'Data Kendaraan - Dishub System')

{{-- Membuka bagian konten utama --}}
@section('content')

{{-- ================= LOGIKA TAB AKTIF & FILTER ================= --}}
@php
    // Mengecek apakah user sedang mencari spesifik berdasarkan wilayah (baik dari form atau redirect URL)
    $hasWilayahFilter = request()->filled('wilayah');
    // Jika ada filter wilayah, otomatis aktifkan tab Dishub. Jika tidak, default tab Pemilik.
    $activeTab = $hasWilayahFilter ? 'dishub' : 'pemilik';
@endphp

{{-- ================= HEADER HALAMAN & TOMBOL TAMBAH ================= --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0 text-primary"><i class="bi bi-truck me-2"></i>Data Kendaraan Bermotor</h4>
    
    @if(Auth::guard('admin')->check())
        <a href="{{ route('vehicles.create') }}" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> Tambah Kendaraan
        </a>
    @endif
</div>

{{-- ================= FORM PENCARIAN GLOBAL & FILTER WILAYAH ================= --}}
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body p-3">
        <form action="{{ route('vehicles.index') }}" method="GET" class="d-flex flex-wrap gap-2">
            
            {{-- Input Pencarian Teks Bebas --}}
            <div class="input-group shadow-sm flex-grow-1" style="min-width: 250px;">
                <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari No Uji, Plat, Merk, atau Nama Pemilik..." value="{{ request('search') }}">
            </div>

            {{-- Dropdown Filter Khusus Wilayah Dishub --}}
            <select name="wilayah" class="form-select shadow-sm" style="max-width: 250px;">
                <option value="">-- Semua Wilayah Dishub --</option>
                {{-- Mengambil daftar wilayah unik dari koleksi kendaraan untuk opsi dropdown --}}
                @foreach(collect($vehicles)->pluck('wilayah')->unique()->filter()->sort() as $w)
                    <option value="{{ $w }}" {{ request('wilayah') == $w ? 'selected' : '' }}>
                        {{ $w }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-secondary px-4 shadow-sm fw-bold">Terapkan</button>
            
            {{-- Tombol Reset muncul jika ada pencarian teks ATAU filter wilayah --}}
            @if(request('search') || request('wilayah'))
                <a href="{{ route('vehicles.index') }}" class="btn btn-outline-danger shadow-sm"><i class="bi bi-x-circle"></i> Reset</a>
            @endif
        </form>
    </div>
</div>

{{-- ================= TOGGLE MODE TAMPILAN ================= --}}
<ul class="nav nav-pills mb-4 bg-white p-2 rounded-3 shadow-sm d-inline-flex" id="viewModeTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-bold {{ $activeTab === 'pemilik' ? 'active' : '' }}" id="pemilik-tab" data-bs-toggle="pill" data-bs-target="#view-pemilik" type="button" role="tab" aria-controls="view-pemilik" aria-selected="{{ $activeTab === 'pemilik' ? 'true' : 'false' }}">
            <i class="bi bi-person-lines-fill me-2"></i>Per Pemilik
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-bold {{ $activeTab === 'dishub' ? 'active' : '' }}" id="dishub-tab" data-bs-toggle="pill" data-bs-target="#view-dishub" type="button" role="tab" aria-controls="view-dishub" aria-selected="{{ $activeTab === 'dishub' ? 'true' : 'false' }}">
            <i class="bi bi-building me-2"></i>Per Dishub
        </button>
    </li>
</ul>

{{-- ================= KONTEN TAMPILAN (TABS) ================= --}}
<div class="tab-content" id="viewModeTabContent">

    {{-- TAB 1: DAFTAR KENDARAAN (GROUPED BY PEMILIK) --}}
    <div class="tab-pane fade {{ $activeTab === 'pemilik' ? 'show active' : '' }}" id="view-pemilik" role="tabpanel" aria-labelledby="pemilik-tab">
        <div class="accordion shadow-sm" id="accordionVehicles">
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
                        Data tidak ditemukan.
                    </div>
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-end mb-4 mt-3">
            {{ $users->links('pagination::bootstrap-5') }}
        </div>
    </div>

    {{-- TAB 2: DAFTAR KENDARAAN (GROUPED BY DISHUB) --}}
    <div class="tab-pane fade {{ $activeTab === 'dishub' ? 'show active' : '' }}" id="view-dishub" role="tabpanel" aria-labelledby="dishub-tab">
        @php
            $groupedByDishub = collect($vehicles)->groupBy('wilayah');
            
            // LOGIKA FILTER: Jika parameter URL 'wilayah' diisi, buang semua grup lain kecuali wilayah yang dicari
            if (request()->filled('wilayah')) {
                $groupedByDishub = $groupedByDishub->filter(function($value, $key) {
                    return $key == request('wilayah');
                });
            }
        @endphp

        <div class="accordion shadow-sm" id="accordionDishub">
            @forelse($groupedByDishub as $wilayah => $dishubVehicles)
                @php 
                    $collapseIdDishub = 'collapseDishub' . Str::slug($wilayah ?: 'unknown');
                    
                    // Jika data difilter berdasarkan wilayah spesifik, otomatis BUKA (expand) accordion-nya
                    $isExpanded = request()->filled('wilayah') && request('wilayah') == $wilayah;
                @endphp

                <div class="accordion-item border-0 mb-3 rounded-3 overflow-hidden">
                    <h2 class="accordion-header" id="heading{{ $collapseIdDishub }}">
                        {{-- Hapus class 'collapsed' dan set aria-expanded='true' jika harus terbuka --}}
                        <button class="accordion-button bg-white fw-bold text-dark py-3 {{ $isExpanded ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $collapseIdDishub }}" aria-expanded="{{ $isExpanded ? 'true' : 'false' }}">
                            <div class="d-flex align-items-center w-100 pe-3">
                                <div class="bg-light p-2 rounded-circle me-3">
                                    <i class="bi bi-geo-alt fs-5 text-success"></i>
                                </div>
                                <div>
                                    <div class="fs-6">Dishub {{ $wilayah ?: 'Wilayah Tidak Diketahui' }}</div>
                                </div>
                                <div class="ms-auto">
                                    <span class="badge bg-success rounded-pill px-3 py-2 shadow-sm">
                                        {{ $dishubVehicles->count() }} Kendaraan
                                    </span>
                                </div>
                            </div>
                        </button>
                    </h2>
                    
                    {{-- Tambahkan class 'show' jika harus terbuka otomatis --}}
                    <div id="{{ $collapseIdDishub }}" class="accordion-collapse collapse {{ $isExpanded ? 'show' : '' }}" data-bs-parent="{{ $isExpanded ? '' : '#accordionDishub' }}">
                        <div class="accordion-body p-0 border-top border-light">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4 py-3" style="width: 25%;">Pemilik</th>
                                            <th style="width: 15%;">No. Uji</th>
                                            <th style="width: 20%;">No. Plat</th>
                                            <th style="width: 20%;">Merk / Tipe</th>
                                            <th class="text-center" style="width: 20%;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($dishubVehicles as $item)
                                            <tr>
                                                <td class="ps-4 fw-bold">
                                                    {{ $item->user->nama ?? 'Tidak Diketahui' }}
                                                </td>
                                                <td class="text-secondary">{{ $item->no_uji }}</td>
                                                <td><span class="badge bg-dark fs-6 shadow-sm">{{ $item->no_kendaraan }}</span></td>
                                                <td>
                                                    <div class="fw-bold">{{ $item->merk }}</div>
                                                    <small class="text-muted">{{ $item->tipe }}</small>
                                                </td>
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
                        <i class="bi bi-building-x fs-1 d-block mb-3"></i>
                        Data wilayah "<b>{{ request('wilayah') }}</b>" tidak ditemukan.
                    </div>
                </div>
            @endforelse
        </div>
    </div>

</div>

{{-- ================= AREA MODAL VIEW LENGKAP ================= --}}
{{-- Kode Modal biarkan sama persis seperti yang Anda berikan, tidak ada perubahan yang dibutuhkan di sini --}}
@foreach($vehicles as $item)
    <div class="modal fade" id="modalDetail{{ $item->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            {{-- ... (Isi Modal Detail Tetap Sama) ... --}}
            <div class="modal-content border-0 shadow-lg">

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

                    <div class="p-4 bg-light border-bottom">
                        <div class="row g-4">
                            <div class="col-lg-6">
                                <div class="card border-0 shadow-sm h-100 bg-white border-start {{ $activeRfid ? 'border-success' : 'border-danger' }}">
                                    <div class="card-body d-flex flex-column justify-content-center">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="fw-bold text-muted mb-0">Kartu RFID Aktif Saat Ini</h6>
                                            <div class="d-flex gap-1">
                                                @if($activeRfid)
                                                    <a href="{{ route('inspections.index', $activeRfid->id) }}" class="btn btn-sm btn-success shadow-sm"><i class="bi bi-list-check"></i> Daftar Uji</a>
                                                    <a href="{{ route('inspections.create', $activeRfid->id) }}" class="btn btn-sm btn-primary shadow-sm"><i class="bi bi-plus-lg"></i></a>
                                                @endif
                                            </div>
                                        </div>
                                        @if($activeRfid)
                                            <h3 class="mb-1 fw-bold text-success"><i class="bi bi-credit-card-2-front me-2"></i>{{ $activeRfid->kode_rfid }}</h3>
                                            <span class="text-muted small">Diaktifkan pada: {{ $activeRfid->created_at->format('d M Y, H:i') }}</span>
                                        @else
                                            <h4 class="mb-0 fw-bold text-danger"><i class="bi bi-x-circle me-2"></i>Belum Ada RFID Aktif</h4>
                                        @endif
                                    </div>
                                </div>
                            </div>

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

                    <ul class="nav nav-tabs pt-3 px-3 bg-white border-bottom-0" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active fw-bold text-primary" data-bs-toggle="tab" data-bs-target="#tab-info-{{ $item->id }}" type="button" role="tab"><i class="bi bi-file-earmark-text me-1"></i> Spesifikasi Kendaraan</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link fw-bold text-secondary" data-bs-toggle="tab" data-bs-target="#tab-history-{{ $item->id }}" type="button" role="tab"><i class="bi bi-clock-history me-1"></i> Riwayat RFID</button>
                        </li>
                    </ul>

                    <div class="tab-content p-4 bg-white">
                        <div class="tab-pane fade show active" id="tab-info-{{ $item->id }}" role="tabpanel">
                            <div class="row g-4">
                                <div class="col-lg-6">
                                    <div class="card shadow-sm border-0 mb-3 bg-light">
                                        <div class="card-header bg-transparent border-bottom-0 fw-bold text-primary pb-0">A & B. Identitas</div>
                                        <div class="card-body">
                                            <dl class="row mb-0 small">
                                                <dt class="col-sm-4 text-muted">Nama Pemilik</dt>
                                                {{-- Sesuaikan nama relasi user jika berbeda --}}
                                                <dd class="col-sm-8 fw-bold">{{ $item->user->nama ?? '-' }}</dd>
                                                <dt class="col-sm-4 text-muted">No. Uji</dt><dd class="col-sm-8 fw-bold">{{ $item->no_uji }}</dd>
                                                <dt class="col-sm-4 text-muted">No. Kendaraan</dt><dd class="col-sm-8"><span class="badge bg-dark">{{ $item->no_kendaraan }}</span></dd>
                                                <dt class="col-sm-4 text-muted">No. Rangka</dt><dd class="col-sm-8">{{ $item->no_rangka }}</dd>
                                                <dt class="col-sm-4 text-muted">No. Mesin</dt><dd class="col-sm-8">{{ $item->no_mesin }}</dd>
                                            </dl>
                                        </div>
                                    </div>
                                    <div class="card shadow-sm border-0 bg-light">
                                        <div class="card-header bg-transparent border-bottom-0 fw-bold text-primary pb-0">C. Spesifikasi Mesin & Berat</div>
                                        <div class="card-body">
                                            <dl class="row mb-0 small">
                                                <dt class="col-sm-4 text-muted">Merk / Tipe</dt><dd class="col-sm-8">{{ $item->merk }} / {{ $item->tipe }}</dd>
                                                <dt class="col-sm-4 text-muted">Bahan Bakar</dt><dd class="col-sm-8">{{ $item->bahan_bakar }}</dd>
                                                <dt class="col-sm-4 text-muted">JBB / JBKB</dt><dd class="col-sm-8">{{ $item->jbb ?? '-' }} Kg / {{ $item->jbkb ?? '-' }} Kg</dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="card shadow-sm border-0 mb-3 bg-light">
                                        <div class="card-header bg-transparent border-bottom-0 fw-bold text-primary pb-0">C. Roda, Ban & Dimensi</div>
                                        <div class="card-body">
                                            <dl class="row mb-0 small">
                                                <dt class="col-sm-5 text-muted">Dimensi (P x L x T)</dt><dd class="col-sm-7">{{ $item->panjang ?? '-' }} x {{ $item->lebar ?? '-' }} x {{ $item->tinggi ?? '-' }} mm</dd>
                                                <dt class="col-sm-5 text-muted">Wilayah Asal</dt><dd class="col-sm-7 fw-bold">{{ $item->wilayah ?? '-' }}</dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tab-history-{{ $item->id }}" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-3">Kode Kartu RFID</th>
                                            <th>Tanggal Aktivasi</th>
                                            <th>Status</th>
                                            <th class="text-center">Riwayat Uji</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($item->rfids()->latest()->get() as $rfid)
                                            <tr class="{{ $rfid->is_active ? 'table-success' : '' }}">
                                                <td class="ps-3 fw-bold">{{ $rfid->kode_rfid }}</td>
                                                <td>{{ $rfid->created_at->format('d M Y, H:i') }}</td>
                                                <td>
                                                    {!! $rfid->is_active ? '<span class="badge bg-success">AKTIF</span>' : '<span class="badge bg-secondary">NONAKTIF</span>' !!}
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('inspections.index', ['rfid' => $rfid->id]) }}" class="btn btn-sm btn-outline-primary shadow-sm">
                                                        <i class="bi bi-journal-text me-1"></i> Lihat Log Kartu
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="text-center text-muted py-3">Belum ada RFID terdaftar.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
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