<?php
// Tambahkan baris berikut ke routes/web.php
use App\Http\Controllers\AkurasiController;
Route::get('/akurasi', [AkurasiController::class, 'index'])->name('akurasi.index');
