{{-- 
  Mewarisi struktur tampilan utama dari file 'resources/views/layouts/app.blade.php'.
  Semua elemen di dalam file ini akan disisipkan ke layout induk tersebut.
--}}
@extends('layouts.app')

{{-- Membuka bagian/section konten utama --}}
@section('content')

<h2>Dashboard</h2>

{{-- 
  BLOK STATISTIK UMUM
  Menampilkan ringkasan data. 
  Penggunaan sintaks {{ $variabel ?? '-' }} (Null Coalescing Operator) berfungsi sebagai fallback: 
  Jika $total_kendaraan kosong (null) atau tidak terdefinisi di Controller, maka yang ditampilkan adalah tanda strip '-'.
--}}
<div>
    <p>Total Kendaraan: {{ $total_kendaraan ?? '-' }}</p>
    <p>Total Uji: {{ $total_uji }}</p>
    <p>Total User: {{ $total_user ?? '-' }}</p>
</div>

{{-- 
  BLOK STATISTIK KELULUSAN
  Menampilkan jumlah kendaraan yang lolos dan tidak lolos uji.
  Menggunakan inline styling (style="color:...") sederhana untuk membedakan status.
--}}
<div>
    <p style="color:green;">Lolos: {{ $lolos }}</p>
    <p style="color:red;">Tidak Lolos: {{ $tidak_lolos }}</p>
</div>

{{-- 
  ELEMEN CANVAS UNTUK GRAFIK
  Tag <canvas> ini adalah area gambar/kanvas kosong yang nantinya 
  akan digambar (di-render) menjadi grafik oleh library Chart.js menggunakan ID 'chart'.
--}}
<canvas id="chart"></canvas>

{{-- Memuat library Chart.js dari CDN (Content Delivery Network) eksternal --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // --- AREA JAVASCRIPT ---

    // 1. MENGAMBIL DATA DARI DATABASE (PHP) KE JAVASCRIPT
    // Direktif @json() bawaan Laravel ini sangat penting. 
    // Fungsinya untuk mengonversi variabel array/collection PHP ($grafik) 
    // menjadi format JSON (array/objek JavaScript) secara aman.
    const data = @json($grafik);

    // 2. MEMISAHKAN DATA UNTUK GRAFIK
    // Method .map() membuat array baru dengan mengambil data spesifik dari array 'data'
    // 'labels' akan berisi nama sumbu X (contoh: ["Bulan 1", "Bulan 2", dst])
    const labels = data.map(d => 'Bulan ' + d.bulan);
    
    // 'values' akan berisi angka/nilai untuk sumbu Y (contoh: [15, 30, dst])
    const values = data.map(d => d.total);

    // 3. INISIALISASI DAN KONFIGURASI CHART.JS
    // Menggambar grafik pada elemen HTML yang memiliki id 'chart'
    new Chart(document.getElementById('chart'), {
        
        // Menentukan tipe grafik. 'bar' berarti grafik batang (diagram batang).
        // Bisa diganti menjadi 'line' (garis), 'pie' (lingkaran), dll.
        type: 'bar',
        
        data: {
            // Memasukkan array label bulan yang sudah dibuat di atas ke sumbu X
            labels: labels,
            
            datasets: [{ 
                // Memasukkan array nilai total data ke dalam batang grafik
                data: values 
            }]
        }
    });
</script>

{{-- Menutup section konten utama --}}
@endsection