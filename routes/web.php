<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\IncomingClaimController;
use App\Http\Controllers\MyItemController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Auth::check()
    ? redirect()->route(Auth::user()->homeRoute())
    : redirect()->route('login');
});

// Tamu
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

//Mahasiswa
Route::middleware(['auth', 'role:mahasiswa'])->group(function () {
    Route::get('/katalog', [CatalogController::class, 'index'])->name('catalog.index');
    Route::get('/barang/{item}', [CatalogController::class, 'show'])->name('catalog.show');
    Route::get('/barang/{item}/klaim', [ClaimController::class, 'store'])->name('claim.store');
    Route::get('/barang/{item}/laporkan', [ReportController::class, 'store'])->name('report.store');

    Route::resource('barang-saya', MyItemController::class)
        ->parameters(['barang-saya' => 'item'])
        ->names('my-items')
        ->except('show');

    Route::get('/permintaan-masuk', [IncomingClaimController::class, 'index'])->name('incoming.index');
    Route::post('/permintaan-masuk/{claim}/terima', [IncomingClaimController::class, 'accept'])->name('incoming.accept');
    Route::post('/permintaan-masuk/{claim}/tolak', [IncomingClaimController::class, 'reject'])->name('incoming.reject');

    Route::get('/riwayat-klaim', [ClaimController::class, 'index'])->name('claims.index');
    Route::put('/riwayat-klaim/{claim}', [ClaimController::class, 'update'])->name('claims.update');
    Route::delete('/riwayat-klaim/{claim}', [ClaimController::class, 'destroy'])->name('claims.destroy');
});

//Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('.dashboard');
    Route::post('/kategori', [AdminController::class, 'storeCategory'])->name('categories.store');
    Route::put('/kategori/{category}', [AdminController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/kategori/{category}', [AdminController::class, 'destroyCategory'])->name('categories.destroy');
    Route::patch('/laporan/{report}/abaikan', [AdminController::class, 'ignoreReport'])->name('reports.ignore');
    Route::patch('/laporan/{report}/nonaktifkan', [AdminController::class, 'deactivateItem'])->name('reports.deactivate');
});