@extends('layouts.app')

@section('title', 'Edit Wilayah - Dishub System')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold text-primary mb-0">Edit Wilayah Dishub</h4>
            <a href="{{ route('dishubs.index') }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger shadow-sm">
                <ul class="mb-0 small">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form action="{{ route('dishubs.update', $dishub->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        {{-- Data Wilayah --}}
                        <div class="col-md-12">
                            <h6 class="text-primary fw-bold border-bottom pb-2">
                                <i class="bi bi-geo-alt me-1"></i> Informasi Wilayah
                            </h6>
                        </div>
                        
                        <div class="col-md-8">
                            <label class="form-label fw-semibold small">Nama Lengkap Satuan Kerja</label>
                            <input type="text" name="nama" class="form-control" value="{{ old('nama', $dishub->nama) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Singkatan</label>
                            <input type="text" name="singkatan" class="form-control" value="{{ old('singkatan', $dishub->singkatan) }}" required>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Provinsi</label>
                            <input type="text" name="provinsi" class="form-control" value="{{ old('provinsi', $dishub->provinsi) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Kota/Kabupaten</label>
                            <input type="text" name="kota" class="form-control" value="{{ old('kota', $dishub->kota) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Kecamatan</label>
                            <input type="text" name="kecamatan" class="form-control" value="{{ old('kecamatan', $dishub->kecamatan) }}">
                        </div>

                        {{-- Data Pejabat --}}
                        <div class="col-md-12 mt-4">
                            <h6 class="text-primary fw-bold border-bottom pb-2">
                                <i class="bi bi-person-badge me-1"></i> Informasi Pejabat (Penandatangan)
                            </h6>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Nama Kepala Dinas</label>
                            <input type="text" name="kepala_dinas_nama" class="form-control" value="{{ old('kepala_dinas_nama', $dishub->kepala_dinas_nama) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">NIP Kepala Dinas</label>
                            <input type="text" name="kepala_dinas_nip" class="form-control" value="{{ old('kepala_dinas_nip', $dishub->kepala_dinas_nip) }}">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Nama Direktur / Kabid</label>
                            <input type="text" name="direktur_nama" class="form-control" value="{{ old('direktur_nama', $dishub->direktur_nama) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">NIP Direktur / Kabid</label>
                            <input type="text" name="direktur_nip" class="form-control" value="{{ old('direktur_nip', $dishub->direktur_nip) }}">
                        </div>
                    </div>

                    <div class="mt-5 pt-3 border-top text-end">
                        <button type="submit" class="btn btn-warning px-5 shadow-sm text-white fw-bold">
                            <i class="bi bi-check2-all me-1"></i> Perbarui Data Wilayah
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection