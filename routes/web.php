<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\{AdminAuthController, UserAuthController};
use App\Http\Controllers\{
    DashboardController, 
    UserController, 
    VehicleController, 
    AdminController, 
    DishubController, 
    RfidController, 
    RfidScanController, 
    InspectionController, 
    UserDashboardController,
    ProfileController
};

/*
|--------------------------------------------------------------------------
| 1. PUBLIC & AUTH ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

// AUTH USER (PEMILIK KENDARAAN)
Route::middleware('guest')->group(function () {
    Route::get('/login', [UserAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [UserAuthController::class, 'login']);
});
Route::post('/logout', [UserAuthController::class, 'logout'])->name('logout')->middleware('auth');

// Cetak Sertifikat Uji (Bisa diakses oleh User & Admin)
Route::get('/inspections/{inspection}/print', [InspectionController::class, 'print'])->name('inspections.print');

// AUTH ADMIN & SUPERADMIN
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login']);
    });
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout')->middleware('auth:admin');
});

// CEK KENDARAAN PUBLIC (GUEST)
Route::controller(RfidScanController::class)->group(function () {
    Route::get('/cek-kendaraan', 'index')->name('public.index');
    Route::post('/cek-kendaraan/search', 'search')->name('public.search');
    Route::get('/rfid/check/{kode}', 'show')->name('rfid.public.check');
});

/*
|--------------------------------------------------------------------------
| 2. SHARED ACCESS (BISA DIAKSES USER & ADMIN)
|--------------------------------------------------------------------------
| Di sinilah kunci agar tombol PRINT tidak balik ke LOGIN.
| Kita izinkan guard 'web' (user) dan 'admin' masuk ke sini.
*/
Route::middleware(['auth:web,admin'])->group(function () {
    // Lihat Daftar Riwayat Uji per RFID
    Route::get('/rfid/{rfid}/inspections', [InspectionController::class, 'index'])->name('inspections.index');
    
    // Lihat Detail/Cetak Sertifikat (Ini yang tadi bikin mental ke login)
    Route::get('/inspections/{inspection}', [InspectionController::class, 'show'])->name('inspections.show');

    // Pengaturan Profil (Email & Password)
    Route::get('/settings', [ProfileController::class, 'index'])->name('profile.setting');
    Route::put('/settings', [ProfileController::class, 'update'])->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| 3. USER AREA (PEMILIK KENDARAAN SAJA)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:web')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
});


/*
|--------------------------------------------------------------------------
| 4. ADMIN & SUPERADMIN AREA (INTERNAL SYSTEM)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware(['auth:admin'])->group(function () {
    
    // Dashboard (Logika dipisah di Controller: Superadmin vs Admin)
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
    Route::get('/super-dashboard', [DashboardController::class, 'superadmin'])->name('superadmin.dashboard');

    // Manajemen Core (User, Kendaraan, RFID)
    Route::resource('users', UserController::class);
    Route::resource('vehicles', VehicleController::class);
    Route::resource('rfids', RfidController::class);
    Route::patch('/rfids/{id}/toggle', [RfidController::class, 'toggleStatus'])->name('rfids.toggle');
    Route::post('/rfids/search-redirect', [RfidController::class, 'searchRedirect'])->name('admin.rfids.search_redirect');

    // Inspeksi/Uji (Hanya Admin yang bisa buat & hapus)
    Route::controller(InspectionController::class)->group(function () {
        Route::get('/inspections/create/{rfid}', 'create')->name('inspections.create');
        Route::post('/inspections/store', 'store')->name('inspections.store');
        Route::delete('/inspections/{inspection}', 'destroy')->name('inspections.destroy');
    });

    // Data Wilayah (Read-Only untuk Admin biasa)
    Route::get('/dishubs', [DishubController::class, 'index'])->name('dishubs.index');
    Route::get('/dishubs/{dishub}', [DishubController::class, 'show'])->name('dishubs.show');

    /*
    |--------------------------------------------------------------------------
    | 5. SUPERADMIN ONLY
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:superadmin')->group(function () {
        // Master Data Wilayah (Full CRUD)
        Route::resource('dishubs', DishubController::class)->except(['index', 'show']);
        
        // Manajemen Akun Petugas
        Route::resource('admins', AdminController::class);
        
        // Analisa & Laporan
        Route::get('/analytics', [DashboardController::class, 'analytics'])->name('admin.analytics');
        Route::get('/laporan/pdf', [DashboardController::class, 'laporanPdf'])->name('laporan.pdf');
    });
});