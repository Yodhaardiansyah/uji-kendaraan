@extends('layouts.app')
@section('title', 'Pengaturan Akun')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-gear-fill me-2"></i>Pengaturan Keamanan</h5>
            </div>
            <div class="card-body p-4">
                
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Seksi Email --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold">Alamat Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email', $user->email) }}" required>
                        </div>
                        @error('email')
                            <div class="small text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="my-4 opacity-25">

                    {{-- Seksi Password --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Password Saat Ini <span class="text-danger">*</span></label>
                        <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" 
                               placeholder="Konfirmasi password lama Anda" required>
                        @error('current_password')
                            <div class="small text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Password Baru (Opsional)</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                               placeholder="Kosongkan jika tidak ingin mengubah">
                        @error('password')
                            <div class="small text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-control" 
                               placeholder="Ketik ulang password baru">
                    </div>

                    <div class="d-grid gap-2 mt-5">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold shadow-sm">
                            <i class="bi bi-check-circle me-1"></i> Simpan Perubahan
                        </button>
                        <a href="javascript:history.back()" class="btn btn-light rounded-pill px-4">Batal</a>
                    </div>
                </form>

            </div>
        </div>

        {{-- Info Alert --}}
        <div class="alert alert-info border-0 shadow-sm mt-4 rounded-4 small">
            <i class="bi bi-info-circle-fill me-2"></i>
            Demi keamanan, sistem mewajibkan Anda memasukkan password lama setiap kali melakukan perubahan email atau password.
        </div>
    </div>
</div>
@endsection