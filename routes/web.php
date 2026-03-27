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
| Berisi route untuk halaman utama, autentikasi user dan admin, 
| serta fitur pengecekan kendaraan publik via RFID.
|--------------------------------------------------------------------------
*/

/**
 * Route untuk halaman utama (Landing Page).
 * Fungsi: Menampilkan view 'welcome' saat user mengakses domain utama website.
 */
Route::get('/', function () {
    return view('welcome');
})->name('home');

/**
 * GROUP: AUTH USER (PEMILIK KENDARAAN)
 * Middleware 'guest': Route di dalam grup ini hanya bisa diakses oleh 
 * pengunjung yang belum login ke dalam sistem.
 */
Route::middleware('guest')->group(function () {

    /**
     * Menampilkan halaman form login untuk User (Pemilik Kendaraan).
     * Method Controller: showLogin() dari UserAuthController.
     */
    Route::get('/login', [UserAuthController::class, 'showLogin'])->name('login');

    /**
     * Memproses data submit login dari User.
     * Method Controller: login() dari UserAuthController.
     */
    Route::post('/login', [UserAuthController::class, 'login']);
});

/**
 * Memproses permintaan logout untuk User.
 * Middleware 'auth': Memastikan hanya user yang sudah login yang bisa melakukan logout.
 * Method Controller: logout() dari UserAuthController.
 */
Route::post('/logout', [UserAuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

/**
 * Mencetak sertifikat hasil uji/inspeksi kendaraan.
 * Route ini dibuat publik/terbuka (atau setidaknya tidak dibatasi di block ini) 
 * agar user, admin, atau pihak terkait bisa mengunduh/mencetak dokumen.
 * Parameter {inspection} merujuk pada ID inspeksi.
 */
Route::get('/inspections/{inspection}/print', [InspectionController::class, 'print'])
    ->name('inspections.print');


/**
 * GROUP: AUTH ADMIN & SUPERADMIN
 * Prefix 'admin': Semua URL di dalamnya akan diawali dengan '/admin'
 * Name 'admin.': Semua nama route di dalamnya akan diawali dengan 'admin.'
 */
Route::prefix('admin')->name('admin.')->group(function () {

    /**
     * Middleware 'guest:admin': Hanya untuk admin yang BELUM login.
     */
    Route::middleware('guest:admin')->group(function () {
        
        /**
         * Menampilkan halaman form login khusus untuk Admin/Petugas.
         */
        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
        
        /**
         * Memproses autentikasi (submit data login) Admin.
         */
        Route::post('/login', [AdminAuthController::class, 'login']);
    });

    /**
     * Memproses permintaan logout khusus untuk sesi Admin.
     * Middleware 'auth:admin': Hanya admin yang sudah login yang bisa mengaksesnya.
     */
    Route::post('/logout', [AdminAuthController::class, 'logout'])
        ->name('logout')
        ->middleware('auth:admin');
});


/**
 * GROUP: CEK KENDARAAN PUBLIC (TANPA LOGIN)
 * Mengelompokkan semua route yang menggunakan RfidScanController.
 * Fungsi: Memfasilitasi masyarakat umum atau petugas lapangan untuk mengecek 
 * status kendaraan hanya dengan scan/input kode RFID tanpa perlu login.
 */
Route::controller(RfidScanController::class)->group(function () {

    /**
     * Menampilkan halaman utama untuk form input/scan RFID umum.
     */
    Route::get('/cek-kendaraan', 'index')->name('public.index');

    /**
     * Memproses pencarian data kendaraan berdasarkan input kode RFID dari publik.
     */
    Route::post('/cek-kendaraan/search', 'search')->name('public.search');

    /**
     * Menampilkan hasil detail scan RFID publik.
     * Parameter {kode} menerima nilai RFID yang berhasil ditemukan.
     */
    Route::get('/rfid/check/{kode}', 'show')->name('rfid.public.check');
});


/*
|--------------------------------------------------------------------------
| 2. SHARED ACCESS (USER & ADMIN)
|--------------------------------------------------------------------------
| Route ini dilindungi oleh middleware multi-guard.
| Artinya, baik User biasa ('web') maupun Admin ('admin') yang sudah login
| dapat mengakses rute-rute di bawah ini tanpa di-redirect kembali ke login.
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:web,admin'])->group(function () {

    /**
     * Menampilkan daftar riwayat inspeksi dari satu kendaraan berdasarkan RFID-nya.
     * Parameter {rfid} merujuk pada kode RFID kendaraan tersebut.
     */
    Route::get('/rfid/{rfid}/inspections', [InspectionController::class, 'index'])
        ->name('inspections.index');
    
    /**
     * Menampilkan detail spesifik dari satu hasil inspeksi (seperti sertifikat digital).
     * Parameter {inspection} merujuk pada ID inspeksi.
     */
    Route::get('/inspections/{inspection}', [InspectionController::class, 'show'])
        ->name('inspections.show');

    /**
     * Menampilkan halaman pengaturan profil untuk akun yang sedang login (bisa user atau admin).
     */
    Route::get('/settings', [ProfileController::class, 'index'])
        ->name('profile.setting');

    /**
     * Memproses pembaruan (update) data profil dari akun yang sedang login.
     * Menggunakan method HTTP PUT.
     */
    Route::put('/settings', [ProfileController::class, 'update'])
        ->name('profile.update');
});


