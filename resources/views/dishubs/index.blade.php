{{-- Mewarisi kerangka layout utama aplikasi --}}
@extends('layouts.app')

{{-- Membuka bagian konten utama --}}
@section('content')

{{-- Card Container: Membungkus keseluruhan halaman --}}
<div class="card shadow-sm border-0">
    
    {{-- HEADER KARTU --}}
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-geo-alt me-2"></i>Daftar Wilayah Dishub</h5>
        
        @if(Auth::guard('admin')->user()->role === 'superadmin')
            <a href="{{ route('dishubs.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> Tambah Wilayah
            </a>
        @endif
    </div>
    
    <div class="card-body">
        
        {{-- MENGELOMPOKKAN DATA BERDASARKAN PROVINSI --}}
        @php
            // Pastikan $dishubs adalah Collection. Jika berupa array, gunakan collect($dishubs)
            $groupedDishubs = $dishubs->groupBy('provinsi');
        @endphp

        {{-- Container Accordion --}}
        <div class="accordion" id="accordionProvinsi">
            
            {{-- Looping per Provinsi --}}
            @foreach($groupedDishubs as $provinsi => $dishubList)
                @php
                    // Membuat ID unik untuk accordion berdasarkan nama provinsi (menghapus spasi dll)
                    $collapseId = 'collapse-' . Str::slug($provinsi);
                @endphp
                
                {{-- Item Accordion (Diberi margin bottom agar terpisah seperti di gambar) --}}
                <div class="accordion-item mb-3 border rounded shadow-sm">
                    <h2 class="accordion-header" id="heading-{{ $collapseId }}">
                        {{-- Tombol Header Accordion --}}
                        <button class="accordion-button collapsed py-3" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}" aria-expanded="false" aria-controls="{{ $collapseId }}">
                            <div class="d-flex justify-content-between align-items-center w-100 me-3">
                                <div>
                                    <i class="bi bi-map text-primary me-2"></i>
                                    <span class="fw-bold fs-6">Provinsi {{ $provinsi ?: 'Tidak Diketahui' }}</span>
                                </div>
                                {{-- Badge Total Data seperti referensi gambar --}}
                                <span class="badge bg-primary rounded-pill px-3 py-2">
                                    {{ $dishubList->count() }} Total Dishub
                                </span>
                            </div>
                        </button>
                    </h2>
                    
                    {{-- Body Accordion (Tabel Daftar Dishub per Provinsi) --}}
                    <div id="{{ $collapseId }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ $collapseId }}" data-bs-parent="#accordionProvinsi">
                        <div class="accordion-body p-0">
                            <div class="table-responsive">
                                {{-- Tabel tidak menggunakan border luar agar menyatu dengan accordion --}}
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4">Nama Wilayah</th>
                                            <th>Singkatan</th>
                                            <th>Kepala Dinas</th>
                                            <th class="text-center pe-4">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- Looping data dishub di dalam provinsi terkait --}}
                                        @foreach($dishubList as $dishub)
                                        <tr>
                                            <td class="fw-bold ps-4">
                                                <i class="bi bi-building text-muted me-2"></i>{{ $dishub->nama }}
                                            </td>
                                            <td><span class="badge bg-secondary">{{ $dishub->singkatan }}</span></td>
                                            <td>{{ $dishub->kepala_dinas_nama }}</td>
                                            
                                            {{-- KOLOM AKSI --}}
                                            <td class="text-center pe-4">
                                                <button type="button" class="btn btn-info btn-sm text-white" data-bs-toggle="modal" data-bs-target="#modalDetail{{ $dishub->id }}" title="Detail">
                                                    <i class="bi bi-eye"></i>
                                                </button>

                                                @if(Auth::guard('admin')->user()->role === 'superadmin')
                                                    <a href="{{ route('dishubs.edit', $dishub->id) }}" class="btn btn-warning btn-sm text-white" title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    
                                                    <form action="{{ route('dishubs.destroy', $dishub->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus wilayah ini?')">
                                                        @csrf 
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" title="Hapus"><i class="bi bi-trash"></i></button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>

                                        {{-- START MODAL DETAIL (Tetap diletakkan di dalam loop) --}}
                                        <div class="modal fade" id="modalDetail{{ $dishub->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content border-0 shadow">
                                                    
                                                    <div class="modal-header bg-primary text-white">
                                                        <h5 class="modal-title"><i class="bi bi-building me-2"></i>Profil {{ $dishub->singkatan }}</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    
                                                    <div class="modal-body p-4">
                                                        <div class="row">
                                                            <div class="col-md-6 border-end">
                                                                <p class="text-muted mb-1 small fw-bold">NAMA INSTANSI</p>
                                                                <p class="fw-bold text-primary">{{ $dishub->nama }}</p>
                                                                
                                                                <p class="text-muted mb-1 small fw-bold">LOKASI</p>
                                                                <p class="mb-0">{{ $dishub->kecamatan }}, {{ $dishub->kota }}</p>
                                                                <p>{{ $dishub->provinsi }}</p>
                                                            </div>
                                                            
                                                            <div class="col-md-6 px-4">
                                                                <p class="text-muted mb-1 small fw-bold">KEPALA DINAS</p>
                                                                <p class="fw-bold mb-0">{{ $dishub->kepala_dinas_nama }}</p>
                                                                <p class="small text-muted mb-3">NIP: {{ $dishub->kepala_dinas_nip }}</p>

                                                                <p class="text-muted mb-1 small fw-bold">DIREKTUR</p>
                                                                <p class="fw-bold mb-0">{{ $dishub->direktur_nama ?? '-' }}</p>
                                                                <p class="small text-muted">NIP: {{ $dishub->direktur_nip ?? '-' }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="modal-footer bg-light py-2">
                                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                                                        @if(Auth::guard('admin')->user()->role === 'superadmin')
                                                            <a href="{{ route('dishubs.edit', $dishub->id) }}" class="btn btn-warning btn-sm text-white">Edit Wilayah</a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- END MODAL --}}

                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</div>
@endsection