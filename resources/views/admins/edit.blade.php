@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold text-primary"><i class="bi bi-pencil-square me-2"></i>Edit Akun Admin / Penguji</h6>
                <span class="badge bg-secondary">ID: #{{ $admin->id }}</span>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admins.update', $admin->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" value="{{ old('nama', $admin->nama) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Email (Username Login)</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $admin->email) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">NRP</label>
                            <input type="text" name="nrp" class="form-control" value="{{ old('nrp', $admin->nrp) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Pangkat Penguji</label>
                            <select name="pangkat" class="form-select">
                                <option value="">-- Pilih Pangkat --</option>
                                @foreach(['Pembantu Penguji', 'Penguji Pemula', 'Penguji Tingkat Satu', 'Penguji Tingkat Dua', 'Penguji Tingkat Tiga', 'Penguji Tingkat Empat', 'Penguji Tingkat Lima', 'Master Penguji'] as $p)
                                    <option value="{{ $p }}" {{ $admin->pangkat == $p ? 'selected' : '' }}>{{ $p }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Role Akses</label>
                            <select name="role" class="form-select" required>
                                <option value="admin" {{ $admin->role == 'admin' ? 'selected' : '' }}>Admin Wilayah</option>
                                <option value="superadmin" {{ $admin->role == 'superadmin' ? 'selected' : '' }}>Super Admin</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Penempatan Dishub</label>
                            <select name="dishub_id" class="form-select" required>
                                @foreach($dishubs as $d)
                                    <option value="{{ $d->id }}" {{ $admin->dishub_id == $d->id ? 'selected' : '' }}>{{ $d->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Ganti Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin ganti">
                            <div class="form-text" style="font-size: 0.75rem;">Minimal 6 karakter jika ingin diubah.</div>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top text-end">
                        <a href="{{ route('admins.index') }}" class="btn btn-light px-4">Batal</a>
                        <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection