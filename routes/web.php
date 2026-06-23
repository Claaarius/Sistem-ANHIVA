<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EdukasiController;
use App\Http\Controllers\SkriningController;
use App\Http\Controllers\KonselingController;
use App\Http\Controllers\KomentarController;
use App\Http\Controllers\AkunController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminEdukasiController;
use App\Http\Controllers\Admin\AdminSkriningController;
use App\Http\Controllers\Admin\AdminKonselingController;
use App\Http\Controllers\Admin\AdminKomentarController;
use App\Http\Controllers\Admin\AdminManagementController;
use App\Http\Controllers\Admin\AdminLaporanController;
use App\Http\Controllers\Admin\AdminGalleryController;

/*
|----------------------------------------------------------------
| User Routes
|----------------------------------------------------------------
*/

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/tentang', function () { return view('tentang'); })->name('tentang');

// Auth User
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Edukasi
Route::get('/edukasi', [EdukasiController::class, 'index'])->name('edukasi.index');
Route::get('/edukasi/{id}', [EdukasiController::class, 'show'])->name('edukasi.show');

// Skrining
Route::get('/skrining', [SkriningController::class, 'index'])->name('skrining.index');
Route::get('/skrining/mulai', [SkriningController::class, 'mulai'])->name('skrining.mulai');
Route::post('/skrining/jawaban', [SkriningController::class, 'simpanJawaban'])->name('skrining.jawaban');
Route::get('/skrining/hasil/{id}', [SkriningController::class, 'hasil'])->name('skrining.hasil');
Route::get('/skrining/riwayat', [SkriningController::class, 'riwayat'])->name('skrining.riwayat');

Route::post('/skrining/batalkan-session', function() {
    session()->forget(['skrining_pertanyaan', 'skrining_jawaban', 'skrining_current', 'kode_unik']);
    return response()->json(['success' => true]);
})->name('skrining.batalkan.session');

// Konseling
Route::get('/konseling', [KonselingController::class, 'index'])->name('konseling.index');
Route::get('/konseling/pengajuan', [KonselingController::class, 'pengajuan'])->name('konseling.pengajuan');
Route::post('/konseling/pengajuan', [KonselingController::class, 'store'])->name('konseling.store');
Route::get('/konseling/catatan', [KonselingController::class, 'catatan'])->name('konseling.catatan');

Route::get('/konseling/riwayat', [KonselingController::class, 'riwayatPengajuan']);
Route::get('/konseling/riwayat-pengajuan', [KonselingController::class, 'riwayatPengajuan'])->name('konseling.riwayat');
Route::post('/konseling/riwayat-pengajuan', [KonselingController::class, 'cariRiwayat'])->name('konseling.riwayat.cari');

Route::put('/konseling/{id}/konfirmasi', [KonselingController::class, 'konfirmasiJadwal'])->name('konseling.konfirmasi');
Route::put('/konseling/{id}/reschedule', [KonselingController::class, 'mintaReschedule'])->name('konseling.reschedule');
Route::delete('/konseling/{id}/batalkan', [KonselingController::class, 'batalkan'])->name('konseling.batalkan');

// Akun User
Route::middleware('auth')->group(function () {
    Route::get('/akun', [AkunController::class, 'index'])->name('akun.index');
    Route::put('/akun/profil', [AkunController::class, 'updateProfil'])->name('akun.profil');
    Route::put('/akun/password', [AkunController::class, 'updatePassword'])->name('akun.password');
    Route::delete('/akun/komentar/{id}', [AkunController::class, 'hapusKomentar'])->name('akun.komentar.hapus');
    Route::delete('/akun', [AkunController::class, 'destroy'])->name('akun.destroy');
});

