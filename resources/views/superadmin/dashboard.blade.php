{{-- Mewarisi kerangka utama website dari 'layouts.app' --}}
@extends('layouts.app')

{{-- Mengatur judul tab browser --}}
@section('title', 'Superadmin Dashboard - Dishub System')

{{-- Membuka bagian konten utama --}}
@section('content')

{{-- ================= HEADER DASHBOARD ================= --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0 text-dark">Dashboard Pusat</h4>
        <p class="text-muted mb-0 small">Sistem Informasi Pengujian Kendaraan Bermotor Terpadu</p>
    </div>
    
    {{-- Info Tanggal & User (Disembunyikan di layar HP menggunakan d-none d-md-block) --}}
    <div class="text-end d-none d-md-block">
        {{-- 
          Menggunakan library Carbon bawaan Laravel untuk mendapatkan tanggal hari ini.
          translatedFormat('l, d F Y') akan menghasilkan format bahasa Indonesia (Misal: Senin, 01 Januari 2024).
        --}}
        <div class="fw-bold text-primary">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</div>
        <div class="small text-muted">Superadmin: {{ $admin->nama }}</div>
    </div>
</div>

{{-- ================= METRIK GLOBAL (KARTU STATISTIK) ================= --}}
{{-- 
  Menggunakan Grid System Bootstrap (row g-3). 
  Di layar desktop, setiap kartu akan mengambil 3 dari 12 kolom (col-md-3), sehingga berjajar 4 kartu sejajar.
--}}
<div class="row g-3 mb-4">
    
    {{-- Kartu 1: Total Wilayah --}}
    <div class="col-md-3">
        {{-- Menggunakan inline CSS background: linear-gradient untuk memberikan efek gradasi warna modern --}}
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
    
    {{-- Kartu 2: Total Petugas --}}
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
    
    {{-- Kartu 3: Total Kendaraan --}}
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 text-white p-3" style="background: linear-gradient(45deg, #1D976C 0%, #93F9B9 100%);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1 opacity-75">Total Kendaraan</h6>
                    {{-- number_format() mengubah angka ribuan menjadi format terpisah koma (Misal: 1000 -> 1,000) --}}
                    <h2 class="fw-bold mb-0">{{ number_format($totalKendaraan) }}</h2>
                </div>
                <i class="bi bi-truck fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
    
    {{-- Kartu 4: Sirkulasi RFID --}}
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

{{-- ================= KONTEN BAWAH (GRAFIK & ACCORDION) ================= --}}
<div class="row g-4 mb-5">
    
    {{-- KOLOM KIRI: GRAFIK CHART.JS --}}
    {{-- Mengambil 7 kolom dari 12 di layar besar (col-lg-7) --}}
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="mb-0 fw-bold"><i class="bi bi-bar-chart-line me-2 text-primary"></i>Distribusi Petugas per Wilayah</h6>
            </div>
            <div class="card-body">
                {{-- Elemen Canvas ini akan menjadi tempat Chart.js menggambar grafiknya --}}
                <canvas id="adminChart" height="120"></canvas>
            </div>
        </div>
    </div>

    {{-- KOLOM KANAN: KENDARAAN PER WILAYAH (ACCORDION) --}}
    {{-- Mengambil sisa 5 kolom (col-lg-5) --}}
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="bi bi-truck-front me-2 text-success"></i>Persebaran Kendaraan</h6>
            </div>
            
            {{-- overflow-auto & max-height: Membuat kotak ini bisa di-scroll ke bawah jika isinya terlalu panjang --}}
            <div class="card-body p-3 overflow-auto" style="max-height: 450px;">
                <div class="accordion accordion-flush" id="accordionWilayah">
                    
                    {{-- 
                      Looping data yang sudah dikelompokkan (Grouped By) dari Controller.
                      $wilayah berisi nama Dishub, $vehicles berisi array data kendaraan di wilayah tersebut.
                    --}}
                    @forelse($vehiclesByRegion as $wilayah => $vehicles)
                        @php 
                            // Str::slug() mengubah teks dengan spasi menjadi aman untuk ID HTML 
                            // Contoh: "DKI Jakarta" menjadi "dki-jakarta"
                            $collapseId = 'collapseWilayah' . Str::slug($wilayah); 
                        @endphp
                        
                        <div class="accordion-item border rounded mb-2 shadow-sm">
                            <h2 class="accordion-header" id="heading{{ $collapseId }}">
                                <button class="accordion-button collapsed fw-bold py-2 bg-white text-dark rounded" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}" aria-expanded="false">
                                    <i class="bi bi-geo-alt-fill text-danger me-2"></i> {{ $wilayah }}
                                    
                                    {{-- Menampilkan jumlah total kendaraan di wilayah ini --}}
                                    <span class="badge bg-primary rounded-pill ms-auto shadow-sm">
                                        {{ $vehicles->count() }} Unit
                                    </span>
                                </button>
                            </h2>
                            <div id="{{ $collapseId }}" class="accordion-collapse collapse" data-bs-parent="#accordionWilayah">
                                <div class="accordion-body p-3 bg-light border-top">
                                    
                                    {{-- Jejeran Plat Nomor Kendaraan --}}
                                    <div class="d-flex flex-wrap gap-2">
                                        
                                        {{-- Looping daftar kendaraan yang ada di dalam wilayah spesifik ini --}}
                                        @foreach($vehicles as $v)
                                            {{-- 
                                              Membuat link yang mengarah ke halaman master kendaraan, 
                                              sambil otomatis mengisikan parameter pencarian plat nomor ini.
                                            --}}
                                            <a href="{{ route('vehicles.index', ['search' => $v->no_kendaraan]) }}" class="text-decoration-none" title="Pemilik: {{ $v->user->nama ?? '-' }}">
                                                <span class="badge bg-dark border border-secondary shadow-sm p-2" style="font-size: 0.85rem; letter-spacing: 1px;">
                                                    {{ $v->no_kendaraan }}
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
        // Mengambil referensi dari elemen <canvas> HTML di atas
        const ctx = document.getElementById('adminChart').getContext('2d');
        
        // MENGAMBIL DATA JSON DARI LARAVEL (PHP)
        // Sintaks {!! !!} berfungsi untuk mencetak variabel PHP mentah (raw) tanpa di-escape oleh Blade.
        // Wajib digunakan saat mencetak struktur array/JSON ke dalam script JavaScript.
        const labels = {!! $chartLabels !!};
        const data = {!! $chartData !!};

        // Inisialisasi Chart
        new Chart(ctx, {
            type: 'bar', // Tipe grafik batang vertikal
            data: {
                labels: labels, // Sumbu X (Nama-nama Wilayah)
                datasets: [{
                    label: 'Jumlah Petugas (Admin)',
                    data: data, // Sumbu Y (Jumlah Angka)
                    
                    // Styling batang grafik
                    backgroundColor: 'rgba(13, 110, 253, 0.7)',
                    borderColor: 'rgba(13, 110, 253, 1)',
                    borderWidth: 1,
                    borderRadius: 5, // Membuat ujung batang membulat
                    barPercentage: 0.6 // Mengatur ketebalan batang
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    // Menyembunyikan legenda karena hanya ada 1 dataset
                    legend: { display: false }
                },
                scales: {
                    y: { 
                        beginAtZero: true,
                        // Memaksa sumbu Y menampilkan angka bulat (tidak ada desimal 0.5 orang)
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    });
</script>
@endsection