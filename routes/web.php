<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\KasusController;
use App\Http\Controllers\TindakLanjutController;
use App\Http\Controllers\HomeVisitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LaporanController;

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Siswa - Admin CRUD, Guru BK & Pimpinan view
    Route::resource('siswa', SiswaController::class);

    // Kasus
    Route::resource('kasus', KasusController::class);
    Route::patch('kasus/{kasus}/status', [KasusController::class, 'updateStatus'])->name('kasus.status');

    // Tindak Lanjut
    Route::post('kasus/{kasus}/tindak-lanjut', [TindakLanjutController::class, 'store'])->name('tindak-lanjut.store');
    Route::delete('tindak-lanjut/{tindakLanjut}', [TindakLanjutController::class, 'destroy'])->name('tindak-lanjut.destroy');

    // Home Visit
    Route::post('kasus/{kasus}/home-visit', [HomeVisitController::class, 'store'])->name('home-visit.store');

    // Admin only
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('kategori', KategoriController::class);
        Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('laporan/export-pdf', [LaporanController::class, 'exportPdf'])->name('laporan.pdf');
        Route::get('laporan/export-excel', [LaporanController::class, 'exportExcel'])->name('laporan.excel');
    });

    // Pimpinan + Admin
    Route::middleware('role:admin,pimpinan')->group(function () {
        Route::get('statistik', [DashboardController::class, 'statistik'])->name('statistik');
    });
});