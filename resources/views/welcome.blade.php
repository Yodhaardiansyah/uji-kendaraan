<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dishub KIR - Layanan KIR Pintar Berbasis RFID</title>
    
    {{-- Bootstrap 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    {{-- Google Fonts - Plus Jakarta Sans --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8f9fa;
        }

        /* --- Navbar --- */
        .navbar-custom {
            background-color: transparent   ;
            backdrop-filter: blur(1px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.03);
        }
        .navbar-custom .navbar-brand {
            font-weight: 800;
            color: white;
            font-size: 1.5rem;
        }
        .nav-link{
            color: white;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        .btn-portal {
            background-color: #f1f3f5;
            color: #002d72;
            border-radius: 12px;
            font-weight: 700;
            transition: all 0.3s ease;
        }
        .btn-portal:hover {
            background-color: #e2e6ea;
            color: #001f4d;
        }

        /* --- Hero Section (Menggunakan Gambar Background) --- */
        .hero-section {
            /* Fallback warna jika gambar gagal dimuat */
            background-color: #002d72; 
            
            /* Menggunakan gambar hero-kir.png sebagai background */
            background-image: url('{{ asset('images/hero-kir.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            
            color: white;
            padding: 240px 0 160px;
            position: relative;
            overflow: hidden;
        }

        /* Overlay Gradasi agar teks tetap terbaca jelas */
        .hero-section::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            /* Gradasi: Gelap di kiri (area teks), Transparan di kanan */
            background: linear-gradient(90deg, rgba(0, 45, 114, 0.95) 0%, rgba(0, 45, 114, 0.7) 50%, rgba(0, 45, 114, 0.3) 100%);
            z-index: 1;
        }

        /* Pastikan konten berada di atas overlay */
        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-weight: 800;
            font-size: 3.5rem;
            line-height: 1.2;
            margin-bottom: 20px;
        }
        .hero-description {
            font-weight: 400;
            opacity: 0.9;
            font-size: 1.15rem;
            margin-bottom: 40px;
            max-width: 600px;
        }

        /* --- Form Pencarian Public --- */
        .search-form .input-group {
            background-color: white;
            border-radius: 50px;
            padding: 6px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        .search-form .form-control {
            border: none;
            padding-left: 20px;
            font-size: 1.05rem;
            box-shadow: none;
        }
        .search-form .form-control::placeholder {
            color: #adb5bd;
        }
        .search-form .input-group-text {
            background-color: transparent;
            border: none;
            color: #adb5bd;
            font-size: 1.2rem;
            padding-left: 20px;
        }
        .btn-periksa {
            background-color: #ffe000;
            color: #002d72;
            font-weight: 800;
            border-radius: 50px !important;
            padding: 12px 30px;
            transition: all 0.3s ease;
        }
        .btn-periksa:hover {
            background-color: #ffd700;
            transform: scale(1.02);
        }

        /* --- Tombol Masuk Area Pemilik --- */
        .btn-main-action {
            background-color: transparent;
            color: #ffe000;
            font-weight: 800;
            font-size: 1.2rem;
            border: 2px solid #ffe000;
            border-radius: 50px;
            padding: 16px 45px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }
        .btn-main-action:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(255, 224, 0, 0.2);
            background-color: #ffe000;
            color: #002d72;
        }

        /* --- Garis Pemisah --- */
        .separator-text {
            color: rgba(255, 255, 255, 0.8);
            font-weight: 600;
            font-size: 0.85rem;
            margin: 40px 0 20px 0;
            letter-spacing: 2px;
            display: flex;
            align-items: center;
            justify-content: flex-start;
        }

        /* --- Fitur Section --- */
        .features-section {
            padding: 0 0 80px 0;
            margin-top: -70px;
            position: relative;
            z-index: 5;
        }
        .feature-card {
            background-color: white;
            border: none;
            border-radius: 24px;
            padding: 40px 30px;
            text-align: center;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
            height: 100%;
        }
        .feature-card:hover {
            transform: translateY(-10px);
        }
        .feature-icon-box {
            width: 80px;
            height: 80px;
            background: #f8f9fa;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: #0d6efd;
            margin: 0 auto 25px auto;
        }
        .feature-title {
            font-weight: 800;
            color: #002d72;
            margin-bottom: 15px;
            font-size: 1.2rem;
        }
        .feature-text {
            font-size: 0.95rem;
            color: #6c757d;
            line-height: 1.6;
            margin-bottom: 0;
        }

        @media (max-width: 991px) {
            .hero-title { font-size: 2.5rem; }
            .hero-section { 
                padding: 120px 0 100px; 
                text-align: center; 
            }
            .hero-section::before {
                /* Di layar kecil, gelapkan seluruh area agar teks terbaca di atas gambar truk */
                background: rgba(0, 45, 114, 0.85);
            }
            .search-form { margin: 0 auto; }
            .separator-text { justify-content: center; }
            .btn-main-action { width: 100%; justify-content: center; }
        }
    </style>
</head>
<body class="d-flex flex-column h-100">

    {{-- HEADER/NAVBAR --}}
    <header>
        <nav class="navbar navbar-expand-lg navbar-custom fixed-top py-3">
            <div class="container">
                <<a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                    <img src="{{ asset('images/logo-dishub.png') }}" alt="Logo Dishub" height="40" class="me-2">
                    E-KIR Dishub
                </a>
                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto me-lg-3">
                        <li class="nav-item"><a class="nav-link" href="{{ route('public.index') }}">Cek Kendaraan</a></li>
                        {{-- <li class="nav-item"><a class="nav-link" href="#">Tentang RFID</a></li> --}}
                    </ul>
                    {{-- Tombol Login Petugas (Admin Guard) --}}
                    <a href="{{ route('admin.login') }}" class="btn btn-portal px-4 py-2 mt-2 mt-lg-0">
                        <i class="bi bi-shield-lock-fill me-1"></i> Portal Petugas
                    </a>
                </div>
            </div>
        </nav>
    </header>

    {{-- HERO SECTION --}}
    <main class="flex-shrink-0">
        <section class="hero-section">
            <div class="container hero-content">
                <div class="row align-items-center">
                    
                    {{-- Kolom Kiri (Teks & Form) --}}
                    {{-- Diperlebar sedikit ke col-lg-8 karena ilustrasi kanan sudah dihapus --}}
                    <div class="col-lg-8">
                        <h1 class="hero-title">Layanan KIR Pintar Berbasis RFID</h1>
                        <p class="hero-description">Sistem Terintegrasi untuk Transparansi & Kecepatan Uji Berkala Kendaraan Bermotor Anda.</p>
                        
                        {{-- Form Pencarian Cek Kendaraan (Public Search) --}}
                        <form action="{{ route('public.search') }}" method="POST" class="search-form" style="max-width: 550px;">
                            @csrf
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-search"></i></span>
                                <input type="text" name="kode" class="form-control" placeholder="Masukkan Nomor Plat atau Kode RFID..." required>
                                <button class="btn btn-periksa" type="submit">
                                    PERIKSA <i class="bi bi-arrow-right ms-1"></i>
                                </button>
                            </div>
                            @if(session('error'))
                                <div class="text-warning mt-2 small ms-3 fw-bold"><i class="bi bi-exclamation-circle me-1"></i>{{ session('error') }}</div>
                            @endif
                        </form>

                        <div class="separator-text text-uppercase">Atau Lakukan Pengelolaan Data</div>

                        {{-- Tombol Login User (Pemilik) --}}
                        <a href="{{ route('login') }}" class="btn-main-action">
                            <i class="bi bi-person-bounding-box me-2"></i> MASUK AREA PEMILIK
                        </a>
                    </div>
                    
                    {{-- Kolom Kanan Dihapus --}}
                    {{-- Gambar truk sekarang menjadi background dari seluruh hero-section --}}

                </div>
            </div>
        </section>

        {{-- FITUR SECTION --}}
        <section class="features-section">
            <div class="container">
                <div class="row g-4">
                    {{-- Fitur 1 --}}
                    <div class="col-md-4">
                        <div class="card feature-card">
                            <div class="feature-icon-box bg-primary-subtle text-primary">
                                <i class="bi bi-cpu"></i>
                            </div>
                            <h5 class="feature-title">TEKNOLOGI RFID</h5>
                            <p class="feature-text">Setiap kendaraan dilengkapi identitas digital cerdas yang tahan lama, aman, dan sangat sulit untuk dipalsukan.</p>
                        </div>
                    </div>
                    {{-- Fitur 2 --}}
                    <div class="col-md-4">
                        <div class="card feature-card">
                            <div class="feature-icon-box bg-success-subtle text-success">
                                <i class="bi bi-clock-history"></i>
                            </div>
                            <h5 class="feature-title">RIWAYAT DIGITAL</h5>
                            <p class="feature-text">Seluruh catatan hasil uji berkala tersimpan rapi dan terpusat di dalam database *cloud* secara aman.</p>
                        </div>
                    </div>
                    {{-- Fitur 3 --}}
                    <div class="col-md-4">
                        <div class="card feature-card">
                            <div class="feature-icon-box bg-warning-subtle text-warning">
                                <i class="bi bi-printer"></i>
                            </div>
                            <h5 class="feature-title">CETAK MANDIRI</h5>
                            <p class="feature-text">Kemudahan bagi pemilik kendaraan dan petugas untuk mencetak bukti lulus uji kapan saja dan di mana saja.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    {{-- FOOTER --}}
    <footer class="bg-white py-4 mt-auto border-top">
        <div class="container text-center text-muted small">
            <p class="mb-0 fw-medium">&copy; {{ date('Y') }} Dinas Perhubungan. Sistem Informasi Pengujian Kendaraan Terpadu.</p>
        </div>
    </footer>

    {{-- Bootstrap Bundle JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>