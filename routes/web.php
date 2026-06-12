<?php

use App\Http\Controllers\Admin\KategoriAdminController;
use App\Http\Controllers\Admin\MahasiswaAdminController;
use App\Http\Controllers\Admin\PenggunaAdminController;
use App\Http\Controllers\Admin\PortofolioAdminController;
use App\Http\Controllers\Admin\VerifikasiController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BuktiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PortofolioController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\ShowcaseController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman publik (showcase) — tanpa login
|--------------------------------------------------------------------------
*/
Route::get('/', [ShowcaseController::class, 'index'])->name('showcase.index');
Route::get('/showcase/mahasiswa/{mahasiswa}', [ShowcaseController::class, 'mahasiswa'])->name('showcase.mahasiswa');

// Berkas bukti: otorisasi diatur di controller (pemilik/petugas/publik)
Route::get('/bukti/{bukti}', [BuktiController::class, 'show'])->name('bukti.show');

/*
|--------------------------------------------------------------------------
| Autentikasi
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:10,1')->name('login.attempt');
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| Area login (semua peran)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('role:admin,verifikator,mahasiswa')
        ->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| Area mahasiswa
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:mahasiswa'])->group(function () {
    Route::resource('portofolio', PortofolioController::class)
        ->parameters(['portofolio' => 'portofolio']);

    Route::post('/portofolio/{portofolio}/ajukan', [PortofolioController::class, 'ajukan'])->name('portofolio.ajukan');
    Route::post('/portofolio/{portofolio}/publik', [PortofolioController::class, 'tampilkanPublik'])->name('portofolio.publik');
    Route::post('/portofolio/{portofolio}/bukti', [PortofolioController::class, 'simpanBuktiBaru'])->name('portofolio.bukti.store');
    Route::delete('/bukti/{bukti}', [PortofolioController::class, 'hapusBukti'])->name('bukti.destroy');

    Route::get('/profil', [ProfilController::class, 'edit'])->name('profil.edit');
    Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');
    Route::put('/profil/password', [ProfilController::class, 'updatePassword'])->name('profil.password');
});

/*
|--------------------------------------------------------------------------
| Area admin & verifikator
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin,verifikator'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/verifikasi', [VerifikasiController::class, 'index'])->name('verifikasi.index');
    Route::get('/verifikasi/{portofolio}', [VerifikasiController::class, 'show'])->name('verifikasi.show');
    Route::post('/verifikasi/{portofolio}', [VerifikasiController::class, 'proses'])->name('verifikasi.proses');

    Route::get('/portofolio', [PortofolioAdminController::class, 'index'])->name('portofolio.index');
});

/*
|--------------------------------------------------------------------------
| Area khusus admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::post('/portofolio/{portofolio}/publikasikan', [VerifikasiController::class, 'publikasikan'])->name('portofolio.publikasikan');
    Route::post('/portofolio/{portofolio}/batalkan-publikasi', [VerifikasiController::class, 'batalkanPublikasi'])->name('portofolio.batalkan');

    Route::resource('mahasiswa', MahasiswaAdminController::class)
        ->parameters(['mahasiswa' => 'mahasiswa'])
        ->except(['show']);

    Route::get('/kategori', [KategoriAdminController::class, 'index'])->name('kategori.index');
    Route::post('/kategori', [KategoriAdminController::class, 'store'])->name('kategori.store');
    Route::put('/kategori/{kategori}', [KategoriAdminController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{kategori}', [KategoriAdminController::class, 'destroy'])->name('kategori.destroy');

    Route::get('/pengguna', [PenggunaAdminController::class, 'index'])->name('pengguna.index');
    Route::post('/pengguna', [PenggunaAdminController::class, 'store'])->name('pengguna.store');
    Route::post('/pengguna/{pengguna}/toggle', [PenggunaAdminController::class, 'toggleAktif'])->name('pengguna.toggle');
    Route::post('/pengguna/{pengguna}/reset-password', [PenggunaAdminController::class, 'resetPassword'])->name('pengguna.reset');
});
