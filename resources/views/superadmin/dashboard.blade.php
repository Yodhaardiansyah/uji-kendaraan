@extends('layouts.app')
@section('title', 'Superadmin Dashboard - Dishub System')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0 text-dark">Dashboard Pusat</h4>
        <p class="text-muted mb-0 small">Sistem Informasi Pengujian Kendaraan Bermotor Terpadu</p>
    </div>
    <div class="text-end d-none d-md-block">
        <div class="fw-bold text-primary">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</div>
        <div class="small text-muted">Superadmin: {{ $admin->nama }}</div>
    </div>
</div>

{{-- ================= METRIK GLOBAL (Gradient Cards) ================= --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 text-white p-3" style="background: linear-gradient(45deg, #FF512F 0%, #DD2476 100%);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1 opacity-75">Total Wilayah</h6>
                    <h2 class="fw-bold mb-0">{{ $totalDishub }}</h2>
                </div>
                <i class="bi bi-geo-alt fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 text-white p-3" style="background: linear-gradient(45deg, #1A2980 0%, #26D0CE 100%);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1 opacity-75">Total Petugas</h6>
                    <h2 class="fw-bold mb-0">{{ $totalAdmin }}</h2>
                </div>
                <i class="bi bi-shield-lock fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 text-white p-3" style="background: linear-gradient(45deg, #1D976C 0%, #93F9B9 100%);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1 opacity-75">Total Kendaraan</h6>
                    <h2 class="fw-bold mb-0">{{ number_format($totalKendaraan) }}</h2>
                </div>
                <i class="bi bi-truck fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 text-white p-3" style="background: linear-gradient(45deg, #4CB8C4 0%, #3CD3AD 100%);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1 opacity-75">Sirkulasi RFID</h6>
                    <h2 class="fw-bold mb-0">{{ number_format($totalRfid) }}</h2>
                </div>
                <i class="bi bi-credit-card-2-front fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-5">
    
    {{-- ================= KOLOM KIRI: GRAFIK CHART.JS ================= --}}
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="mb-0 fw-bold"><i class="bi bi-bar-chart-line me-2 text-primary"></i>Distribusi Petugas per Wilayah</h6>
            </div>
            <div class="card-body">
                <canvas id="adminChart" height="120"></canvas>
            </div>
        </div>
    </div>

    {{-- ================= KOLOM KANAN: PERSEBARAN KENDARAAN ================= --}}
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="bi bi-truck-front me-2 text-success"></i>Persebaran Kendaraan</h6>
            </div>
            
            <div class="card-body p-3 overflow-auto" style="max-height: 450px;">
                
                <div class="accordion accordion-flush" id="accordionProvinsi">
                    
                    {{-- Looping Langsung dari Variabel yang dikirim Controller --}}
                    @forelse($vehiclesByProvinsi as $provinsi => $dishubsInProvinsi)
                        @php 
                            $collapseIdProvinsi = 'collapseProvinsi' . Str::slug($provinsi); 
                            
                            // Menghitung total kendaraan di seluruh dishub dalam 1 provinsi
                            $totalKendaraanProvinsi = $dishubsInProvinsi->flatten()->count();
                        @endphp
                        
                        <div class="accordion-item border rounded mb-2 shadow-sm">
                            
                            {{-- HEADER PROVINSI --}}
                            <h2 class="accordion-header" id="heading{{ $collapseIdProvinsi }}">
                                <button class="accordion-button collapsed fw-bold py-3 bg-white text-dark rounded" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $collapseIdProvinsi }}" aria-expanded="false">
                                    <i class="bi bi-map-fill text-primary me-2"></i> Provinsi {{ $provinsi }}
                                    
                                    <span class="badge bg-primary rounded-pill ms-auto shadow-sm">
                                        {{ $totalKendaraanProvinsi }} Kendaraan
                                    </span>
                                </button>
                            </h2>
                            
                            {{-- BODY PROVINSI (DAFTAR DISHUB) --}}
                            <div id="{{ $collapseIdProvinsi }}" class="accordion-collapse collapse" data-bs-parent="#accordionProvinsi">
                                <div class="accordion-body p-2 bg-light border-top">
                                    <div class="d-flex flex-column gap-2">
                                        
                                        {{-- Looping Daftar Link Dishub --}}
                                        @foreach($dishubsInProvinsi as $wilayah => $vehicles)
                                            <a href="{{ route('vehicles.index', ['search' => '', 'wilayah' => $wilayah]) }}" 
                                               class="d-flex justify-content-between align-items-center border rounded p-2 bg-white shadow-sm text-decoration-none transition-all"
                                               style="transition: all 0.2s ease; cursor: pointer;"
                                               onmouseover="this.classList.add('bg-light')" 
                                               onmouseout="this.classList.remove('bg-light')">
                                               
                                                <div class="fw-bold text-dark small">
                                                    <i class="bi bi-geo-alt-fill text-danger me-2"></i> {{ $wilayah ?: 'Wilayah Tidak Diketahui' }}
                                                </div>
                                                
                                                <span class="badge bg-success rounded-pill shadow-sm small">
                                                    {{ $vehicles->count() }} Unit
                                                </span>
                                            </a>
                                        @endforeach
                                        
                                    </div>
                                </div>
                            </div>

                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                            Belum ada data kendaraan.
                        </div>
                    @endforelse
                    
                </div>

            </div>
        </div>
    </div>

</div>

{{-- ================= SCRIPT CHART.JS ================= --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('adminChart').getContext('2d');
        
        const labels = {!! $chartLabels !!};
        const data = {!! $chartData !!};

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Petugas (Admin)',
                    data: data,
                    backgroundColor: 'rgba(13, 110, 253, 0.7)',
                    borderColor: 'rgba(13, 110, 253, 1)',
                    borderWidth: 1,
                    borderRadius: 5,
                    barPercentage: 0.6
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { 
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    });
</script>
@endsection