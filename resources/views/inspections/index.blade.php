@extends('layouts.app')
@section('title', 'Riwayat Uji - ' . $vehicle->no_kendaraan)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0 text-primary"><i class="bi bi-journal-check me-2"></i>Riwayat Uji Berkala</h4>
        <p class="text-muted mb-0">{{ $vehicle->merk }} {{ $vehicle->tipe }} | <b>{{ $vehicle->no_kendaraan }}</b></p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('vehicles.index') }}" class="btn btn-light border shadow-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        
        {{-- PENGECEKAN ROLE: Tombol ini HANYA MUNCUL untuk Admin --}}
        @if(Auth::guard('admin')->check())
            
            {{-- Pengecekan Status Kartu (Hanya dieksekusi jika yang login Admin) --}}
            @if($rfid->is_active)
                <a href="{{ route('inspections.create', $rfid->id) }}" class="btn btn-primary shadow-sm">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Uji Baru
                </a>
            @else
                <button class="btn btn-secondary shadow-sm" disabled title="Kartu ini sudah Non-Aktif">
                    <i class="bi bi-lock-fill"></i> Tambah Uji Baru
                </button>
            @endif

        @endif
        {{-- AKHIR PENGECEKAN ROLE --}}
        
    </div>
</div>

{{-- INFORMASI KENDARAAN & KARTU --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-3 border-start  border-primary">
            <small class="text-muted d-block">No. Uji Kendaraan</small>
            <span class="fw-bold fs-5 text-dark">{{ $vehicle->no_uji }}</span>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-3 border-start  {{ $rfid->is_active ? 'border-success' : 'border-secondary' }}">
            <small class="text-muted d-block">Log Kartu RFID {{ !$rfid->is_active ? '(Non-Aktif)' : '' }}</small>
            <span class="fw-bold fs-5 {{ $rfid->is_active ? 'text-success' : 'text-secondary' }}">
                <i class="bi bi-credit-card-2-front me-1"></i> {{ $rfid->kode_rfid }}
            </span>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-3 border-start border-info">
            <small class="text-muted d-block">Total Uji Pada Kartu Ini</small>
            <span class="fw-bold fs-5 text-info">{{ $inspections->count() }} Kali</span>
        </div>
    </div>
</div>

{{-- TABEL RIWAYAT UJI KHUSUS RFID TERPILIH --}}
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
                    @forelse($inspections as $index => $uji)
                        <tr>
                            <td class="ps-4 fw-bold text-primary">{{ $index + 1 }}</td>
                            <td>{{ $uji->tgl_uji->format('d M Y') }}</td>
                            <td>
                                @if($uji->tgl_berlaku)
                                    <span class="fw-semibold">{{ $uji->tgl_berlaku->format('d M Y') }}</span>
                                @else
                                    <span class="text-muted small italic">Belum ditentukan</span>
                                @endif
                            </td>
                            <td>
                                @if($uji->hasil == 'Lolos Uji Berkala')
                                    <span class="badge bg-success shadow-sm"><i class="bi bi-check-circle me-1"></i> LOLOS</span>
                                @elseif($uji->hasil == 'Tidak Lolos Uji Berkala')
                                    <span class="badge bg-danger shadow-sm"><i class="bi bi-x-circle me-1"></i> TIDAK LOLOS</span>
                                @else
                                    <span class="badge bg-warning text-dark shadow-sm"><i class="bi bi-clock me-1"></i> MENUNGGU</span>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold small">{{ $uji->nama_petugas }}</div>
                                <div class="text-muted" style="font-size: 0.75rem;">NRP: {{ $uji->nrp }}</div>
                            </td>
                            <td class="text-center">
                                <div class="btn-group shadow-sm">
                                    <a href="{{ route('inspections.show', $uji->id) }}" class="btn btn-sm btn-outline-primary" title="Lihat Detail & Cetak">
                                        <i class="bi bi-printer me-1"></i> Detail
                                    </a>
                                    
                                    @if(Auth::guard('admin')->check())
                                        <form action="{{ route('inspections.destroy', $uji->id) }}" method="POST" onsubmit="return confirm('Hapus riwayat uji ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger border-start-0">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
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

{{-- Info Tambahan --}}
<div class="mt-3">
    <small class="text-muted">
        <i class="bi bi-info-circle me-1"></i> 
        Menampilkan riwayat pengujian yang terikat pada kartu <b>{{ $rfid->kode_rfid }}</b>. 
        Riwayat pada kartu lain dapat dilihat melalui tab <i>Riwayat RFID</i> di halaman utama.
    </small>
</div>
@endsection