<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    {{-- 
      @yield('title', 'Default Title')
      This allows child views (like the inspection form) to inject their own specific title. 
      If a child view doesn't provide a title, it falls back to 'Dashboard - E-KIR Dishub'.
    --}}
    <title>@yield('title', 'Dashboard - E-KIR Dishub')</title>
    
    {{-- Load Bootstrap 5 Framework and Icons via CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    {{-- Load custom font (Plus Jakarta Sans) --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- ================= GLOBAL STYLES ================= --}}
    <style>
        /* Base typography and background */
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f4f7f6; /* Very light gray to make white cards "pop" out */
        }
        
        /* --- Navbar Container --- */
        .navbar-custom { 
            background-color: #002d72; /* Dishub's signature dark blue */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            padding: 0.8rem 0;
        }
        
        /* Brand/Logo text styling */
        .navbar-custom .navbar-brand { 
            color: #ffffff; 
            font-weight: 800; 
            letter-spacing: 0.5px;
        }
        
        /* --- Navigation Links --- */
        .navbar-custom .nav-link { 
            color: rgba(255, 255, 255, 0.7); /* Slightly transparent white for inactive links */
            font-weight: 500; 
            padding: 0.5rem 1.2rem;
            border-radius: 50px; /* Pill-shaped hover effect */
            transition: all 0.3s ease;
            margin: 0 2px;
        }
        
        /* Hover state for nav links */
        .navbar-custom .nav-link:hover { 
            color: #ffffff; 
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        /* Active state (indicates the current page) */
        .navbar-custom .nav-link.active { 
            color: #002d72 !important; /* Text becomes blue */
            background-color: #ffe000; /* Background becomes Dishub yellow */
            font-weight: 700; 
            box-shadow: 0 4px 10px rgba(255, 224, 0, 0.3);
        }

        /* --- User Profile Dropdown Button --- */
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
        
        /* --- Dropdown Menu Styling --- */
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
        /* Slide effect on hover */
        .dropdown-menu-custom .dropdown-item:hover {
            background-color: #f8f9fa;
            color: #0d6efd;
            transform: translateX(5px); 
        }
        /* Specific hover effect for the Logout button */
        .dropdown-menu-custom .dropdown-item.text-danger:hover {
            background-color: #fff5f5;
            color: #dc3545;
        }

        /* --- Custom Scrollbar for Webkit Browsers (Chrome, Edge, Safari) --- */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }
    </style>
</head>
<body>

    {{-- ================= TOP NAVIGATION BAR ================= --}}
    {{-- sticky-top keeps the navbar fixed at the top when scrolling down --}}
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container-fluid px-4">
            
            {{-- Brand / App Logo --}}
            <a class="navbar-brand d-flex align-items-center me-4" href="#">
                {{-- 
                  onerror attribute: If the logo image file is missing, it gracefully 
                  falls back to showing a Bootstrap truck icon instead of a broken image link.
                --}}
                <img src="{{ asset('images/logo-dishub.png') }}" alt="Logo" height="35" class="me-2" onerror="this.outerHTML='<i class=\'bi bi-truck-front-fill me-2 text-warning\'></i>'">
                E-KIR Dishub
            </a>
            
            {{-- Hamburger menu button for mobile screens --}}
            <button class="navbar-toggler border-0 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="bi bi-list fs-1"></i>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                
                {{-- ================= DYNAMIC MAIN MENU ================= --}}
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    
                    {{-- 
                      ROLE CHECK 1: ADMIN OR SUPERADMIN
                      If the user is authenticated via the 'admin' guard, show these admin tools.
                    --}}
                    @if(Auth::guard('admin')->check())
                        
                        {{-- Dashboard Link: Differentiated by superadmin vs regular admin --}}
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

                        {{-- 
                          Standard Admin Menus 
                          The 'active' class is dynamically applied if the current URL matches the specific route pattern (e.g., 'admin/users*').
                        --}}
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
                        
                        {{-- ROLE CHECK 2: SUPERADMIN ONLY (Manage other admins) --}}
                        @if(Auth::guard('admin')->user()->role == 'superadmin')
                            <li class="nav-item ms-lg-2">
                                <a class="nav-link {{ request()->is('admin/admins*') ? 'active' : '' }}" href="{{ route('admins.index') }}">
                                    <i class="bi bi-shield-check me-1"></i> Petugas
                                </a>
                            </li>
                        @endif

                    {{-- 
                      ROLE CHECK 3: REGULAR USER (Vehicle Owner)
                      If authenticated via the default 'web' guard, show the user dashboard.
                    --}}
                    @elseif(Auth::check())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}" href="{{ route('user.dashboard') }}">
                                <i class="bi bi-house-door me-1"></i> Beranda Saya
                            </a>
                        </li>
                    @endif
                </ul>

                {{-- ================= USER PROFILE & DROPDOWN ================= --}}
                <ul class="navbar-nav ms-auto">
                    {{-- 
                      Determine the active user object and the correct logout route 
                      based on which authentication guard is currently active.
                    --}}
                    @php
                        $activeUser = Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::user();
                        $logoutRoute = Auth::guard('admin')->check() ? route('admin.logout') : route('logout');
                    @endphp

                    {{-- Only render the dropdown if someone is logged in --}}
                    @if($activeUser)
                        <li class="nav-item dropdown">
                            
                            {{-- Dropdown Toggle Button --}}
                            <a class="nav-link dropdown-toggle d-flex align-items-center user-dropdown-btn" href="#" role="button" data-bs-toggle="dropdown">
                                {{-- Generates a dynamic avatar image based on the user's name using the ui-avatars API --}}
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($activeUser->nama) }}&background=ffe000&color=002d72&bold=true" width="35" height="35" class="rounded-circle me-2 shadow-sm">
                                
                                {{-- User Name and Role (Hidden on small mobile screens via d-none d-md-block) --}}
                                <div class="text-start d-none d-md-block me-2">
                                    {{-- Truncates the name to maximum 2 words so it doesn't break the layout --}}
                                    <div class="fw-bold" style="font-size: 0.9rem; line-height: 1.2;">{{ Str::words($activeUser->nama, 2, '') }}</div>
                                    <div class="small text-warning" style="font-size: 0.75rem;">{{ strtoupper($activeUser->role ?? 'PEMILIK') }}</div>
                                </div>
                            </a>
                            
                            {{-- Dropdown Menu Contents --}}
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-custom mt-2">
                                
                                {{-- Mobile-only info: Shows name and role inside the dropdown on small screens --}}
                                <li class="px-3 py-2 mb-2 bg-light rounded-3 mx-2 d-md-none">
                                    <div class="fw-bold text-dark">{{ $activeUser->nama }}</div>
                                    <div class="small text-muted">{{ strtoupper($activeUser->role ?? 'PEMILIK') }}</div>
                                </li>
                                
                                {{-- Profile Settings Link --}}
                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('profile.setting') }}">
                                        <i class="bi bi-gear-fill me-3 text-secondary"></i> Pengaturan Akun
                                    </a>
                                </li>
                                
                                <li><hr class="dropdown-divider opacity-25 my-2"></li>
                                
                                {{-- Logout Action --}}
                                <li>
                                    {{-- Must use a POST form for logout to prevent CSRF attacks --}}
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

    {{-- ================= MAIN CONTENT WRAPPER ================= --}}
    <main class="container-fluid px-4 mt-4 mb-5">
        
        {{-- 
          GLOBAL FLASH MESSAGES
          Catches session messages sent from ANY controller (e.g., return back()->with('success', 'Done!'))
          and displays them universally at the top of the page.
        --}}
        
        {{-- Error Message Alert --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 rounded-4 mb-4 d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i> 
                <div>{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Success Message Alert --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-4 mb-4 d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill fs-4 me-3"></i> 
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- 
          YIELD CONTENT
          This is the placeholder where the actual content of child views (like the forms, tables, dashboards)
          will be injected when they use @section('content').
        --}}
        @yield('content')
        
    </main>

    {{-- Load Bootstrap JS bundle (includes Popper.js for dropdowns and tooltips) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>