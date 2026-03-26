@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold text-primary"><i class="bi bi-person-plus-fill me-2"></i>Registrasi Admin / Penguji Baru</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admins.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" placeholder="Nama Lengkap & Gelar" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Email (Username Login)</label>
                            <input type="email" name="email" class="form-control" placeholder="nama@email.com" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">NRP (Nomor Registrasi Penguji)</label>
                            <input type="text" name="nrp" class="form-control" placeholder="Contoh: 1980xxxx">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Pangkat Penguji</label>
                            <select name="pangkat" class="form-select">
                                <option value="">-- Pilih Pangkat --</option>
                                <option value="Pembantu Penguji">Pembantu Penguji</option>
                                <option value="Penguji Pemula">Penguji Pemula</option>
                                <option value="Penguji Tingkat Satu">Penguji Tingkat Satu</option>
                                <option value="Penguji Tingkat Dua">Penguji Tingkat Dua</option>
                                <option value="Penguji Tingkat Tiga">Penguji Tingkat Tiga</option>
                                <option value="Penguji Tingkat Empat">Penguji Tingkat Empat</option>
                                <option value="Penguji Tingkat Lima">Penguji Tingkat Lima</option>
                                <option value="Master Penguji">Master Penguji</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Role Akses</label>
                            <select name="role" class="form-select" required>
                                <option value="admin">Admin Wilayah (Petugas)</option>
                                <option value="superadmin">Super Admin (Pusat)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Penempatan Dishub</label>
                            <select name="dishub_id" class="form-select" required>
                                <option value="">-- Pilih Lokasi Tugas --</option>
                                @foreach($dishubs as $d)
                                    <option value="{{ $d->id }}">{{ $d->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Password Default</label>
                            <input type="password" name="password" class="form-control" required placeholder="Minimal 6 Karakter">
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top text-end">
                        <a href="{{ route('admins.index') }}" class="btn btn-light px-4">Batal</a>
                        <button type="submit" class="btn btn-primary px-4">Simpan Akun Admin</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection