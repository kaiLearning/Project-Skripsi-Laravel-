<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PrediksiController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RuleController;
use App\Http\Controllers\AkurasiController;
use App\Http\Controllers\MasterProdukController;
use App\Http\Controllers\KategoriController;

use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/login');
});
Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/master-produk', [MasterProdukController::class, 'index'])->name('master_produk.index');

Route::resource('penjualan', PenjualanController::class);

Route::get('/prediksi', [PrediksiController::class, 'index'])->name('prediksi.index');
Route::post('/prediksi/{penjualan}', [PrediksiController::class, 'prediksi'])->name('prediksi.prediksi');

Route::resource('master-produk', MasterProdukController::class);
Route::resource('kategori', KategoriController::class);
Route::resource('rule', RuleController::class);

Route::get('/export/penjualan', [PenjualanController::class, 'exportPenjualan'])->name('export.penjualan');
Route::get('/export/prediksi', [PrediksiController::class, 'exportPrediksi'])->name('export.prediksi');
Route::get('/akurasi', [AkurasiController::class, 'index'])->name('akurasi.index');
Route::post('/akurasi/build-aktual', [AkurasiController::class, 'buildAktual'])
     ->name('akurasi.build');

Route::get('/logout', function () {
    Auth::logout();
    return redirect('/login');
});