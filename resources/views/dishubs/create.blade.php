@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 text-primary fw-bold">
                <i class="bi bi-plus-circle me-2"></i>Tambah Wilayah Dishub Baru
            </div>
            <div class="card-body">
                <form action="{{ route('dishubs.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        {{-- Data Wilayah --}}
                        <div class="col-md-12"><h6 class="text-muted border-bottom pb-2">Informasi Wilayah</h6></div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap Satuan Kerja</label>
                            <input type="text" name="nama" class="form-control" placeholder="Dishub Kota X" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Singkatan</label>
                            <input type="text" name="singkatan" class="form-control" placeholder="DISHUBKOTX" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Provinsi</label>
                            <input type="text" name="provinsi" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Kota</label>
                            <input type="text" name="kota" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Kecamatan</label>
                            <input type="text" name="kecamatan" class="form-control">
                        </div>

                        {{-- Data Pejabat --}}
                        <div class="col-md-12 mt-4"><h6 class="text-muted border-bottom pb-2">Informasi Pejabat (Penandatangan)</h6></div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Kepala Dinas</label>
                            <input type="text" name="kepala_dinas_nama" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">NIP Kepala Dinas</label>
                            <input type="text" name="kepala_dinas_nip" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Direktur</label>
                            <input type="text" name="direktur_nama" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">NIP Direktur</label>
                            <input type="text" name="direktur_nip" class="form-control">
                        </div>
                    </div>

                    <div class="mt-4 text-end">
                        <a href="{{ route('dishubs.index') }}" class="btn btn-light me-2">Batal</a>
                        <button type="submit" class="btn btn-primary px-4">Simpan Wilayah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection