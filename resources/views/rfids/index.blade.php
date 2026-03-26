@extends('layouts.app')
@section('title', 'Data RFID - Dishub System')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0 text-primary"><i class="bi bi-credit-card-2-front me-2"></i>Manajemen Kartu RFID</h4>
    
    @if(Auth::guard('admin')->check())
        <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahRfid">
            <i class="bi bi-plus-circle me-1"></i> Daftarkan Kartu Baru
        </button>
    @endif
</div>

{{-- ================= FORM PENCARIAN & FILTER ================= --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form action="{{ route('rfids.index') }}" method="GET" class="row g-2">
            
            {{-- Input Pencarian Global --}}
            <div class="col-md-{{ Auth::guard('admin')->check() ? '5' : '10' }}">
                <div class="input-group shadow-sm h-100">
                    <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari Kode RFID, No Uji, Plat, NIK, Nama..." value="{{ request('search') }}">
                </div>
            </div>

            {{-- Filter Pemilik (Hanya Muncul Untuk Admin) --}}
            @if(Auth::guard('admin')->check())
            <div class="col-md-4">
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

            {{-- Tombol Aksi --}}
            <div class="col-md-{{ Auth::guard('admin')->check() ? '3' : '2' }} d-flex gap-2">
                <button type="submit" class="btn btn-secondary shadow-sm fw-bold grow w-100">
                    <i class="bi bi-search me-1"></i> Cari
                </button>
                @if(request('search') || request('user_id'))
                    <a href="{{ route('rfids.index') }}" class="btn btn-outline-danger shadow-sm" title="Reset Pencarian">
                        <i class="bi bi-x-circle"></i>
                    </a>
                @endif
            </div>

        </form>
    </div>
</div>

{{-- ================= DAFTAR RFID (GROUPED BY KENDARAAN) ================= --}}
<div class="accordion shadow-sm" id="accordionRfids">
    @forelse($vehicles as $vehicle)
        @php 
            // Ambil RFID milik kendaraan ini, urutkan terbaru
            $rfids = $vehicle->rfids->sortByDesc('created_at');
            $collapseId = 'collapseVehicle' . $vehicle->id;
        @endphp

        <div class="accordion-item border-0 mb-3 rounded-3 overflow-hidden">
            <h2 class="accordion-header" id="heading{{ $collapseId }}">
                <button class="accordion-button collapsed bg-white fw-bold text-dark py-3" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}" aria-expanded="false">
                    <div class="d-flex align-items-center w-100 pe-3">
                        <div class="bg-light p-2 rounded-circle me-3">
                            <i class="bi bi-truck fs-5 text-primary"></i>
                        </div>
                        <div>
                            <div class="fs-6 text-uppercase">{{ $vehicle->no_kendaraan }} <span class="text-muted mx-1">|</span> {{ $vehicle->no_uji }}</div>
                            <small class="text-muted fw-normal">
                                <i class="bi bi-person me-1"></i>Pemilik: <b>{{ $vehicle->user->nama ?? '-' }}</b> 
                                <span class="ms-2 d-none d-md-inline small">(NIK: {{ $vehicle->user->nomor_identitas ?? '-' }})</span>
                            </small>
                        </div>
                        <div class="ms-auto d-flex gap-2 align-items-center">
                            @php $activeCount = $rfids->where('is_active', true)->count(); @endphp
                            
                            @if($activeCount > 0)
                                <span class="badge bg-success rounded-pill px-3 py-2 shadow-sm">{{ $activeCount }} Aktif</span>
                            @endif
                            <span class="badge bg-secondary rounded-pill px-3 py-2 shadow-sm">{{ $rfids->count() }} Kartu</span>
                        </div>
                    </div>
                </button>
            </h2>
            
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
                                @foreach($rfids as $rfid)
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
                                        <td class="text-center text-nowrap">
                                            <div class="d-flex justify-content-center align-items-center gap-1">
                                                {{-- Tombol Aksi Admin --}}
                                                @if(Auth::guard('admin')->check())
                                                    <a href="{{ route('inspections.index', $rfid->id) }}" class="btn btn-sm btn-info text-white shadow-sm" title="Lihat Daftar Uji">
                                                        <i class="bi bi-journal-text"></i>
                                                    </a>
                                                    <form action="{{ route('rfids.toggle', $rfid->id) }}" method="POST" class="m-0 p-0 d-flex">
                                                        @csrf @method('PATCH')
                                                        <button type="submit" class="btn btn-sm shadow-sm {{ $rfid->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}">
                                                            <i class="bi bi-power"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('rfids.destroy', $rfid->id) }}" method="POST" class="m-0 p-0 d-flex" onsubmit="return confirm('Hapus?')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-secondary btn-sm shadow-sm"><i class="bi bi-trash"></i></button>
                                                    </form>
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
    @empty
        {{-- Pesan Kosong --}}
    @endforelse
</div>

{{-- ================= PAGINATION ================= --}}
<div class="d-flex justify-content-end mb-4 mt-3">
    {{ $vehicles->links('pagination::bootstrap-5') }}
</div>

{{-- ================= MODAL TAMBAH RFID BARU (Hanya Admin) ================= --}}
@if(Auth::guard('admin')->check())
<div class="modal fade" id="modalTambahRfid" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('rfids.store') }}" method="POST" class="modal-content border-0 shadow-lg">
            @csrf
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2"></i>Daftarkan Kartu RFID</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-light">
                
                <div class="mb-4">
                    <label class="form-label fw-bold text-secondary">Scan / Input Kode RFID <span class="text-danger">*</span></label>
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-white"><i class="bi bi-upc-scan"></i></span>
                        <input type="text" name="kode_rfid" class="form-control" placeholder="Arahkan kursor ke sini, lalu scan kartu..." required autofocus>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold text-secondary">Pilih Kendaraan Tujuan <span class="text-danger">*</span></label>
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

{{-- ================= LIBRARY SELECT2 & JQUERY ================= --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        // Select2 untuk form Modal Kendaraan
        $('#selectKendaraan').select2({
            theme: 'bootstrap-5',
            dropdownParent: $('#modalTambahRfid'),
            placeholder: '-- Ketik/Pilih No Uji atau Plat Kendaraan --',
            width: '100%'
        });

        // Select2 untuk filter Pemilik di halaman index
        $('#filterPemilik').select2({
            theme: 'bootstrap-5',
            placeholder: '-- Filter Semua Pemilik --',
            width: '100%'
        });

        $('#modalTambahRfid').on('shown.bs.modal', function () {
            $('input[name="kode_rfid"]').trigger('focus');
        });
    });
</script>