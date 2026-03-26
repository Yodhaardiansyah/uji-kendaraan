@extends('layouts.app')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-geo-alt me-2"></i>Daftar Wilayah Dishub</h5>
        
        @if(Auth::guard('admin')->user()->role === 'superadmin')
            <a href="{{ route('dishubs.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> Tambah Wilayah
            </a>
        @endif
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nama Wilayah</th>
                        <th>Singkatan</th>
                        <th>Provinsi</th>
                        <th>Kepala Dinas</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dishubs as $dishub)
                    <tr>
                        <td class="fw-bold">{{ $dishub->nama }}</td>
                        <td><span class="badge bg-secondary">{{ $dishub->singkatan }}</span></td>
                        <td>{{ $dishub->provinsi }}</td>
                        <td>{{ $dishub->kepala_dinas_nama }}</td>
                        <td class="text-center">
                            {{-- TOMBOL TRIGGER MODAL --}}
                            <button type="button" class="btn btn-info btn-sm text-white" data-bs-toggle="modal" data-bs-target="#modalDetail{{ $dishub->id }}" title="Detail">
                                <i class="bi bi-eye"></i>
                            </button>

                            @if(Auth::guard('admin')->user()->role === 'superadmin')
                                <a href="{{ route('dishubs.edit', $dishub->id) }}" class="btn btn-warning btn-sm text-white" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('dishubs.destroy', $dishub->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus wilayah ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                                </form>
                            @endif
                        </td>
                    </tr>

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
@endsection