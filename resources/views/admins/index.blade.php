@extends('layouts.app')
@section('title', 'Manajemen Admin - Dishub System')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0 text-primary"><i class="bi bi-shield-lock me-2"></i>Manajemen Admin & Superadmin</h4>
    <a href="{{ route('admins.create') }}" class="btn btn-primary shadow-sm">
        <i class="bi bi-person-plus-fill me-1"></i> Tambah Admin
    </a>
</div>

{{-- ================= FORM PENCARIAN ================= --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form action="{{ route('admins.index') }}" method="GET" class="d-flex gap-2">
            <div class="input-group shadow-sm">
                <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari Nama Admin, NRP, Email, atau Cabang Dishub..." value="{{ request('search') }}">
            </div>
            <button type="submit" class="btn btn-secondary px-4 shadow-sm fw-bold">Cari</button>
            
            @if(request('search'))
                <a href="{{ route('admins.index') }}" class="btn btn-outline-danger shadow-sm"><i class="bi bi-x-circle"></i> Reset</a>
            @endif
        </form>
    </div>
</div>

{{-- ================= DAFTAR ADMIN (GROUPED BY DISHUB) ================= --}}
<div class="accordion shadow-sm" id="accordionAdmins">
    @forelse($dishubs as $dishub)
        @php 
            $branchAdmins = $dishub->admins; 
            $collapseId = 'collapseDishub' . $dishub->id;
        @endphp

        <div class="accordion-item border-0 mb-3 rounded-3 overflow-hidden">
            <h2 class="accordion-header" id="heading{{ $collapseId }}">
                <button class="accordion-button collapsed bg-white fw-bold text-dark py-3" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}" aria-expanded="false">
                    <div class="d-flex align-items-center w-100 pe-3">
                        <div class="bg-light p-2 rounded-circle me-3">
                            <i class="bi bi-building fs-5 text-primary"></i>
                        </div>
                        <div>
                            <div class="fs-6">{{ $dishub->nama ?? 'Dishub Tidak Diketahui' }}</div>
                            <small class="text-muted fw-normal"><i class="bi bi-geo-alt me-1"></i>{{ $dishub->singkatan ?? '-' }}</small>
                        </div>
                        <div class="ms-auto d-flex gap-2 align-items-center">
                            {{-- Lencana Indikator Jumlah Admin --}}
                            @php $superCount = $branchAdmins->where('role', 'superadmin')->count(); @endphp
                            @if($superCount > 0)
                                <span class="badge bg-danger rounded-pill px-3 py-2 shadow-sm">{{ $superCount }} Superadmin</span>
                            @endif
                            <span class="badge bg-primary rounded-pill px-3 py-2 shadow-sm">{{ $branchAdmins->count() }} Total Admin</span>
                        </div>
                    </div>
                </button>
            </h2>
            
            <div id="{{ $collapseId }}" class="accordion-collapse collapse" data-bs-parent="#accordionAdmins">
                <div class="accordion-body p-0 border-top border-light">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4 py-3" style="width: 30%;">Nama / NRP</th>
                                    <th style="width: 25%;">Email</th>
                                    <th style="width: 20%;">Pangkat</th>
                                    <th style="width: 10%;">Role</th>
                                    <th class="text-center" style="width: 15%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($branchAdmins as $admin)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold text-dark">{{ $admin->nama }}</div>
                                            <small class="text-muted">NRP: {{ $admin->nrp ?? '-' }}</small>
                                        </td>
                                        <td>{{ $admin->email }}</td>
                                        <td><small class="text-muted fw-bold">{{ $admin->pangkat ?? '-' }}</small></td>
                                        <td>
                                            <span class="badge {{ $admin->role == 'superadmin' ? 'bg-danger' : 'bg-success' }} shadow-sm">
                                                {{ strtoupper($admin->role) }}
                                            </span>
                                        </td>
                                        <td class="text-center text-nowrap">
                                            <div class="d-flex justify-content-center align-items-center gap-1">
                                                <a href="{{ route('admins.edit', $admin->id) }}" class="btn btn-warning btn-sm text-dark shadow-sm" title="Edit Data">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <form action="{{ route('admins.destroy', $admin->id) }}" method="POST" class="m-0 p-0 d-flex" onsubmit="return confirm('Yakin ingin menghapus admin ini secara permanen?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm shadow-sm" title="Hapus Akun">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5 text-muted">
                <i class="bi bi-search fs-1 d-block mb-3"></i>
                @if(request('search'))
                    Data cabang atau admin dengan kata kunci "<b>{{ request('search') }}</b>" tidak ditemukan.
                @else
                    Belum ada data admin cabang lain yang terdaftar di sistem.
                @endif
            </div>
        </div>
    @endforelse
</div>

{{-- ================= PAGINATION ================= --}}
<div class="d-flex justify-content-end mb-4 mt-3">
    {{ $dishubs->links() }}
</div>
@endsection