<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pemilik Kendaraan - Dishub KIR</title>
    
    {{-- Bootstrap 5 & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    {{-- Google Fonts - Plus Jakarta Sans --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            /* Latar Belakang Topografi Biru Dishub */
            background-color: #002d72;
            background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMDAwIDEwMDAiPjxnPjxwYXRoIGQ9Ik0wIDEwMDBoMTAwMFYwSDB2MTAwMHpNMCAwaDEwMDB2MTAwMEgwVjB6IiBmaWxsPSIjMDAzMzhkIi8+PHBhdGggZD0iTTEwMCA5MDBsMTAwLTEwMG0xMDAtMTAwbDEwMC0xMDBtMTAwLTEwMGwxMDAtMTAwbDEwMC0xMDBtMTAwLTEwMGwxMDAtMTAwbDEwMC0xMDBtMTAwLTEwMGwxMDAtMTAwIiBmaWxsPSJub25lIiBzdHJva2U9IiMwMDNkOGMiIHN0cm9rZS13aWR0aD0iMC41Ii8+PC9nPjwvc3ZnPg==');
            background-size: cover;
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
        }

        /* Tombol Kembali Floating */
        .btn-back {
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 10;
        }

        /* Desain Kartu Login */
        .login-card {
            border-radius: 24px;
            border: none;
            box-shadow: 0 25px 50px rgba(0,0,0,0.2);
            overflow: hidden;
            background-color: #ffffff;
        }

        .card-header-custom {
            background: rgba(13, 110, 253, 0.05);
            padding: 40px 30px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

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

        /* Form Styling */
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
            border-color: #0d6efd;
        }

        /* Tombol Login */
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

        /* Link Admin */
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

    {{-- TOMBOL KEMBALI KE BERANDA --}}
    <a href="{{ route('home') }}" class="btn btn-outline-light rounded-pill px-4 fw-bold shadow-sm btn-back">
        <i class="bi bi-arrow-left me-2"></i> Kembali
    </a>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-11 col-sm-8 col-md-6 col-lg-5 col-xl-4">
                <div class="card login-card">
                    
                    {{-- Header Kartu --}}
                    <div class="card-header-custom">
                        <div class="icon-circle">
                            <i class="bi bi-person-bounding-box"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-1">Area Pemilik</h4>
                        <p class="text-muted small mb-0">Masuk untuk cek riwayat kendaraan Anda</p>
                    </div>

                    <div class="card-body p-4 p-md-5 pt-4">
                        
                        {{-- Menampilkan Error Login --}}
                        @if(session('error'))
                            <div class="alert alert-danger border-0 shadow-sm rounded-3 py-2 small fw-bold d-flex align-items-center mb-4">
                                <i class="bi bi-exclamation-circle-fill fs-5 me-2 text-danger"></i> 
                                <div>{{ session('error') }}</div>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger border-0 shadow-sm rounded-3 py-2 small fw-bold d-flex align-items-center mb-4">
                                <i class="bi bi-exclamation-circle-fill fs-5 me-2 text-danger"></i> 
                                <div>Email atau password salah. Silakan coba lagi.</div>
                            </div>
                        @endif

                        {{-- Form Login --}}
                        <form action="{{ route('login') }}" method="POST">
                            @csrf
                            
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary">Alamat Email</label>
                                <div class="input-group shadow-sm rounded-3">
                                    <span class="input-group-text border-end-0"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" class="form-control border-start-0" placeholder="nama@email.com" value="{{ old('email') }}" required autofocus>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small fw-bold text-secondary">Password</label>
                                <div class="input-group shadow-sm rounded-3">
                                    <span class="input-group-text border-end-0"><i class="bi bi-lock"></i></span>
                                    <input type="password" name="password" class="form-control border-start-0" placeholder="••••••••" required>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-login w-100 shadow-sm">
                                MASUK SEKARANG <i class="bi bi-box-arrow-in-right ms-1"></i>
                            </button>
                        </form>

                        {{-- Pemisah --}}
                        <div class="d-flex align-items-center my-4">
                            <hr class="flex-grow-1 opacity-25">
                            <span class="mx-3 text-muted small fw-bold">ATAU</span>
                            <hr class="flex-grow-1 opacity-25">
                        </div>

                        {{-- Tautan ke Login Admin --}}
                        <div class="text-center">
                            <a href="{{ route('admin.login') }}" class="text-decoration-none admin-link small">
                                <i class="bi bi-shield-lock me-1"></i> Masuk sebagai Petugas
                            </a>
                        </div>

                    </div>
                </div>
                
                {{-- Footer Bawah Login --}}
                <div class="text-center mt-4 text-white-50 small">
                    &copy; {{ date('Y') }} Sistem E-KIR Dishub
                </div>

            </div>
        </div>
    </div>

</body>
</html>