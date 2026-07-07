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

// =========================
// AUTH
// =========================
Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login')
    ->middleware('guest');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// =========================
// PROTECTED ROUTES
// =========================
Route::middleware('auth')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // =========================
    // SISWA
    // =========================

    // HARUS DI ATAS RESOURCE
    Route::post('/siswa/import', [SiswaController::class, 'import'])
        ->name('siswa.import');

    Route::get('/siswa/download-template', [SiswaController::class, 'downloadTemplate'])
        ->name('siswa.download-template');

    Route::resource('siswa', SiswaController::class);

    // =========================
    // KASUS
    // =========================
    Route::resource('kasus', KasusController::class)->parameters([
        'kasus' => 'kasus'
    ]);

    Route::patch('kasus/{kasus}/status', [KasusController::class, 'updateStatus'])
        ->name('kasus.status');

    // =========================
    // TINDAK LANJUT
    // =========================
    Route::post('kasus/{kasus}/tindak-lanjut', [TindakLanjutController::class, 'store'])
        ->name('tindak-lanjut.store');

    Route::delete('tindak-lanjut/{tindakLanjut}', [TindakLanjutController::class, 'destroy'])
        ->name('tindak-lanjut.destroy');

    // =========================
    // HOME VISIT
    // =========================
    Route::post('kasus/{kasus}/home-visit', [HomeVisitController::class, 'store'])
        ->name('home-visit.store');

    // =========================
    // ADMIN
    // =========================
    Route::middleware('role:admin')->group(function () {

        Route::resource('users', UserController::class);
        Route::resource('kategori', KategoriController::class);

        Route::get('laporan', [LaporanController::class, 'index'])
            ->name('laporan.index');

        Route::get('laporan/export-pdf', [LaporanController::class, 'exportPdf'])
            ->name('laporan.pdf');

        Route::get('laporan/export-excel', [LaporanController::class, 'exportExcel'])
            ->name('laporan.excel');
    });

    // =========================
    // ADMIN + PIMPINAN
    // =========================
    Route::middleware('role:admin,pimpinan')->group(function () {

        Route::get('statistik', [DashboardController::class, 'statistik'])
            ->name('statistik');

    });

});