// Komentar User
Route::post('/komentar', [KomentarController::class, 'store'])->name('komentar.store');
Route::put('/komentar/{id}', [KomentarController::class, 'update'])->name('komentar.update');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    /*
    |--------------------------------------------------------------------------
    | Admin Auth
    |--------------------------------------------------------------------------
    */

    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminLoginController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | Protected Admin Routes
    |--------------------------------------------------------------------------
    | Penting:
    | Pakai auth:admin supaya Auth::guard('admin')->user() tidak null.
    */

    Route::middleware('auth:admin')->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::put('/dashboard/hero', [AdminDashboardController::class, 'updateHero'])->name('dashboard.hero');

        Route::post('/dashboard/fakta', [AdminDashboardController::class, 'storeFakta'])->name('dashboard.fakta.store');
        Route::put('/dashboard/fakta/{id}', [AdminDashboardController::class, 'updateFakta'])->name('dashboard.fakta.update');
        Route::delete('/dashboard/fakta/{id}', [AdminDashboardController::class, 'destroyFakta'])->name('dashboard.fakta.destroy');

        // Edukasi
        Route::get('/edukasi', [AdminEdukasiController::class, 'index'])->name('edukasi.index');
        Route::get('/edukasi/create', [AdminEdukasiController::class, 'create'])->name('edukasi.create');
        Route::post('/edukasi', [AdminEdukasiController::class, 'store'])->name('edukasi.store');
        Route::get('/edukasi/{id}/edit', [AdminEdukasiController::class, 'edit'])->name('edukasi.edit');
        Route::put('/edukasi/{id}', [AdminEdukasiController::class, 'update'])->name('edukasi.update');
        Route::delete('/edukasi/{id}', [AdminEdukasiController::class, 'destroy'])->name('edukasi.destroy');

        // Skrining
        Route::get('/skrining', [AdminSkriningController::class, 'index'])->name('skrining.index');
        Route::get('/skrining/create', [AdminSkriningController::class, 'create'])->name('skrining.create');
        Route::post('/skrining', [AdminSkriningController::class, 'store'])->name('skrining.store');
        Route::get('/skrining/{id}/edit', [AdminSkriningController::class, 'edit'])->name('skrining.edit');
        Route::put('/skrining/{id}', [AdminSkriningController::class, 'update'])->name('skrining.update');
        Route::delete('/skrining/{id}', [AdminSkriningController::class, 'destroy'])->name('skrining.destroy');

        // Konseling
        Route::get('/konseling', [AdminKonselingController::class, 'index'])->name('konseling.index');
        Route::get('/konseling/{id}', [AdminKonselingController::class, 'show'])->name('konseling.show');
        Route::put('/konseling/{id}/status', [AdminKonselingController::class, 'updateStatus'])->name('konseling.status');
        Route::put('/konseling/{id}/setuju-reschedule', [AdminKonselingController::class, 'setujuReschedule'])->name('konseling.setuju-reschedule');
        Route::put('/konseling/{id}/jadwalkan-ulang', [AdminKonselingController::class, 'jadwalkanUlang'])->name('konseling.jadwalkan-ulang');
        Route::delete('/konseling/{id}', [AdminKonselingController::class, 'destroy'])->name('konseling.destroy');

        Route::post('/konseling/{id}/catatan', [AdminKonselingController::class, 'storeCatatan'])->name('konseling.catatan');
        Route::post('/konseling/{id}/rujukan', [AdminKonselingController::class, 'storeRujukan'])->name('konseling.rujukan.store');
        Route::put('/rujukan/{id}', [AdminKonselingController::class, 'updateRujukan'])->name('rujukan.update');
        Route::delete('/rujukan/{id}', [AdminKonselingController::class, 'destroyRujukan'])->name('rujukan.destroy');

        // Komentar
        Route::get('/komentar', [AdminKomentarController::class, 'index'])->name('komentar.index');
        Route::put('/komentar/{id}/approve', [AdminKomentarController::class, 'approve'])->name('komentar.approve');
        Route::put('/komentar/{id}/reject', [AdminKomentarController::class, 'reject'])->name('komentar.reject');
        Route::delete('/komentar/{id}', [AdminKomentarController::class, 'destroy'])->name('komentar.destroy');
        Route::put('/komentar/{id}/read', [AdminKomentarController::class, 'markAsRead'])->name('komentar.read');

        // Gallery
        Route::get('/gallery', [AdminGalleryController::class, 'index'])->name('gallery.index');
        Route::post('/gallery', [AdminGalleryController::class, 'store'])->name('gallery.store');
        Route::put('/gallery/{id}', [AdminGalleryController::class, 'update'])->name('gallery.update');
        Route::delete('/gallery/{id}', [AdminGalleryController::class, 'destroy'])->name('gallery.destroy');

        // Laporan
        Route::get('/laporan', [AdminLaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/preview', [AdminLaporanController::class, 'preview'])->name('laporan.preview');
        Route::get('/laporan/pdf', [AdminLaporanController::class, 'exportPdf'])->name('laporan.pdf');

        // Password & Profile Admin
        Route::get('/password', [AdminManagementController::class, 'showChangePassword'])->name('password');
        Route::put('/password', [AdminManagementController::class, 'changePassword'])->name('password.update');
        Route::put('/profil', [AdminManagementController::class, 'updateProfil'])->name('profil.update');

        // Admin Management khusus Super Admin
        Route::middleware('super_admin')->group(function () {
            Route::get('/manajemen', [AdminManagementController::class, 'index'])->name('manajemen.index');
            Route::post('/manajemen', [AdminManagementController::class, 'store'])->name('manajemen.store');
            Route::delete('/manajemen/{id}', [AdminManagementController::class, 'destroy'])->name('manajemen.destroy');
        });
    });
});