/*
|--------------------------------------------------------------------------
| 3. USER AREA (PEMILIK KENDARAAN)
|--------------------------------------------------------------------------
| Area eksklusif yang hanya bisa dimasuki oleh user biasa (guard: web).
|--------------------------------------------------------------------------
*/
Route::middleware('auth:web')->group(function () {

    /**
     * Menampilkan halaman dashboard utama untuk User (Pemilik Kendaraan).
     * Biasanya berisi ringkasan kendaraan yang dimiliki dan status uji terkininya.
     */
    Route::get('/dashboard', [UserDashboardController::class, 'index'])
        ->name('user.dashboard');
});


/*
|--------------------------------------------------------------------------
| 4. ADMIN & SUPERADMIN AREA
|--------------------------------------------------------------------------
| Area eksklusif untuk pengelola sistem (petugas cabang atau pusat).
| Semua route menggunakan prefix '/admin' dan butuh login admin ('auth:admin').
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware(['auth:admin'])->group(function () {
    
    /**
     * Menampilkan Dashboard untuk Admin biasa (misal: Admin Cabang).
     */
    Route::get('/dashboard', [DashboardController::class, 'admin'])
        ->name('admin.dashboard');

    /**
     * Menampilkan Dashboard tingkat tinggi untuk Superadmin (Pusat).
     * Biasanya memuat data akumulatif seluruh cabang.
     */
    Route::get('/super-dashboard', [DashboardController::class, 'superadmin'])
        ->name('superadmin.dashboard');

    /**
     * GROUP MANAJEMEN DATA INTI (CRUD RESOURCES)
     * Route::resource secara otomatis membuat 7 route standar RESTful:
     * (index, create, store, show, edit, update, destroy).
     */

    // Manajemen master data akun User (Pemilik Kendaraan)
    Route::resource('users', UserController::class);
    
    // Manajemen master data Kendaraan yang terdaftar
    Route::resource('vehicles', VehicleController::class);
    
    // Manajemen pengalokasian/pencatatan kartu/tag RFID
    Route::resource('rfids', RfidController::class);

    /**
     * Mengubah status aktif/non-aktif dari sebuah kartu/tag RFID.
     * Menggunakan method PATCH karena hanya mengubah sebagian kecil data (status).
     */
    Route::patch('/rfids/{id}/toggle', [RfidController::class, 'toggleStatus'])
        ->name('rfids.toggle');

    /**
     * Fitur shortcut di dashboard admin untuk melakukan scan RFID dengan cepat 
     * dan langsung di-redirect ke halaman hasil data kendaraan bersangkutan.
     */
    Route::post('/rfids/search-redirect', [RfidController::class, 'searchRedirect'])
        ->name('admin.rfids.search_redirect');


    /**
     * GROUP: MANAJEMEN INSPEKSI (HANYA ADMIN)
     * Admin bertugas untuk menginput hasil pemeriksaan/uji kendaraan ke sistem.
     */
    Route::controller(InspectionController::class)->group(function () {

        /**
         * Menampilkan form untuk menambahkan data hasil inspeksi baru.
         * Terikat langsung dengan parameter {rfid} kendaraan yang sedang diuji.
         */
        Route::get('/inspections/create/{rfid}', 'create')->name('inspections.create');

        /**
         * Memproses dan menyimpan data hasil uji inspeksi ke database.
         */
        Route::post('/inspections/store', 'store')->name('inspections.store');

        /**
         * Menghapus catatan/data inspeksi dari database (misal karena salah input).
         */
        Route::delete('/inspections/{inspection}', 'destroy')->name('inspections.destroy');
    });

    /**
     * DATA WILAYAH DISHUB (Akses Baca/Read-Only untuk Admin Biasa)
     * Admin biasa hanya bisa melihat daftar dan detail wilayah, tidak bisa mengubah.
     */

    // Melihat daftar wilayah Dishub
    Route::get('/dishubs', [DishubController::class, 'index'])->name('dishubs.index');
    
    // Melihat detail spesifik satu wilayah Dishub
    Route::get('/dishubs/{dishub}', [DishubController::class, 'show'])->name('dishubs.show');


    /*
    |--------------------------------------------------------------------------
    | 5. SUPERADMIN ONLY
    |--------------------------------------------------------------------------
    | Hak akses level tertinggi. Route di bawah ini dilindungi oleh middleware
    | 'role:superadmin', memastikan admin biasa tidak bisa mengubah pengaturan pusat.
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:superadmin')->group(function () {

        /**
         * Fitur CRUD (Create, Read, Update, Delete) untuk master data wilayah Dishub.
         * Kecuali method 'index' dan 'show', karena hak akses read-only 
         * tersebut sudah diberikan di atas untuk admin biasa.
         */
        Route::resource('dishubs', DishubController::class)
            ->except(['index', 'show']);
        
        /**
         * Fitur CRUD (Create, Read, Update, Delete) untuk manajemen akun
         * petugas/admin cabang. Hanya superadmin yang berhak menambah/menghapus petugas.
         */
        Route::resource('admins', AdminController::class);
        
        /**
         * Menampilkan halaman analitik dan statistik tingkat lanjut (pusat).
         */
        Route::get('/analytics', [DashboardController::class, 'analytics'])
            ->name('admin.analytics');

        /**
         * Mengenerate dan mengunduh laporan (report) dalam format PDF 
         * dari data-data inspeksi atau pendapatan wilayah.
         */
        Route::get('/laporan/pdf', [DashboardController::class, 'laporanPdf'])
            ->name('laporan.pdf');
    });
});