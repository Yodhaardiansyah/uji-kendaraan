<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Petugas - Dishub KIR</title>
    
    {{-- Memuat pustaka CSS Bootstrap 5 dan Bootstrap Icons dari CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    {{-- Memuat font khusus 'Plus Jakarta Sans' dari Google Fonts untuk tampilan yang lebih modern --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- CSS Kustom Internal --}}
    <style>
        /* Mengatur font utama dan background untuk seluruh halaman */
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #002d72;
            
            /* Menggunakan inline SVG (Base64) untuk efek tekstur/pola pada background */
            background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMDAwIDEwMDAiPjxnPjxwYXRoIGQ9Ik0wIDEwMDBoMTAwMFYwSDB2MTAwMHpNMCAwaDEwMDB2MTAwMEgwVjB6IiBmaWxsPSIjMDAzMzhkIi8+PHBhdGggZD0iTTEwMCA5MDBsMTAwLTEwMG0xMDAtMTAwbDEwMC0xMDBtMTAwLTEwMGwxMDAtMTAwbDEwMC0xMDBtMTAwLTEwMGwxMDAtMTAwbDEwMC0xMDBtMTAwLTEwMGwxMDAtMTAwIiBmaWxsPSJub25lIiBzdHJva2U9IiMwMDNkOGMiIHN0cm9rZS13aWR0aD0iMC41Ii8+PC9nPjwvc3ZnPg==');
            background-size: cover;
            min-height: 100vh;
            display: flex; /* Flexbox untuk memusatkan konten secara vertikal dan horizontal */
            align-items: center;
            position: relative;
        }

        /* Styling untuk tombol "Kembali" agar posisinya tetap di pojok kiri atas */
        .btn-back {
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 10;
        }

        /* Desain kotak/kartu form login utama */
        .login-card {
            border-radius: 24px; /* Membuat sudut membulat */
            border: none;
            box-shadow: 0 25px 50px rgba(0,0,0,0.2); /* Efek bayangan dalam (depth) */
            overflow: hidden;
            background-color: #ffffff;
        }

        /* Styling bagian atas/header dalam kartu login */
        .card-header-custom {
            background: rgba(13, 110, 253, 0.05);
            padding: 40px 30px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        /* Desain wadah melingkar untuk ikon logo di header login */
        .icon-circle {
            width: 70px;
            height: 70px;
            background: #e9ecef; 
            color: #002d72;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2rem;
            margin: 0 auto 15px; /* Pusatkan ke tengah horizontal dan beri margin bawah */
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
            border: 2px solid #002d72;
        }

        /* --- Styling Kustom untuk Kolom Input (Input Group) --- */
        /* Kotak ikon di sebelah kiri input */
        .input-group-text {
            background-color: #f8f9fa;
            border-right: none;
            color: #6c757d;
            border-radius: 12px 0 0 12px;
        }
        /* Kolom ketik input utama */
        .form-control {
            background-color: #f8f9fa;
            border-left: none; /* Menghilangkan batas kiri agar menyatu dengan kotak ikon */
            border-radius: 0 12px 12px 0;
            padding: 12px 15px;
        }
        /* Efek saat input aktif/dipilih */
        .form-control:focus {
            background-color: #ffffff;
            box-shadow: none; /* Hilangkan glow bawaan Bootstrap */
            border-color: #dee2e6;
        }
        /* Mengubah warna border keseluruhan input group saat salah satu elemen di dalamnya aktif */
        .input-group:focus-within .input-group-text,
        .input-group:focus-within .form-control {
            background-color: #ffffff;
            border-color: #002d72;
        }

        /* --- Styling Tombol Submit Login --- */
        .btn-login {
            background-color: #002d72;
            color: #ffffff;
            font-weight: 800;
            border-radius: 12px;
            padding: 14px;
            transition: all 0.3s ease; /* Transisi halus untuk efek hover */
            border: none;
        }
        /* Efek saat tombol di-hover */
        .btn-login:hover {
            background-color: #001f4d;
            transform: translateY(-2px); /* Tombol sedikit terangkat */
            box-shadow: 0 10px 20px rgba(0, 45, 114, 0.3);
            color: #ffe000; /* Mengubah warna teks/ikon menjadi kuning */
        }

        /* --- Styling Tautan/Link Pindah Login --- */
        .user-link {
            color: #6c757d;
            font-weight: 600;
            transition: color 0.2s;
        }
        .user-link:hover {
            color: #002d72;
        }
    </style>
</head>
<body>

    {{-- TOMBOL KEMBALI KE BERANDA: Mengarahkan user kembali ke landing page (route 'home') --}}
    <a href="{{ route('home') }}" class="btn btn-outline-light rounded-pill px-4 fw-bold shadow-sm btn-back">
        <i class="bi bi-arrow-left me-2"></i> Kembali
    </a>

    {{-- Container utama form login --}}
    <div class="container">
        {{-- Menggunakan Grid System Bootstrap untuk memusatkan form --}}
        <div class="row justify-content-center">
            {{-- Mengatur lebar form responsif di berbagai ukuran layar (mobile s/d desktop besar) --}}
            <div class="col-11 col-sm-8 col-md-6 col-lg-5 col-xl-4">
                
                {{-- Mulai Kartu Login --}}
                <div class="card login-card">
                    
                    {{-- HEADER KARTU: Berisi ikon, judul, dan subjudul --}}
                    <div class="card-header-custom">
                        <div class="icon-circle">
                            <i class="bi bi-shield-lock-fill"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-1">Portal Petugas</h4>
                        <p class="text-muted small mb-0">Sistem Manajemen Pengujian Kendaraan</p>
                    </div>

                    {{-- BODY KARTU: Berisi pesan error, form input, dan link alternatif --}}
                    <div class="card-body p-4 p-md-5 pt-4">
                        
                        {{-- BLOK ERROR: Notifikasi Kesalahan Kustom --}}
                        {{-- session('error') biasanya dilempar dari Controller jika kredensial tidak cocok --}}
                        @if(session('error'))
                            <div class="alert alert-danger border-0 shadow-sm rounded-3 py-2 small fw-bold d-flex align-items-center mb-4">
                                <i class="bi bi-exclamation-circle-fill fs-5 me-2 text-danger"></i> 
                                <div>{{ session('error') }}</div>
                            </div>
                        @endif

                        {{-- BLOK ERROR: Notifikasi Validasi Bawaan Laravel --}}
                        {{-- Jika ada field input yang gagal melewati rules validasi di Controller --}}
                        @if($errors->any())
                            <div class="alert alert-danger border-0 shadow-sm rounded-3 py-2 small fw-bold d-flex align-items-center mb-4">
                                <i class="bi bi-exclamation-circle-fill fs-5 me-2 text-danger"></i> 
                                <div>Email atau password salah. Silakan coba lagi.</div>
                            </div>
                        @endif

                        {{-- FORM LOGIN ADMIN --}}
                        {{-- Mengirim data method POST ke route 'admin.login' untuk diautentikasi --}}
                        <form action="{{ route('admin.login') }}" method="POST">
                            
                            {{-- Token keamanan CSRF (Wajib) --}}
                            @csrf
                            
                            {{-- Grup Input Email --}}
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary">Email Petugas</label>
                                <div class="input-group shadow-sm rounded-3">
                                    <span class="input-group-text border-end-0"><i class="bi bi-envelope"></i></span>
                                    
                                    {{-- value="{{ old('email') }}" mempertahankan teks jika validasi gagal --}}
                                    <input type="email" name="email" class="form-control border-start-0" placeholder="admin@dishub.go.id" value="{{ old('email') }}" required autofocus>
                                </div>
                            </div>

                            {{-- Grup Input Password --}}
                            <div class="mb-4">
                                <label class="form-label small fw-bold text-secondary">Password</label>
                                <div class="input-group shadow-sm rounded-3">
                                    <span class="input-group-text border-end-0"><i class="bi bi-key"></i></span>
                                    
                                    {{-- Menggunakan type="password" agar ketikan disembunyikan --}}
                                    <input type="password" name="password" class="form-control border-start-0" placeholder="••••••••" required>
                                </div>
                            </div>

                            {{-- Tombol Submit Form --}}
                            <button type="submit" class="btn btn-login w-100 shadow-sm">
                                OTORISASI MASUK <i class="bi bi-arrow-right-circle ms-1"></i>
                            </button>
                        </form>

                        {{-- GARIS PEMISAH VISUAL ("ATAU") --}}
                        <div class="d-flex align-items-center my-4">
                            <hr class="flex-grow-1 opacity-25">
                            <span class="mx-3 text-muted small fw-bold">ATAU</span>
                            <hr class="flex-grow-1 opacity-25">
                        </div>

                        {{-- TAUTAN ALTERNATIF: Pindah ke halaman login untuk User Umum (Pemilik Kendaraan) --}}
                        <div class="text-center">
                            <a href="{{ route('login') }}" class="text-decoration-none user-link small">
                                <i class="bi bi-person-bounding-box me-1"></i> Masuk sebagai Pemilik Kendaraan
                            </a>
                        </div>

                    </div>
                </div>
                
                {{-- FOOTER BAWAH KARTU LOGIN --}}
                <div class="text-center mt-4 text-white-50 small">
                    &copy; {{ date('Y') }} Tim IT Dinas Perhubungan
                </div>

            </div>
        </div>
    </div>

    {{-- Memuat script Bootstrap untuk interaktivitas komponen (jika diperlukan) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>