# Fitur Akurasi (Confusion Matrix) — Laravel
Berkas ini berisi file-file siap tempel:

## File yang disertakan
- database/migrations/2025_08_27_000000_create_prediksis_table.php
- app/Services/ConfusionMatrix.php
- app/Services/AktualKategoriBuilder.php
- app/Console/Commands/AkurasiBuildAktual.php
- app/Http/Controllers/AkurasiController.php
- resources/views/akurasi/index.blade.php
- routes/web.append.php (potongan route — copy ke routes/web.php)

## Langkah pasang
1) Salin semua folder ini ke root project Laravel Anda (gabungkan).
2) Jalankan migrasi:
   php artisan migrate
3) (Opsional) Daftarkan command bila perlu (Laravel < 10) di app/Console/Kernel.php:
   protected $commands = [\App\Console\Commands\AkurasiBuildAktual::class];
4) Simpan hasil prediksi model ke tabel `prediksis` (produk_id, tanggal [YYYY-MM-DD], prediksi_kategori).
5) Isi `aktual_kategori` dari transaksi + rules:
   php artisan akurasi:build-aktual --start=2025-01-01 --end=2025-12-31
6) Buka halaman:
   /akurasi

## Penyesuaian kolom
- Jika tanggal transaksi bukan `created_at` — ganti di App/Services/AktualKategoriBuilder.php
- Jika kuantitas bukan `jumlah` — ganti ke nama kolom Anda (mis. qty).
- Jika kategori memakai ID — ganti lookup rules per komentar di file builder.

— Dibuat otomatis pada 2025-08-27 08:49:52.160173
