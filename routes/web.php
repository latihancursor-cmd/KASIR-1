<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\PosController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
require __DIR__.'/lock.php';

// Owner-only management features.
Route::middleware(['auth', 'role:owner'])->group(function () {
    Route::resource('categories', App\Http\Controllers\CategoryController::class);
    Route::resource('products', App\Http\Controllers\ProductController::class);
});

// Features shared by owner and cashier.
Route::middleware(['auth', 'role:owner,kasir'])->prefix('kasir')->group(function () {
    Route::get('/', [App\Http\Controllers\KasirController::class, 'beranda'])->name('kasir.beranda');
    Route::get('/beranda', [App\Http\Controllers\KasirController::class, 'beranda'])->name('kasir.dashboard');
    Route::put('/modal-harian', [App\Http\Controllers\KasirController::class, 'dailyCapitalUpdate'])->name('kasir.daily-capital.update');
    Route::get('/penjualan', [App\Http\Controllers\KasirController::class, 'pos'])->name('kasir.penjualan');
    Route::post('/checkout', [App\Http\Controllers\KasirController::class, 'checkout'])->name('kasir.checkout');
    Route::get('/riwayat', [App\Http\Controllers\KasirController::class, 'history'])->name('kasir.history');
    Route::get('/menu', [App\Http\Controllers\KasirController::class, 'pos'])->name('kasir.purr');
});

// Owner-only KasirNih management modules.
Route::middleware(['auth', 'role:owner'])->prefix('kasir')->group(function () {
    Route::get('/produk', [App\Http\Controllers\KasirController::class, 'products'])->name('kasir.products');
    Route::get('/produk/tambah', [App\Http\Controllers\KasirController::class, 'productCreate'])->name('kasir.products.create');
    Route::post('/produk', [App\Http\Controllers\KasirController::class, 'productStore'])->name('kasir.products.store');
    Route::get('/produk/sku', [App\Http\Controllers\KasirController::class, 'generateProductSku'])->name('kasir.products.sku');
    Route::get('/produk/{product}/edit', [App\Http\Controllers\KasirController::class, 'productEdit'])->name('kasir.products.edit');
    Route::put('/produk/{product}', [App\Http\Controllers\KasirController::class, 'productUpdate'])->name('kasir.products.update');
    Route::delete('/produk/{product}', [App\Http\Controllers\KasirController::class, 'productDestroy'])->name('kasir.products.destroy');
    Route::get('/stok', [App\Http\Controllers\KasirController::class, 'stock'])->name('kasir.stock');
    Route::post('/stok/{product}/tambah', [App\Http\Controllers\KasirController::class, 'stockAdd'])->name('kasir.stock.add');
    Route::get('/pengaturan', [App\Http\Controllers\KasirController::class, 'settings'])->name('kasir.settings');
    Route::put('/pengaturan', [App\Http\Controllers\KasirController::class, 'settingsUpdate'])->name('kasir.settings.update');
    Route::post('/kategori', [App\Http\Controllers\KasirController::class, 'categoryStore'])->name('kasir.categories.store');
    Route::put('/kategori/{category}', [App\Http\Controllers\KasirController::class, 'categoryUpdate'])->name('kasir.categories.update');
    Route::delete('/kategori/{category}', [App\Http\Controllers\KasirController::class, 'categoryDestroy'])->name('kasir.categories.destroy');
    Route::get('/laporan', [App\Http\Controllers\KasirController::class, 'reports'])->name('kasir.reports');
});
