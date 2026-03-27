{{-- Mewarisi kerangka utama website dari 'resources/views/layouts/app.blade.php' --}}
@extends('layouts.app')

{{-- Membuka bagian konten utama --}}
@section('content')

{{-- 
  Container utama dengan utilitas Bootstrap:
  text-center: Memusatkan semua teks dan elemen di dalamnya.
  mt-5: Memberikan margin top (jarak atas) agar posisi tidak terlalu menempel ke header.
--}}
<div class="text-center mt-5">
    
    {{-- 
      Menampilkan Kode Status HTTP.
      403 adalah kode standar internet untuk "Forbidden" (Akses Terlarang).
      display-1: Membuat ukuran teks sangat besar (khas Bootstrap).
      text-danger: Memberikan warna merah sebagai indikator peringatan/error.
    --}}
    <h1 class="display-1 fw-bold text-danger">403</h1>
    
    {{-- Pesan Singkat --}}
    <p class="fs-3"> <span class="text-danger">Opps!</span> Akses Ditolak.</p>
    
    {{-- 
      Penjelasan lebih detail.
      class "lead" di Bootstrap membuat paragraf sedikit lebih menonjol/besar 
      dibandingkan teks paragraf standar.
    --}}
    <p class="lead">Anda tidak memiliki izin untuk mengakses halaman ini.</p>
    
    {{-- 
      TOMBOL KEMBALI DINAMIS
      Fungsi url()->previous() adalah helper bawaan Laravel yang sangat cerdas.
      Ia akan secara otomatis membaca riwayat sesi (session history) dan 
      mengarahkan user kembali ke halaman terakhir yang mereka buka sebelum 
      terkena halaman error ini.
    --}}
    <a href="{{ url()->previous() }}" class="btn btn-primary">Kembali</a>
    
</div>
@endsection