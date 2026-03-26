<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Cek Kendaraan - Dishub KIR</title>
    
    {{-- Bootstrap 5 & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    {{-- Google Fonts - Plus Jakarta Sans --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* --- Latar Belakang Sama Dengan Halaman Awal --- */
        .hero-bg {
            background-color: #002d72;
            background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMDAwIDEwMDAiPjxnPjxwYXRoIGQ9Ik0wIDEwMDBoMTAwMFYwSDB2MTAwMHpNMCAwaDEwMDB2MTAwMEgwVjB6IiBmaWxsPSIjMDAzMzhkIi8+PHBhdGggZD0iTTEwMCA5MDBsMTAwLTEwMG0xMDAtMTAwbDEwMC0xMDBtMTAwLTEwMGwxMDAtMTAwbDEwMC0xMDBtMTAwLTEwMGwxMDAtMTAwbDEwMC0xMDBtMTAwLTEwMGwxMDAtMTAwIiBmaWxsPSJub25lIiBzdHJva2U9IiMwMDNkOGMiIHN0cm9rZS13aWR0aD0iMC41Ii8+PC9nPjwvc3ZnPg==');
            background-size: cover;
            padding: 80px 0 140px 0;
            color: white;
            text-align: center;
        }

        /* --- Kartu Pencarian --- */
        .search-card {
            margin-top: -80px;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            position: relative;
            z-index: 10;
        }

        /* --- Form Input --- */
        .scanner-input {
            font-size: 1.25rem;
            font-weight: 700;
            letter-spacing: 2px;
            text-align: center;
            border: 2px solid #e9ecef;
            border-radius: 16px 0 0 16px !important;
            transition: all 0.3s ease;
        }
        .scanner-input:focus {
            box-shadow: none;
            border-color: #002d72;
            background-color: #f8faff;
        }
        .scanner-input::placeholder {
            font-weight: 500;
            letter-spacing: normal;
            color: #adb5bd;
            font-size: 1.1rem;
        }

        /* --- Tombol Kuning Dishub --- */
        .btn-scan {
            background-color: #ffe000;
            color: #002d72;
            font-weight: 800;
            font-size: 1.1rem;
            border: 2px solid #ffe000;
            border-radius: 0 16px 16px 0 !important;
            padding: 0 30px;
            transition: all 0.3s ease;
        }
        .btn-scan:hover {
            background-color: #ffd700;
        }

        /* --- Animasi Radar/Sinyal --- */
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
            z-index: 2;
        }
        .radar-box::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: rgba(13, 110, 253, 0.4);
            animation: pulse 1.5s infinite;
            z-index: 1;
        }
        @keyframes pulse {
            0% { transform: scale(1); opacity: 1; }
            100% { transform: scale(1.8); opacity: 0; }
        }
    </style>
</head>
<body>

    {{-- NAVBAR KEMBALI --}}
    <nav class="navbar navbar-dark position-absolute w-100 mt-3" style="z-index: 20;">
        <div class="container">
            <a href="{{ route('home') }}" class="btn btn-outline-light rounded-pill px-4 fw-bold shadow-sm">
                <i class="bi bi-arrow-left me-2"></i> Kembali ke Beranda
            </a>
        </div>
    </nav>

    {{-- HEADER / HERO SECTION --}}
    <div class="hero-bg">
        <div class="container">
            <h1 class="fw-bold mt-4" style="font-size: 2.5rem;">Portal Pengecekan Kendaraan</h1>
            <p class="lead opacity-75 fw-normal">Sistem Informasi Pengujian Kendaraan Berkala (KIR)</p>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="container grow">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-7">
                
                {{-- KARTU PENCARIAN --}}
                <div class="card border-0 bg-white search-card p-4 p-md-5">
                    
                    {{-- Animasi Radar --}}
                    <div class="radar-box">
                        <i class="bi bi-wifi"></i>
                    </div>

                    <div class="text-center mb-4">
                        <h4 class="fw-bold text-dark">Siap Memindai Kartu</h4>
                        <p class="text-muted">Tempelkan kartu RFID pada *scanner* atau ketik nomor seri secara manual.</p>
                    </div>

                    {{-- Menampilkan Error Jika Gagal --}}
                    @if(session('error'))
                        <div class="alert alert-danger border-0 shadow-sm rounded-3 py-3 fw-bold d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill fs-4 me-3 text-danger"></i> 
                            <div>{{ session('error') }}</div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger border-0 shadow-sm rounded-3 py-3 fw-bold d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill fs-4 me-3 text-danger"></i> 
                            <div>{{ $errors->first() }}</div>
                        </div>
                    @endif

                    {{-- FORM INPUT --}}
                    <form action="{{ route('public.search') }}" method="POST" id="scanForm">
                        @csrf
                        <div class="input-group input-group-lg mb-2 shadow-sm rounded-4">
                            <input type="text" name="kode" id="kode" class="form-control scanner-input py-3" 
                                   placeholder="Contoh: B 1234 XY atau RFID123" autocomplete="off" autofocus required>
                            
                            <button class="btn btn-scan" type="submit">
                                CEK <i class="bi bi-search ms-1"></i>
                            </button>
                        </div>
                    </form>

                    {{-- Loading Spinner --}}
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

    {{-- FOOTER --}}
    <footer class="bg-white text-center py-4 mt-auto border-top">
        <small class="text-muted fw-bold">© {{ date('Y') }} Dinas Perhubungan. Sistem Informasi Pengujian Kendaraan Terpadu.</small>
    </footer>

    {{-- SCRIPT AGAR SCANNER LANGSUNG SUBMIT --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const inputField = document.getElementById('kode');
            const form = document.getElementById('scanForm');
            const spinner = document.getElementById('loadingSpinner');

            // Pastikan input selalu fokus saat halaman dibuka agar siap di-scan
            inputField.focus();

            // Tampilkan animasi loading saat form dikirim
            form.addEventListener('submit', function() {
                spinner.classList.remove('d-none');
                
                // Opsional: Matikan tombol agar tidak di-submit dua kali
                form.querySelector('button[type="submit"]').disabled = true;
            });

            // Paksa kursor kembali ke input jika user tidak sengaja klik di luar layar
            // (Sangat berguna jika layar ini dijadikan Kiosk/Layar Sentuh publik)
            document.body.addEventListener('click', function(e) {
                if(e.target.id !== 'kode' && e.target.tagName !== 'BUTTON' && e.target.tagName !== 'A') {
                    inputField.focus();
                }
            });
        });
    </script>
</body>
</html>