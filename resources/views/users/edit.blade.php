@extends('layouts.app')

@section('title', 'Edit User - Dishub System')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold text-primary mb-0">Edit Profil User</h4>
            <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">
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
                <form action="{{ route('users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-semibold small">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" value="{{ old('nama', $user->nama) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">No. Identitas (KTP/NIK)</label>
                            <input type="text" name="nomor_identitas" class="form-control" value="{{ old('nomor_identitas', $user->nomor_identitas) }}" required>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold small">Alamat</label>
                            <textarea name="alamat" class="form-control" rows="3" required>{{ old('alamat', $user->alamat) }}</textarea>
                        </div>

                        <div class="col-md-12 mt-4">
                            <div class="alert alert-info border-0 pb-0">
                                <p class="small mb-2">
                                    <i class="bi bi-info-circle-fill me-1"></i> 
                                    <strong>Tips Keamanan:</strong> Kosongkan kolom password di bawah jika tidak ingin mengubah password user.
                                </p>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold small">Ganti Password (Opsional)</label>
                                    <input type="password" name="password" class="form-control border-info" placeholder="Masukkan password baru jika ingin diganti">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top text-end">
                        <button type="submit" class="btn btn-warning px-5 shadow-sm text-white fw-bold">
                            <i class="bi bi-save me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection