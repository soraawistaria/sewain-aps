<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\BarangController as AdminBarangController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\LaporanController as AdminLaporanController;
use App\Http\Controllers\Admin\PengaturanController as AdminPengaturanController;
use App\Http\Controllers\Admin\PenggunaController as AdminPenggunaController;
use App\Http\Controllers\Admin\PesananController as AdminPesananController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\ProfilController;
use Illuminate\Support\Facades\Route;

Route::get('/', [KatalogController::class, 'index'])->name('home');
Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog.index');
Route::get('/barang/{barang}', [KatalogController::class, 'show'])->name('katalog.show');

Route::middleware('guest:web')->group(function (): void {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('login.store');

    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('register.store');
});

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth:web')
    ->name('logout');

Route::middleware('auth:web')->group(function (): void {
    Route::get('/dashboard', [PesananController::class, 'index'])->name('dashboard');
    Route::get('/pesanan-saya/{pesanan}', [PesananController::class, 'show'])->name('pesanan.show');
    Route::post('/barang/{barang}/pesanan', [PesananController::class, 'store'])->name('pesanan.store');
    Route::patch('/pesanan-saya/{pesanan}/batalkan', [PesananController::class, 'cancel'])->name('pesanan.cancel');

    Route::get('/profil', [ProfilController::class, 'edit'])->name('profil.edit');
    Route::patch('/profil/password', [ProfilController::class, 'update'])->name('profil.update');
});

Route::prefix('admin')->name('admin.')->group(function (): void {
    Route::middleware('guest:admin')->group(function (): void {
        Route::get('/login', [AdminAuthController::class, 'create'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'store'])
            ->middleware('throttle:6,1')
            ->name('login.store');

        Route::get('/setup', [AdminAuthController::class, 'setup'])->name('setup');
        Route::post('/setup', [AdminAuthController::class, 'storeSetup'])
            ->middleware('throttle:6,1')
            ->name('setup.store');
    });

    Route::post('/logout', [AdminAuthController::class, 'destroy'])
        ->middleware('auth:admin')
        ->name('logout');

    Route::middleware('auth:admin')->group(function (): void {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::resource('barang', AdminBarangController::class)->except(['show']);

        Route::get('/pesanan', [AdminPesananController::class, 'index'])->name('pesanan.index');
        Route::get('/pesanan/{pesanan}', [AdminPesananController::class, 'show'])->name('pesanan.show');
        Route::patch('/pesanan/{pesanan}/setujui', [AdminPesananController::class, 'approve'])->name('pesanan.approve');
        Route::patch('/pesanan/{pesanan}/tolak', [AdminPesananController::class, 'reject'])->name('pesanan.reject');
        Route::patch('/pesanan/{pesanan}/disewa', [AdminPesananController::class, 'markRented'])->name('pesanan.mark-rented');
        Route::patch('/pesanan/{pesanan}/selesai', [AdminPesananController::class, 'complete'])->name('pesanan.complete');

        Route::get('/pengguna', [AdminPenggunaController::class, 'index'])->name('pengguna.index');
        Route::get('/laporan', [AdminLaporanController::class, 'index'])->name('laporan.index');

        Route::get('/pengaturan', [AdminPengaturanController::class, 'edit'])->name('pengaturan.edit');
        Route::patch('/pengaturan/password', [AdminPengaturanController::class, 'updatePassword'])
            ->name('pengaturan.password');
    });
});
