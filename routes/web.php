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
| Bagian ini berisi route yang dapat diakses tanpa autentikasi (guest),
| termasuk halaman utama, login user, dan fitur publik lainnya.
|--------------------------------------------------------------------------
*/

/**
 * Halaman utama (landing page)
 */
Route::get('/', fn() => view('welcome'))->name('home');

/**
 * AUTH USER (PEMILIK KENDARAAN)
 * Hanya dapat diakses oleh user yang belum login (guest)
 */
Route::middleware('guest')->group(function () {
    // Menampilkan form login user
    Route::get('/login', [UserAuthController::class, 'showLogin'])->name('login');

    // Proses autentikasi login user
    Route::post('/login', [UserAuthController::class, 'login']);
});

/**
 * Logout user (hanya untuk user yang sudah login)
 */
Route::post('/logout', [UserAuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/**
 * Cetak hasil inspeksi kendaraan (akses publik)
 */
Route::get('/inspections/{inspection}/print', [InspectionController::class, 'print'])
    ->name('inspections.print');


/*
|--------------------------------------------------------------------------
| 2. ADMIN AUTH ROUTES
|--------------------------------------------------------------------------
| Route khusus autentikasi admin dan superadmin.
| Menggunakan guard 'admin'.
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {

    /**
     * Login admin (hanya untuk admin yang belum login)
     */
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login']);
    });

    /**
     * Logout admin
     */
    Route::post('/logout', [AdminAuthController::class, 'logout'])
        ->middleware('auth:admin')
        ->name('logout');
});


/*
|--------------------------------------------------------------------------
| 3. PUBLIC RFID CHECK
|--------------------------------------------------------------------------
| Fitur publik untuk mengecek status kendaraan menggunakan RFID
| tanpa perlu login.
|--------------------------------------------------------------------------
*/

Route::controller(RfidScanController::class)->group(function () {

    // Halaman input RFID
    Route::get('/cek-kendaraan', 'index')->name('public.index');

    // Proses pencarian RFID
    Route::post('/cek-kendaraan/search', 'search')->name('public.search');

    // Menampilkan hasil scan RFID
    Route::get('/rfid/check/{kode}', 'show')->name('rfid.public.check');
});


/*
|--------------------------------------------------------------------------
| 4. SHARED ACCESS (USER & ADMIN)
|--------------------------------------------------------------------------
| Route yang dapat diakses oleh user maupun admin setelah login.
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:web,admin'])->group(function () {

    // Daftar riwayat inspeksi berdasarkan RFID
    Route::get('/rfid/{rfid}/inspections', [InspectionController::class, 'index'])
        ->name('inspections.index');

    // Detail inspeksi kendaraan
    Route::get('/inspections/{inspection}', [InspectionController::class, 'show'])
        ->name('inspections.show');

    // Halaman pengaturan profil
    Route::get('/settings', [ProfileController::class, 'index'])
        ->name('profile.setting');

    // Update profil
    Route::put('/settings', [ProfileController::class, 'update'])
        ->name('profile.update');
});


/*
|--------------------------------------------------------------------------
| 5. USER AREA
|--------------------------------------------------------------------------
| Area khusus user (pemilik kendaraan)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:web')->group(function () {

    // Dashboard user
    Route::get('/dashboard', [UserDashboardController::class, 'index'])
        ->name('user.dashboard');
});


/*
|--------------------------------------------------------------------------
| 6. ADMIN & SUPERADMIN AREA
|--------------------------------------------------------------------------
| Area khusus admin dan superadmin dengan prefix '/admin'
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->middleware('auth:admin')->group(function () {

    /**
     * Dashboard admin dan superadmin
     */
    Route::get('/dashboard', [DashboardController::class, 'admin'])
        ->name('admin.dashboard');

    Route::get('/super-dashboard', [DashboardController::class, 'superadmin'])
        ->name('superadmin.dashboard');

    /**
     * MASTER DATA (CRUD)
     */
    Route::resource('users', UserController::class);
    Route::resource('vehicles', VehicleController::class);
    Route::resource('rfids', RfidController::class);

    /**
     * Toggle status RFID (aktif/nonaktif)
     */
    Route::patch('/rfids/{id}/toggle', [RfidController::class, 'toggleStatus'])
        ->name('rfids.toggle');

    /**
     * Redirect hasil pencarian RFID dari dashboard admin
     */
    Route::post('/rfids/search-redirect', [RfidController::class, 'searchRedirect'])
        ->name('admin.rfids.search_redirect');


    /*
    |--------------------------------------------------------------------------
    | INSPECTIONS MANAGEMENT
    |--------------------------------------------------------------------------
    | Digunakan oleh admin untuk input dan pengelolaan data inspeksi
    |--------------------------------------------------------------------------
    */

    Route::controller(InspectionController::class)->group(function () {
        Route::get('/inspections/create/{rfid}', 'create')->name('inspections.create');
        Route::post('/inspections/store', 'store')->name('inspections.store');
        Route::delete('/inspections/{inspection}', 'destroy')->name('inspections.destroy');
    });


    /*
    |--------------------------------------------------------------------------
    | DISHUB MANAGEMENT
    |--------------------------------------------------------------------------
    | Pengelolaan wilayah Dishub
    | - Admin: hanya bisa melihat (read)
    | - Superadmin: bisa CRUD penuh
    |--------------------------------------------------------------------------
    */

    /**
     * READ ONLY (SEMUA ADMIN)
     */
    Route::get('/dishubs', [DishubController::class, 'index'])
        ->name('dishubs.index');

    Route::get('/dishubs/{dishub}', [DishubController::class, 'show'])
        ->where('dishub', '[0-9]+') // mencegah konflik dengan 'create'
        ->name('dishubs.show');


    /**
     * FULL ACCESS (SUPERADMIN ONLY)
     */
    Route::middleware('role:superadmin')->group(function () {

        Route::get('/dishubs/create', [DishubController::class, 'create'])
            ->name('dishubs.create');

        Route::post('/dishubs', [DishubController::class, 'store'])
            ->name('dishubs.store');

        Route::get('/dishubs/{dishub}/edit', [DishubController::class, 'edit'])
            ->where('dishub', '[0-9]+')
            ->name('dishubs.edit');

        Route::put('/dishubs/{dishub}', [DishubController::class, 'update'])
            ->where('dishub', '[0-9]+')
            ->name('dishubs.update');

        Route::delete('/dishubs/{dishub}', [DishubController::class, 'destroy'])
            ->where('dishub', '[0-9]+')
            ->name('dishubs.destroy');

        /**
         * Manajemen admin (superadmin only)
         */
        Route::resource('admins', AdminController::class);

        /**
         * Analitik & laporan
         */
        Route::get('/analytics', [DashboardController::class, 'analytics'])
            ->name('admin.analytics');

        Route::get('/laporan/pdf', [DashboardController::class, 'laporanPdf'])
            ->name('laporan.pdf');
    });
});