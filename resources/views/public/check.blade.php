<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Cek Kendaraan - Dishub KIR</title>
    
    {{-- Memuat pustaka eksternal (Bootstrap 5 CSS & Bootstrap Icons) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    {{-- Memuat font Google (Plus Jakarta Sans) --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* ================= PENGATURAN DASAR ================= */
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8f9fa; /* Abu-abu terang */
            min-height: 100vh;
            display: flex;
            flex-direction: column; /* Membantu mendorong footer selalu ke bawah layar */
        }

        /* ================= HERO SECTION (BAGIAN ATAS) ================= */
        /* Menggunakan tema biru gelap Dishub dengan latar belakang pola topografi */
        .hero-bg {
            background-color: #002d72;
            background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMDAwIDEwMDAiPjxnPjxwYXRoIGQ9Ik0wIDEwMDBoMTAwMFYwSDB2MTAwMHpNMCAwaDEwMDB2MTAwMEgwVjB6IiBmaWxsPSIjMDAzMzhkIi8+PHBhdGggZD0iTTEwMCA5MDBsMTAwLTEwMG0xMDAtMTAwbDEwMC0xMDBtMTAwLTEwMGwxMDAtMTAwbDEwMC0xMDBtMTAwLTEwMGwxMDAtMTAwbDEwMC0xMDBtMTAwLTEwMGwxMDAtMTAwIiBmaWxsPSJub25lIiBzdHJva2U9IiMwMDNkOGMiIHN0cm9rZS13aWR0aD0iMC41Ii8+PC9nPjwvc3ZnPg==');
            background-size: cover;
            padding: 80px 0 140px 0; /* Padding bawah yang sangat besar agar Card Pencarian bisa "menimpa" garis batasnya */
            color: white;
            text-align: center;
        }

        /* ================= KOMPONEN KARTU & FORM ================= */
        /* Kotak Pencarian Utama yang letaknya menimpa warna biru dan abu-abu (overlapping layout) */
        .search-card {
            margin-top: -80px; /* Menarik kartu ke atas agar menimpa Hero Section */
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1); /* Bayangan tebal untuk efek melayang */
            position: relative;
            z-index: 10;
        }

        /* Gaya Khusus untuk Kolom Input Ketik/Scan */
        .scanner-input {
            font-size: 1.25rem;
            font-weight: 700;
            letter-spacing: 2px; /* Memberi jarak antar huruf agar jelas saat dibaca */
            text-align: center;
            border: 2px solid #e9ecef;
            border-radius: 16px 0 0 16px !important; /* Membulatkan sudut kiri saja */
            transition: all 0.3s ease;
        }
        .scanner-input:focus {
            box-shadow: none;
            border-color: #002d72; /* Border berubah biru saat diketik */
            background-color: #f8faff;
        }
        .scanner-input::placeholder {
            font-weight: 500;
            letter-spacing: normal; /* Placeholder tidak perlu jarak huruf lebar */
            color: #adb5bd;
            font-size: 1.1rem;
        }

        /* Tombol Cari (Warna Kuning Khas Dishub) */
        .btn-scan {
            background-color: #ffe000;
            color: #002d72;
            font-weight: 800;
            font-size: 1.1rem;
            border: 2px solid #ffe000;
            border-radius: 0 16px 16px 0 !important; /* Membulatkan sudut kanan saja */
            padding: 0 30px;
            transition: all 0.3s ease;
        }
        .btn-scan:hover {
            background-color: #ffd700;
        }

        /* ================= ANIMASI VISUAL ================= */
        /* Animasi "Radar" yang berdenyut (Pulse) untuk menarik perhatian user ke mesin scanner */
        .radar-box {
            width: 90px;
            height: 90px;
            background: rgba(13, 110, 253, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px auto;
            position: relative;
        }
        .radar-box i {
            font-size: 2.5rem;
            color: #0d6efd;
            z-index: 2; /* Ikon berada di atas efek denyut */
        }
        /* Elemen semu (pseudo-element) untuk membuat efek gelombang yang menyebar */
        .radar-box::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: rgba(13, 110, 253, 0.4);
            animation: pulse 1.5s infinite; /* Memanggil animasi berulang (infinite) */
            z-index: 1;
        }
        /* Definisi keyframes animasi (membesar dan memudar) */
        @keyframes pulse {
            0% { transform: scale(1); opacity: 1; }
            100% { transform: scale(1.8); opacity: 0; }
        }
    </style>
</head>
<body>

    {{-- ================= NAVBAR (MELAYANG DI ATAS HERO) ================= --}}
    <nav class="navbar navbar-dark position-absolute w-100 mt-3" style="z-index: 20;">
        <div class="container">
            <a href="{{ route('home') }}" class="btn btn-outline-light rounded-pill px-4 fw-bold shadow-sm">
                <i class="bi bi-arrow-left me-2"></i> Kembali ke Beranda
            </a>
        </div>
    </nav>

    {{-- ================= HERO SECTION ================= --}}
    <div class="hero-bg">
        <div class="container">
            <h1 class="fw-bold mt-4" style="font-size: 2.5rem;">Portal Pengecekan Kendaraan</h1>
            <p class="lead opacity-75 fw-normal">Sistem Informasi Pengujian Kendaraan Berkala (KIR)</p>
        </div>
    </div>

    {{-- ================= KONTEN UTAMA ================= --}}
    {{-- class 'grow' atau properti flex-grow akan memastikan form ini mengisi ruang tengah layar --}}
    <div class="container flex-grow-1">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-7">
                
                {{-- KARTU PENCARIAN (Overlap Layout) --}}
                <div class="card border-0 bg-white search-card p-4 p-md-5">
                    
                    {{-- Ikon Animasi Radar --}}
                    <div class="radar-box">
                        <i class="bi bi-wifi"></i>
                    </div>

                    <div class="text-center mb-4">
                        <h4 class="fw-bold text-dark">Siap Memindai Kartu</h4>
                        <p class="text-muted">Tempelkan kartu RFID pada scanner atau ketik nomor seri secara manual.</p>
                    </div>

                    {{-- 
                      BLOK NOTIFIKASI ERROR (Flash Session)
                      Muncul jika Controller gagal menemukan data dengan keyword yang dicari.
                    --}}
                    @if(session('error'))
                        <div class="alert alert-danger border-0 shadow-sm rounded-3 py-3 fw-bold d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill fs-4 me-3 text-danger"></i> 
                            <div>{{ session('error') }}</div>
                        </div>
                    @endif

                    {{-- BLOK NOTIFIKASI ERROR (Validasi Bawaan) --}}
                    @if($errors->any())
                        <div class="alert alert-danger border-0 shadow-sm rounded-3 py-3 fw-bold d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill fs-4 me-3 text-danger"></i> 
                            <div>{{ $errors->first() }}</div>
                        </div>
                    @endif

                    {{-- 
                      FORM PENCARIAN (Input Scanner)
                      Action: Mengarahkan data GET/POST ke route 'public.search'.
                    --}}
                    <form action="{{ route('public.search') }}" method="POST" id="scanForm">
                        @csrf
                        <div class="input-group input-group-lg mb-2 shadow-sm rounded-4">
                            
                            {{-- 
                              Input Text Utama. 
                              - autocomplete="off" mematikan saran history browser agar tidak menutupi layar.
                              - autofocus mematikan bahwa kursor otomatis berkedip di sini saat halaman dimuat.
                            --}}
                            <input type="text" name="kode" id="kode" class="form-control scanner-input py-3" 
                                   placeholder="Contoh: B 1234 XY atau RFID123" autocomplete="off" autofocus required>
                            
                            <button class="btn btn-scan" type="submit">
                                CEK <i class="bi bi-search ms-1"></i>
                            </button>
                        </div>
                    </form>

                    {{-- 
                      IKON LOADING (Spinner)
                      Awalnya disembunyikan menggunakan class 'd-none'.
                      Akan dimunculkan oleh JavaScript sesaat setelah form disubmit (agar layar tidak terkesan nge-lag/blank).
                    --}}
                    <div class="text-center mt-4">
                        <div class="spinner-border text-primary d-none" id="loadingSpinner" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                </div>

                {{-- INFO BANTUAN --}}
                <div class="text-center mt-5 text-muted small">
                    <div class="d-inline-flex align-items-center bg-white px-3 py-2 rounded-pill shadow-sm mb-2 border">
                        <span class="badge bg-success rounded-pill me-2">Aktif</span>
                        Kursor otomatis terkunci pada kotak input untuk Scanner.
                    </div>
                    <p class="mt-2">Hasil pengecekan adalah dokumen resmi yang sah dan terintegrasi dengan database Dinas Perhubungan.</p>
                </div>

            </div>
        </div>
    </div>

    {{-- ================= FOOTER ================= --}}
    {{-- mt-auto (margin-top: auto) berfungsi mendorong footer ke posisi paling bawah (sticky bottom) berkat flexbox body --}}
    <footer class="bg-white text-center py-4 mt-auto border-top">
        <small class="text-muted fw-bold">© {{ date('Y') }} Dinas Perhubungan. Sistem Informasi Pengujian Kendaraan Terpadu.</small>
    </footer>

    {{-- ================= JAVASCRIPT LOGIKA KIOSK ================= --}}
    <script>
        // Dieksekusi setelah seluruh struktur HTML selesai dimuat browser
        document.addEventListener("DOMContentLoaded", function() {
            
            // Mengambil elemen HTML berdasarkan ID-nya
            const inputField = document.getElementById('kode');
            const form = document.getElementById('scanForm');
            const spinner = document.getElementById('loadingSpinner');

            // 1. Autofocus Darurat (Memastikan input selalu siap menerima ketikan/scanner)
            inputField.focus();

            // 2. Event Listener Form Submit (Animasi Loading)
            form.addEventListener('submit', function() {
                // Menghapus class 'd-none' untuk memunculkan ikon putar loading
                spinner.classList.remove('d-none');
                
                // Mencegah Multiple Submit: Menonaktifkan tombol cari (agar tidak diklik berulang kali)
                form.querySelector('button[type="submit"]').disabled = true;
            });

            // 3. Event Listener Klik Sembarangan (Kiosk Mode Lock)
            // Memaksa kursor kembali ke dalam kotak input jika pengguna tidak sengaja mengeklik 
            // layar kosong (background) atau mengetuk layar sentuh di luar form.
            document.body.addEventListener('click', function(e) {
                // Kondisi: Jika yang diklik BUKAN kotak input ('kode'), BUKAN tombol submit ('BUTTON'), 
                // dan BUKAN link navigasi ('A'), maka kembalikan fokus ke kotak input.
                if(e.target.id !== 'kode' && e.target.tagName !== 'BUTTON' && e.target.tagName !== 'A') {
                    inputField.focus();
                }
            });
        });
    </script>
</body>
</html>