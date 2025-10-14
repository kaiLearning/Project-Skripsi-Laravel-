<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AktualKategoriBuilder;

class AkurasiBuildAktual extends Command
{
    protected $signature = 'akurasi:build-aktual {--start=} {--end=}';
    protected $description = 'Hitung & isi aktual_kategori di prediksis dari penjualans + rules';

    public function handle(AktualKategoriBuilder $builder): int
    {
        $n = $builder->build($this->option('start'), $this->option('end'));
        $this->info("Selesai. {$n} baris di-update/insert aktual_kategori.");
        return self::SUCCESS;
    }
}
