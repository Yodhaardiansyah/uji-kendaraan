<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard - E-KIR Dishub')</title>
    
    {{-- Bootstrap 5 & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    {{-- Google Fonts - Plus Jakarta Sans --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f4f7f6; /* Warna abu-abu sangat muda agar card putih lebih menonjol */
        }
        
        /* --- Tema Navbar Dishub --- */
        .navbar-custom { 
            background-color: #002d72; /* Biru Gelap Khas Dishub */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            padding: 0.8rem 0;
        }
        .navbar-custom .navbar-brand { 
            color: #ffffff; 
            font-weight: 800; 
            letter-spacing: 0.5px;
        }
        
        /* --- Menu Navigasi --- */
        .navbar-custom .nav-link { 
            color: rgba(255, 255, 255, 0.7); 
            font-weight: 500; 
            padding: 0.5rem 1.2rem;
            border-radius: 50px;
            transition: all 0.3s ease;
            margin: 0 2px;
        }
        .navbar-custom .nav-link:hover { 
            color: #ffffff; 
            background-color: rgba(255, 255, 255, 0.1);
        }
        .navbar-custom .nav-link.active { 
            color: #002d72 !important; 
            background-color: #ffe000; /* Sorotan Kuning */
            font-weight: 700; 
            box-shadow: 0 4px 10px rgba(255, 224, 0, 0.3);
        }

        /* --- Tombol Profil Pengguna --- */
        .user-dropdown-btn {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50px;
            padding: 5px 20px 5px 5px !important;
            transition: all 0.3s ease;
            color: white !important;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .user-dropdown-btn:hover {
            background-color: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.3);
        }
        .dropdown-menu-custom {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 10px;
        }
        .dropdown-menu-custom .dropdown-item {
            border-radius: 10px;
            padding: 10px 15px;
            font-weight: 500;
            transition: all 0.2s;
        }
        .dropdown-menu-custom .dropdown-item:hover {
            background-color: #f8f9fa;
            color: #0d6efd;
            transform: translateX(5px);
        }
        .dropdown-menu-custom .dropdown-item.text-danger:hover {
            background-color: #fff5f5;
            color: #dc3545;
        }

        /* --- Custom Scrollbar --- */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }
    </style>
</head>
<body>

    {{-- NAVBAR UTAMA --}}
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container-fluid px-4">
            
            {{-- Logo Brand --}}
            <a class="navbar-brand d-flex align-items-center me-4" href="#">
                {{-- Gunakan img tag di bawah ini jika logo-dishub.png sudah ada --}}
                <img src="{{ asset('images/logo-dishub.png') }}" alt="Logo" height="35" class="me-2" onerror="this.outerHTML='<i class=\'bi bi-truck-front-fill me-2 text-warning\'></i>'">
                E-KIR Dishub
            </a>
            
            <button class="navbar-toggler border-0 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="bi bi-list fs-1"></i>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    
                    {{-- MENU ADMIN & SUPERADMIN --}}
                    @if(Auth::guard('admin')->check())
                        
                        @if(Auth::guard('admin')->user()->role == 'superadmin')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}" href="{{ route('superadmin.dashboard') ?? '#' }}">
                                    <i class="bi bi-speedometer2 me-1"></i> Super Dashboard
                                </a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-house-door me-1"></i> Dashboard
                                </a>
                            </li>
                        @endif

                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                                <i class="bi bi-people me-1"></i> Data User
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/dishubs*') ? 'active' : '' }}" href="{{ route('dishubs.index') }}">
                                <i class="bi bi-geo-alt me-1"></i> Wilayah
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/vehicles*') ? 'active' : '' }}" href="{{ route('vehicles.index') }}">
                                <i class="bi bi-car-front me-1"></i> Kendaraan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/rfids*') ? 'active' : '' }}" href="{{ route('rfids.index') }}">
                                <i class="bi bi-credit-card-2-front me-1"></i> Data RFID
                            </a>
                        </li>
                        
                        {{-- MENU KHUSUS SUPERADMIN --}}
                        @if(Auth::guard('admin')->user()->role == 'superadmin')
                            <li class="nav-item ms-lg-2">
                                <a class="nav-link {{ request()->is('admin/admins*') ? 'active' : '' }}" href="{{ route('admins.index') }}">
                                    <i class="bi bi-shield-check me-1"></i> Petugas
                                </a>
                            </li>
                        @endif

                    {{-- MENU USER BIASA (PEMILIK) --}}
                    @elseif(Auth::check())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}" href="{{ route('user.dashboard') }}">
                                <i class="bi bi-house-door me-1"></i> Beranda Saya
                            </a>
                        </li>
                    @endif
                </ul>

                {{-- USER PROFILE & LOGOUT --}}
                <ul class="navbar-nav ms-auto">
                    @php
                        $activeUser = Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::user();
                        $logoutRoute = Auth::guard('admin')->check() ? route('admin.logout') : route('logout');
                    @endphp

                    @if($activeUser)
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center user-dropdown-btn" href="#" role="button" data-bs-toggle="dropdown">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($activeUser->nama) }}&background=ffe000&color=002d72&bold=true" width="35" height="35" class="rounded-circle me-2 shadow-sm">
                                <div class="text-start d-none d-md-block me-2">
                                    <div class="fw-bold" style="font-size: 0.9rem; line-height: 1.2;">{{ Str::words($activeUser->nama, 2, '') }}</div>
                                    <div class="small text-warning" style="font-size: 0.75rem;">{{ strtoupper($activeUser->role ?? 'PEMILIK') }}</div>
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-custom mt-2">
                                <li class="px-3 py-2 mb-2 bg-light rounded-3 mx-2 d-md-none">
                                    <div class="fw-bold text-dark">{{ $activeUser->nama }}</div>
                                    <div class="small text-muted">{{ strtoupper($activeUser->role ?? 'PEMILIK') }}</div>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('profile.setting') }}">
                                        <i class="bi bi-gear-fill me-3 text-secondary"></i> Pengaturan Akun
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider opacity-25 my-2"></li>
                                <li>
                                    <form action="{{ $logoutRoute }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger d-flex align-items-center fw-bold">
                                            <i class="bi bi-box-arrow-right me-3"></i> Keluar Sistem
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    {{-- KONTEN UTAMA --}}
    <main class="container-fluid px-4 mt-4 mb-5">
        
        {{-- Flash Messages (Pesan Sukses / Error) --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 rounded-4 mb-4 d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i> 
                <div>{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-4 mb-4 d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill fs-4 me-3"></i> 
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Tempat Konten Dari View Lain Berada --}}
        @yield('content')
        
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>