<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AktualKategoriBuilder
{
    public function build(?string $start = null, ?string $end = null): int
    {
        $rulesAll = DB::table('rules')->get();
        $rulesByKategoriId = $rulesAll->groupBy('kategori_produk');

        $q = DB::table('penjualans as p')
            ->selectRaw('p.produk_id, DATE(p.created_at) as tgl')
            ->selectRaw('SUM(p.jumlah) as sum_jumlah')
            ->selectRaw('SUM(p.total_harga) as sum_total_harga')
            ->groupBy('p.produk_id', 'tgl');

        if ($start) $q->whereDate('p.created_at', '>=', $start);
        if ($end)   $q->whereDate('p.created_at', '<=', $end);

        $rows = $q->get();

        $upserts = [];
        foreach ($rows as $r) {
            $kategoriId = DB::table('master_produks')->where('id', $r->produk_id)->value('kategori_id');

            $rule = optional($rulesByKategoriId->get($kategoriId))->first();

            $isTinggi = false;
            if ($rule) {
                $minJ = $rule->min_jumlah ?? PHP_INT_MAX;
                $minH = $rule->min_harga  ?? PHP_INT_MAX;
                $isTinggi = ($r->sum_jumlah >= $minJ) || ($r->sum_total_harga >= $minH);
            } else {
                $isTinggi = $r->sum_jumlah > 0;
            }
            $aktual = $isTinggi ? 'Tinggi' : 'Rendah';

            $predAda = DB::table('prediksis')
                ->where('produk_id', $r->produk_id)
                ->whereDate('tanggal', $r->tgl)
                ->exists();

            if ($predAda) {
                $upserts[] = [
                    'produk_id'       => $r->produk_id,
                    'tanggal'         => $r->tgl,
                    'aktual_kategori' => $aktual,
                    'updated_at'      => now(),
                ];
            }
        }

        foreach ($upserts as $u) {
            DB::table('prediksis')
                ->where('produk_id', $u['produk_id'])
                ->whereDate('tanggal', $u['tanggal'])
                ->update([
                    'aktual_kategori' => $u['aktual_kategori'],
                    'updated_at'      => now(),
                ]);
        }

        return count($upserts);
    }
}
