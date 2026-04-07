<!DOCTYPE html>
<html lang="id">
<head>
    <!-- 
        META DASAR HTML
        - charset UTF-8 → agar mendukung semua karakter
        - viewport → agar responsive di HP
    -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Judul halaman (muncul di tab browser) -->
    <title>Dishub KIR - Layanan KIR Pintar Berbasis RFID</title>
    
    <!-- 
        IMPORT CSS FRAMEWORK & ICON
        - Bootstrap → untuk layout & komponen UI
        - Bootstrap Icons → untuk icon seperti search, user, dll
    -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- 
        GOOGLE FONT
        - Menggunakan font modern agar tampilan lebih profesional
    -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* 
            GLOBAL STYLE
            - Set font utama dan background dasar
        */
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8f9fa;
        }

        /* ================= NAVBAR ================= */

        /* Navbar transparan dengan efek blur */
        .navbar-custom {
            background-color: transparent;
            backdrop-filter: blur(1px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.03);
        }

        /* Logo/navbar brand */
        .navbar-custom .navbar-brand {
            font-weight: 800;
            color: white;
            font-size: 1.5rem;
        }

        /* Link navbar */
        .nav-link{
            color: white;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        /* Tombol portal admin */
        .btn-portal {
            background-color: #f1f3f5;
            color: #002d72;
            border-radius: 12px;
            font-weight: 700;
        }

        /* ================= HERO SECTION ================= */

        /*
            Hero utama dengan background gambar
            - Menggunakan gambar dari folder public/images
        */
        .hero-section {
            background-color: #002d72; 
            background-image: url('{{ asset('images/hero-kir.png') }}');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 240px 0 160px;
            position: relative;
        }

        /*
            Overlay agar teks tetap terbaca di atas gambar
        */
        .hero-section::before {
            content: "";
            position: absolute;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                90deg, 
                rgba(0, 45, 114, 0.95) 0%, 
                rgba(0, 45, 114, 0.7) 50%, 
                rgba(0, 45, 114, 0.3) 100%
            );
        }

        /* Konten di atas overlay */
        .hero-content {
            position: relative;
            z-index: 2;
        }

        /* Judul utama */
        .hero-title {
            font-weight: 800;
            font-size: 3.5rem;
        }

        /* Deskripsi */
        .hero-description {
            font-size: 1.15rem;
            margin-bottom: 40px;
        }

        /* ================= FORM SEARCH ================= */

        /* Wrapper input */
        .search-form .input-group {
            background-color: white;
            border-radius: 50px;
            padding: 6px;
        }

        /* Input */
        .search-form .form-control {
            border: none;
            padding-left: 20px;
        }

        /* Tombol submit */
        .btn-periksa {
            background-color: #ffe000;
            color: #002d72;
            font-weight: 800;
            border-radius: 50px !important;
        }

        /* ================= BUTTON USER ================= */

        /* Tombol utama (CTA) */
        .btn-main-action {
            background: linear-gradient(135deg, #ffe000, #ffd000);
            color: #002d72;
            border: none;
            border-radius: 50px;
            padding: 14px 40px;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 10px 25px rgba(255, 224, 0, 0.3);
            transition: all 0.3s ease;
        }

        /* Hover effect */
        .btn-main-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(255, 224, 0, 0.4);
            color: #002d72;
        }
        /* ================= FEATURES ================= */

        /* Section fitur */
        .features-section {
            margin-top: -70px;
        }

        /* Card fitur */
        .feature-card {
            border-radius: 24px;
            padding: 40px;
        }

        /* Responsive */
        @media (max-width: 991px) {
            .hero-title { font-size: 2.5rem; }
        }
    </style>
</head>

<body class="d-flex flex-column h-100">

    <!-- ================= HEADER / NAVBAR ================= -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-custom fixed-top py-3">
            <div class="container">

                <!-- 
                    LOGO + NAMA WEBSITE
                    PERBAIKAN: sebelumnya ada typo <<a
                -->
                <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                    <img src="{{ asset('images/logo-dishub.png') }}" height="40" class="me-2">
                    E-KIR Dishub
                </a>

                <!-- Tombol hamburger (mobile) -->
                <button class="navbar-toggler border-0" data-bs-toggle="collapse" data-bs-target="#navbarNav"></button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    
                    <!-- Menu kanan -->
                    <ul class="navbar-nav ms-auto me-lg-3">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('public.index') }}">
                                Cek Kendaraan
                            </a>
                        </li>
                    </ul>

                    <!-- Tombol login admin -->
                    <a href="{{ route('admin.login') }}" class="btn btn-portal px-4 py-2">
                        <i class="bi bi-shield-lock-fill"></i> Portal Petugas
                    </a>
                </div>
            </div>
        </nav>
    </header>

    <!-- ================= HERO ================= -->
    <main class="flex-shrink-0">
        <section class="hero-section">
            <div class="container hero-content">
                <div class="row align-items-center">
                    
                    <div class="col-lg-8">

                        <!-- Judul -->
                        <h1 class="hero-title">
                            Layanan KIR Pintar Berbasis RFID
                        </h1>

                        <!-- Deskripsi -->
                        <p class="hero-description">
                            Sistem Terintegrasi untuk Transparansi & Kecepatan Uji Kendaraan.
                        </p>

                        <!-- ================= FORM CEK ================= -->
                        <form action="{{ route('public.search') }}" method="POST" class="search-form">
                            @csrf
                            <div class="input-group">

                                <!-- Icon search -->
                                <span class="input-group-text">
                                    <i class="bi bi-search"></i>
                                </span>

                                <!-- Input kode -->
                                <input type="text" name="kode" class="form-control"
                                    placeholder="Masukkan Plat / RFID..." required>

                                <!-- Submit -->
                                <button class="btn btn-periksa" type="submit">
                                    PERIKSA
                                </button>
                            </div>

                            <!-- Error message -->
                            @if(session('error'))
                                <div class="text-warning mt-2">
                                    {{ session('error') }}
                                </div>
                            @endif
                        </form>

                        <!-- Separator -->
                        <div class="mt-4 text-white">
                            Atau Kelola Data
                        </div>

                        <!-- Tombol login user -->
                        <a href="{{ route('login') }}" class="btn-main-action mt-3">
                            MASUK AREA PEMILIK
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- ================= FITUR ================= -->
        <section class="features-section">
            <div class="container">
                <div class="row g-4">

                    <!-- Fitur 1 -->
                    <div class="col-md-4">
                        <div class="card feature-card text-center">
                            <i class="bi bi-cpu fs-1"></i>
                            <h5>Teknologi RFID</h5>
                            <p>Identitas digital kendaraan yang aman.</p>
                        </div>
                    </div>

                    <!-- Fitur 2 -->
                    <div class="col-md-4">
                        <div class="card feature-card text-center">
                            <i class="bi bi-clock-history fs-1"></i>
                            <h5>Riwayat Digital</h5>
                            <p>Semua data uji tersimpan di cloud.</p>
                        </div>
                    </div>

                    <!-- Fitur 3 -->
                    <div class="col-md-4">
                        <div class="card feature-card text-center">
                            <i class="bi bi-printer fs-1"></i>
                            <h5>Cetak Mandiri</h5>
                            <p>Bisa cetak kapan saja.</p>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </main>

    <!-- ================= FOOTER ================= -->
    <footer class="bg-white py-4 mt-auto border-top text-center">
        <small>
            &copy; {{ date('Y') }} Dinas Perhubungan
        </small>
    </footer>

    <!-- JS Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>