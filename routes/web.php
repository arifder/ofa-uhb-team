<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FakultasController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KasController;
use App\Http\Controllers\NotulensiController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\ProfilController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

    // Route Kas
    Route::middleware(['auth', 'checkRole:super_admin,admin_kas_fst,admin_kas_fis,kepala_unit'])
        ->prefix('kas')
        ->name('kas.')
        ->group(function () {
            Route::get('laporan', [KasController::class, 'laporan'])->name('laporan');
            Route::get('masuk', [KasController::class, 'index'])->name('masuk');
            Route::get('keluar', [KasController::class, 'index'])->name('keluar');

            Route::get('tagihan', [KasController::class, 'tagihan'])->name('tagihan');
            Route::get('tagihan/{id}', [KasController::class, 'showTagihan'])->name('tagihan.show');
            Route::post('tagihan/{id}/bayar', [KasController::class, 'bayarTagihan'])->name('tagihan.bayar');
            Route::post('tagihan', [KasController::class, 'storeTagihan'])->name('tagihan.store');
            Route::delete('tagihan/{id}', [KasController::class, 'destroyTagihan'])->name('tagihan.destroy');

            Route::get('transaksi/{id}', [KasController::class, 'show'])->name('transaksi.show');
            Route::post('transaksi', [KasController::class, 'store'])->name('transaksi.store');
            Route::put('transaksi/{id}', [KasController::class, 'update'])->name('transaksi.update');
            Route::delete('transaksi/{id}', [KasController::class, 'destroy'])->name('transaksi.destroy');
        });

    // Route Notulensi
    Route::middleware(['auth', 'checkRole:super_admin,admin_notulensi_fst,admin_notulensi_fis,kepala_unit,dosen'])
        ->group(function () {
            // Specific routes BEFORE resource to avoid {notulensi} conflicts
            Route::get('notulensi/dosen/{id}', [NotulensiController::class, 'getByDosen'])
                ->name('notulensi.byDosen');
            Route::get('notulensi/{id}/export-bap', [NotulensiController::class, 'exportBap'])
                ->name('notulensi.exportBap');
            Route::resource('notulensi', NotulensiController::class)
                ->except(['create', 'edit']);
        });

    // Route Master Data
    Route::middleware(['auth', 'checkRole:super_admin'])
        ->prefix('master')
        ->name('master.')
        ->group(function () {
            Route::resource('users', UserController::class)
                ->except(['show', 'create', 'edit']);
                
            Route::resource('fakultas', FakultasController::class)
                ->except(['show', 'create', 'edit']);
                
            Route::resource('prodi', ProdiController::class)
                ->except(['show', 'create', 'edit', 'index']);
                
            Route::get('prodi/by-fakultas/{id}', [ProdiController::class, 'getByFakultas'])
                ->name('prodi.byFakultas');
                
            Route::resource('dosen', DosenController::class)
                ->except(['show', 'create', 'edit']);
        });

    // Route Notifikasi
    Route::middleware(['auth'])->group(function () {
        Route::get('notifikasi', [NotifikasiController::class, 'index'])
            ->name('notifikasi.index');
        Route::get('notifikasi/unread', [NotifikasiController::class, 'getUnread'])
            ->name('notifikasi.unread');
        Route::post('notifikasi/read-all', [NotifikasiController::class, 'markAllRead'])
            ->name('notifikasi.readAll');
        Route::post('notifikasi/{id}/read', [NotifikasiController::class, 'markRead'])
            ->name('notifikasi.read');
    });

    // Route Pengaturan Profil
    Route::middleware(['auth'])->group(function () {
        Route::get('pengaturan/profil', [ProfilController::class, 'index'])
            ->name('pengaturan.profil');
        Route::post('pengaturan/profil', [ProfilController::class, 'update'])
            ->name('pengaturan.update');
        Route::post('pengaturan/password', [ProfilController::class, 'updatePassword'])
            ->name('pengaturan.password');
    });
