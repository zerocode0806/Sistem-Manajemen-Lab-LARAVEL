<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\MahasiswaAuthController;
use App\Http\Controllers\Admin\DashboardController       as AdminDashboardController;
use App\Http\Controllers\Admin\LabController             as AdminLabController;
use App\Http\Controllers\Admin\BarangController          as AdminBarangController;
use App\Http\Controllers\Admin\MahasiswaController       as AdminMahasiswaController;
use App\Http\Controllers\Admin\PeminjamanController      as AdminPeminjamanController;
use App\Http\Controllers\Admin\InventarisController      as AdminInventarisController;
use App\Http\Controllers\Admin\AkunController            as AdminAkunController;
use App\Http\Controllers\Mahasiswa\DashboardController   as MahasiswaDashboardController;
use App\Http\Controllers\Mahasiswa\PeminjamanController  as MahasiswaPeminjamanController;

/*
|--------------------------------------------------------------------------
| Landing Page
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| Admin Auth Routes
|--------------------------------------------------------------------------
*/
Route::get('/admin/login',     [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login',    [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::get('/admin/register',  [AdminAuthController::class, 'showRegisterForm'])->name('admin.register');
Route::post('/admin/register', [AdminAuthController::class, 'register'])->name('admin.register.submit');

/*
|--------------------------------------------------------------------------
| Mahasiswa Auth Routes
|--------------------------------------------------------------------------
*/
Route::get('/mahasiswa/login',  [MahasiswaAuthController::class, 'showLoginForm'])->name('mahasiswa.login');
Route::post('/mahasiswa/login', [MahasiswaAuthController::class, 'login'])->name('mahasiswa.login.submit');

/*
|--------------------------------------------------------------------------
| Admin Routes (Protected)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard',         [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/refresh', [AdminDashboardController::class, 'refresh'])->name('dashboard.refresh');
    Route::post('/logout',           [AdminAuthController::class, 'logout'])->name('logout');

    // Lab
    Route::resource('lab', AdminLabController::class);

    // Barang
    Route::resource('barang', AdminBarangController::class);

    // Mahasiswa
    Route::resource('mahasiswa', AdminMahasiswaController::class);

    // Peminjaman
    Route::get('peminjaman',                          [AdminPeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('peminjaman/create',                   [AdminPeminjamanController::class, 'create'])->name('peminjaman.create');
    Route::post('peminjaman',                         [AdminPeminjamanController::class, 'store'])->name('peminjaman.store');
    Route::get('peminjaman-riwayat',                  [AdminPeminjamanController::class, 'riwayat'])->name('peminjaman.riwayat');
    Route::get('peminjaman-arsip',                    [AdminPeminjamanController::class, 'arsip'])->name('peminjaman.arsip');
    Route::get('peminjaman/{peminjaman}',             [AdminPeminjamanController::class, 'show'])->name('peminjaman.show');
    Route::post('peminjaman/{peminjaman}/approve',    [AdminPeminjamanController::class, 'approve'])->name('peminjaman.approve');
    Route::post('peminjaman/{peminjaman}/reject',     [AdminPeminjamanController::class, 'reject'])->name('peminjaman.reject');
    Route::post('peminjaman/{peminjaman}/checkout',   [AdminPeminjamanController::class, 'checkout'])->name('peminjaman.checkout');

    // Inventaris
    Route::get('inventaris/{id_lab}',                   [AdminInventarisController::class, 'index'])->name('inventaris.index');
    Route::post('inventaris/update',                    [AdminInventarisController::class, 'update'])->name('inventaris.update');
    Route::post('inventaris/{id_lab}/tambah-ac',        [AdminInventarisController::class, 'tambahAc'])->name('inventaris.tambahAc');
    Route::get('inventaris/{id_lab}/hapus-ac/{id_ac}',  [AdminInventarisController::class, 'hapusAc'])->name('inventaris.hapusAc');
    Route::post('inventaris/{id_lab}/simpan-periode',   [AdminInventarisController::class, 'simpanPeriode'])->name('inventaris.simpanPeriode');
    Route::get('inventaris/{id_lab}/riwayat',           [AdminInventarisController::class, 'riwayat'])->name('inventaris.riwayat');
    Route::get('inventaris-periode/{id_periode}',       [AdminInventarisController::class, 'detailPeriode'])->name('inventaris.detailPeriode');
    Route::delete('inventaris/{id_lab}/riwayat/{id_periode}', [AdminInventarisController::class, 'hapusPeriode'])->name('inventaris.hapusPeriode');
    Route::get('inventaris/{id_lab}/export',            [AdminInventarisController::class, 'export'])->name('inventaris.export');
    Route::get('inventaris-periode/{id_periode}/export',[AdminInventarisController::class, 'exportPeriode'])->name('inventaris.exportPeriode');

    // Akun Admin
    Route::get('akun',             [AdminAkunController::class, 'index'])->name('akun.index');
    Route::get('akun/create',      [AdminAkunController::class, 'create'])->name('akun.create');
    Route::post('akun',            [AdminAkunController::class, 'store'])->name('akun.store');
    Route::get('akun/{id}/edit',   [AdminAkunController::class, 'edit'])->name('akun.edit');
    Route::put('akun/{id}',        [AdminAkunController::class, 'update'])->name('akun.update');
    Route::delete('akun/{id}',     [AdminAkunController::class, 'destroy'])->name('akun.destroy');
});

/*
|--------------------------------------------------------------------------
| Mahasiswa Routes (Protected)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {

    Route::get('/dashboard', [MahasiswaDashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout',   [MahasiswaAuthController::class, 'logout'])->name('logout');

    // Peminjaman
    Route::get('peminjaman/create',   [MahasiswaPeminjamanController::class, 'create'])->name('peminjaman.create');
    Route::post('peminjaman',         [MahasiswaPeminjamanController::class, 'store'])->name('peminjaman.store');
    Route::get('peminjaman-riwayat',  [MahasiswaPeminjamanController::class, 'riwayat'])->name('peminjaman.riwayat');
    Route::get('peminjaman-arsip',    [MahasiswaPeminjamanController::class, 'arsip'])->name('peminjaman.arsip');
    Route::get('peminjaman/{peminjaman}', [MahasiswaPeminjamanController::class, 'show'])->name('peminjaman.show');
});
