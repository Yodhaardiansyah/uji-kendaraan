@extends('layouts.app')

@section('content')
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-people me-2"></i>Manajemen User</h5>
        <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm shadow-sm">
            <i class="bi bi-person-plus me-1"></i> Tambah User
        </a>
    </div>
    
    <div class="card-body">
        
        {{-- ================= FORM PENCARIAN ================= --}}
        <form action="{{ route('users.index') }}" method="GET" class="mb-4">
            <div class="input-group shadow-sm">
                <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari Nama, Email, NIK, atau Alamat..." value="{{ request('search') }}">
                <button class="btn btn-secondary px-4 fw-bold" type="submit">Cari</button>
                @if(request('search'))
                    <a href="{{ route('users.index') }}" class="btn btn-outline-danger" title="Reset Pencarian">
                        <i class="bi bi-x-circle"></i>
                    </a>
                @endif
            </div>
        </form>

        {{-- ================= TABEL USER ================= --}}
        <div class="table-responsive border rounded">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Nama</th>
                        <th>Email</th>
                        <th>No. Identitas</th>
                        <th>Alamat</th>
                        <th class="text-center pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td class="fw-bold ps-3">{{ $user->nama }}</td>
                        <td>{{ $user->email }}</td>
                        <td><span class="badge bg-light text-dark border">{{ $user->nomor_identitas }}</span></td>
                        <td><small class="text-muted">{{ Str::limit($user->alamat, 40) }}</small></td>
                        <td class="text-center pe-3 text-nowrap">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm text-dark shadow-sm" title="Edit User">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline m-0 p-0" onsubmit="return confirm('Hapus user ini secara permanen?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm shadow-sm" title="Hapus User">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-search fs-1 d-block mb-3"></i>
                            @if(request('search'))
                                User dengan kata kunci "<b>{{ request('search') }}</b>" tidak ditemukan.
                            @else
                                Belum ada data user yang terdaftar di sistem.
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ================= PAGINATION ================= --}}
<div class="d-flex justify-content-end mb-4">
    {{-- Tambahkan 'pagination::bootstrap-5' dan withQueryString() agar pencarian tidak hilang saat pindah halaman --}}
    {{ $users->withQueryString()->links('pagination::bootstrap-5') }}
</div>
@endsection