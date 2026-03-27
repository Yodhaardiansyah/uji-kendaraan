<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pemilik Kendaraan - Dishub KIR</title>
    
    {{-- Memuat Library Eksternal via CDN (Bootstrap 5 CSS & Icon) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    {{-- Memuat Google Fonts (Plus Jakarta Sans) --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- CSS Kustom Internal untuk Halaman Login User --}}
    <style>
        /* Pengaturan Dasar Body */
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #002d72; /* Warna dasar biru Dishub */
            /* Background pola (pattern) SVG untuk estetika */
            background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMDAwIDEwMDAiPjxnPjxwYXRoIGQ9Ik0wIDEwMDBoMTAwMFYwSDB2MTAwMHpNMCAwaDEwMDB2MTAwMEgwVjB6IiBmaWxsPSIjMDAzMzhkIi8+PHBhdGggZD0iTTEwMCA5MDBsMTAwLTEwMG0xMDAtMTAwbDEwMC0xMDBtMTAwLTEwMGwxMDAtMTAwbDEwMC0xMDBtMTAwLTEwMGwxMDAtMTAwbDEwMC0xMDBtMTAwLTEwMGwxMDAtMTAwIiBmaWxsPSJub25lIiBzdHJva2U9IiMwMDNkOGMiIHN0cm9rZS13aWR0aD0iMC41Ii8+PC9nPjwvc3ZnPg==');
            background-size: cover;
            min-height: 100vh;
            display: flex; /* Flexbox agar konten berada di tengah (vertikal & horizontal) */
            align-items: center;
            position: relative;
        }

        /* Tombol Kembali ke Beranda yang Melayang di Kiri Atas */
        .btn-back {
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 10;
        }

        /* Kotak Utama / Kartu Login */
        .login-card {
            border-radius: 24px;
            border: none;
            box-shadow: 0 25px 50px rgba(0,0,0,0.2);
            overflow: hidden;
            background-color: #ffffff;
        }

        /* Header Kartu Kustom */
        .card-header-custom {
            background: rgba(13, 110, 253, 0.05); /* Sedikit warna biru transparan */
            padding: 40px 30px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        /* --- Perbedaan dengan Login Admin: Styling Ikon Header --- */
        /* Ikon user menggunakan warna biru tua (background) dan kuning (ikon) */
        .icon-circle {
            width: 70px;
            height: 70px;
            background: #002d72;
            color: #ffe000;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 15px;
            box-shadow: 0 10px 20px rgba(0, 45, 114, 0.2);
        }

        /* --- Styling Input Kolom Form --- */
        .input-group-text {
            background-color: #f8f9fa;
            border-right: none;
            color: #6c757d;
            border-radius: 12px 0 0 12px;
        }
        .form-control {
            background-color: #f8f9fa;
            border-left: none;
            border-radius: 0 12px 12px 0;
            padding: 12px 15px;
        }
        .form-control:focus {
            background-color: #ffffff;
            box-shadow: none;
            border-color: #dee2e6;
        }
        .input-group:focus-within .input-group-text,
        .input-group:focus-within .form-control {
            background-color: #ffffff;
            border-color: #0d6efd; /* Highlight warna biru bootstrap standar saat mengetik */
        }

        /* --- Perbedaan dengan Login Admin: Tombol Login --- */
        /* Tombol user menggunakan warna dasar kuning (brand Dishub) */
        .btn-login {
            background-color: #ffe000;
            color: #002d72;
            font-weight: 800;
            border-radius: 12px;
            padding: 14px;
            transition: all 0.3s ease;
            border: none;
        }
        .btn-login:hover {
            background-color: #ffd700;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(255, 224, 0, 0.3);
        }

        /* Styling untuk link pindah ke Login Admin */
        .admin-link {
            color: #6c757d;
            font-weight: 600;
            transition: color 0.2s;
        }
        .admin-link:hover {
            color: #002d72;
        }
    </style>
</head>
<body>

    {{-- TOMBOL KEMBALI: Mengarahkan user kembali ke rute halaman utama/landing page --}}
    <a href="{{ route('home') }}" class="btn btn-outline-light rounded-pill px-4 fw-bold shadow-sm btn-back">
        <i class="bi bi-arrow-left me-2"></i> Kembali
    </a>

    {{-- Kontainer Flexbox/Grid --}}
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-11 col-sm-8 col-md-6 col-lg-5 col-xl-4">
                
                {{-- Mulai Kartu Form Login --}}
                <div class="card login-card">
                    
                    {{-- HEADER KARTU: Menampilkan Logo/Ikon dan Judul Halaman --}}
                    <div class="card-header-custom">
                        <div class="icon-circle">
                            <i class="bi bi-person-bounding-box"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-1">Area Pemilik</h4>
                        <p class="text-muted small mb-0">Masuk untuk cek riwayat kendaraan Anda</p>
                    </div>

                    {{-- BODY KARTU: Berisi pesan notifikasi error dan Form Input --}}
                    <div class="card-body p-4 p-md-5 pt-4">
                        
                        {{-- BLOK NOTIFIKASI ERROR (Flash Session) --}}
                        {{-- Biasanya dikirim menggunakan metode `back()->with('error', 'Pesan')` di Controller --}}
                        @if(session('error'))
                            <div class="alert alert-danger border-0 shadow-sm rounded-3 py-2 small fw-bold d-flex align-items-center mb-4">
                                <i class="bi bi-exclamation-circle-fill fs-5 me-2 text-danger"></i> 
                                <div>{{ session('error') }}</div>
                            </div>
                        @endif

                        {{-- BLOK NOTIFIKASI ERROR (Validasi Bawaan) --}}
                        {{-- Mengindikasi bahwa input user (seperti format email salah) tidak sesuai dengan 'rules' di Controller --}}
                        @if($errors->any())
                            <div class="alert alert-danger border-0 shadow-sm rounded-3 py-2 small fw-bold d-flex align-items-center mb-4">
                                <i class="bi bi-exclamation-circle-fill fs-5 me-2 text-danger"></i> 
                                <div>Email atau password salah. Silakan coba lagi.</div>
                            </div>
                        @endif

                        {{-- FORM LOGIN USER BIASA (PEMILIK KENDARAAN) --}}
                        {{-- Form POST ini diarahkan ke method di UserAuthController yang diatur pada route('login') --}}
                        <form action="{{ route('login') }}" method="POST">
                            
                            {{-- @csrf Token: Wajib disertakan di Laravel untuk memverifikasi keamanan dari form submission --}}
                            @csrf
                            
                            {{-- Input Grup: Alamat Email --}}
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary">Alamat Email</label>
                                <div class="input-group shadow-sm rounded-3">
                                    <span class="input-group-text border-end-0"><i class="bi bi-envelope"></i></span>
                                    
                                    {{-- autofocus: Cursor langsung otomatis berada di kolom ini saat halaman dimuat --}}
                                    <input type="email" name="email" class="form-control border-start-0" placeholder="nama@email.com" value="{{ old('email') }}" required autofocus>
                                </div>
                            </div>

                            {{-- Input Grup: Password --}}
                            <div class="mb-4">
                                <label class="form-label small fw-bold text-secondary">Password</label>
                                <div class="input-group shadow-sm rounded-3">
                                    {{-- Menggunakan ikon gembok sebagai variasi --}}
                                    <span class="input-group-text border-end-0"><i class="bi bi-lock"></i></span>
                                    
                                    <input type="password" name="password" class="form-control border-start-0" placeholder="••••••••" required>
                                </div>
                            </div>

                            {{-- Tombol Eksekusi Form --}}
                            <button type="submit" class="btn btn-login w-100 shadow-sm">
                                MASUK SEKARANG <i class="bi bi-box-arrow-in-right ms-1"></i>
                            </button>
                        </form>

                        {{-- GARIS PEMISAH ("ATAU") --}}
                        <div class="d-flex align-items-center my-4">
                            <hr class="flex-grow-1 opacity-25">
                            <span class="mx-3 text-muted small fw-bold">ATAU</span>
                            <hr class="flex-grow-1 opacity-25">
                        </div>

                        {{-- TAUTAN ALTERNATIF: Akses ke Portal Login Petugas (Admin) --}}
                        <div class="text-center">
                            <a href="{{ route('admin.login') }}" class="text-decoration-none admin-link small">
                                <i class="bi bi-shield-lock me-1"></i> Masuk sebagai Petugas
                            </a>
                        </div>

                    </div>
                </div>
                
                {{-- FOOTER / COPY RIGHT --}}
                <div class="text-center mt-4 text-white-50 small">
                    &copy; {{ date('Y') }} Sistem E-KIR Dishub
                </div>

            </div>
        </div>
    </div>

</body>
</html>