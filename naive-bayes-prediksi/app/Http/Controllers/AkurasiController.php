<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\MasterProduk;

class AkurasiController extends Controller
{
    public function index(Request $req)
    {
        $start   = $req->query('start');
        $end     = $req->query('end');
        $produk  = $req->query('produk');
        $labelsQ = trim((string) $req->query('labels', ''));

        $produkOptions = MasterProduk::orderBy('nama_produk')->get(['id', 'nama_produk']);

        if (!$start || !$end) {
            return view('akurasi.index', [
                'produkOptions' => $produkOptions,
                'labels'   => [],
                'matrix'   => [],
                'support'  => [],
                'perClass' => [],
                'macro'    => ['precision' => 0, 'recall' => 0, 'f1' => 0],
                'micro'    => ['precision' => 0, 'recall' => 0, 'f1' => 0],
                'accuracy' => 0,
                'total'    => 0,
            ]);
        }

        $rows = DB::table('prediksis')
            ->select('produk_id', 'tanggal', 'prediksi_kategori', 'aktual_kategori')
            ->whereBetween('tanggal', [$start, $end])
            ->when($produk, fn($q) => $q->where('produk_id', $produk))
            ->whereNotNull('prediksi_kategori')
            ->whereNotNull('aktual_kategori')
            ->get();
        $labels = [];
        if ($labelsQ !== '') {
            $labels = array_values(array_filter(array_map('trim', explode(',', $labelsQ))));
        } else {
            $set = [];
            foreach ($rows as $r) {
                if ($r->prediksi_kategori) $set[$r->prediksi_kategori] = true;
                if ($r->aktual_kategori)   $set[$r->aktual_kategori]   = true;
            }
            $labels = array_keys($set);
            sort($labels, SORT_NATURAL | SORT_FLAG_CASE);
        }

        if (empty($labels) || $rows->isEmpty()) {
            return view('akurasi.index', [
                'produkOptions' => $produkOptions,
                'labels'   => $labels,
                'matrix'   => [],
                'support'  => [],
                'perClass' => [],
                'macro'    => ['precision' => 0, 'recall' => 0, 'f1' => 0],
                'micro'    => ['precision' => 0, 'recall' => 0, 'f1' => 0],
                'accuracy' => 0,
                'total'    => 0,
            ]);
        }

        $n = count($labels);
        $index = array_flip($labels);
        $matrix = array_fill(0, $n, array_fill(0, $n, 0));
        $support = array_fill_keys($labels, 0);
        $total = 0;

        foreach ($rows as $r) {
            if (!isset($index[$r->aktual_kategori]) || !isset($index[$r->prediksi_kategori])) continue;
            $i = $index[$r->aktual_kategori];
            $j = $index[$r->prediksi_kategori];
            $matrix[$i][$j] += 1;
            $support[$labels[$i]] += 1;
            $total++;
        }

        $perClass = [];
        $sumTP = $sumFP = $sumFN = 0;
        $diag = 0;

        for ($k = 0; $k < $n; $k++) {
            $tp = $matrix[$k][$k];
            $fp = 0;
            for ($i = 0; $i < $n; $i++) if ($i !== $k) $fp += $matrix[$i][$k];
            $fn = 0;
            for ($j = 0; $j < $n; $j++) if ($j !== $k) $fn += $matrix[$k][$j];
            $tn = $total - $tp - $fp - $fn;

            $precision = ($tp + $fp) ? $tp / ($tp + $fp) : 0.0;
            $recall    = ($tp + $fn) ? $tp / ($tp + $fn) : 0.0;
            $f1        = ($precision + $recall) ? (2 * $precision * $recall) / ($precision + $recall) : 0.0;

            $perClass[$labels[$k]] = [
                'tp' => $tp,
                'fp' => $fp,
                'fn' => $fn,
                'tn' => $tn,
                'precision' => $precision,
                'recall' => $recall,
                'f1' => $f1,
            ];

            $sumTP += $tp;
            $sumFP += $fp;
            $sumFN += $fn;
            $diag += $tp;
        }

        $macro = ['precision' => 0, 'recall' => 0, 'f1' => 0];
        foreach ($perClass as $m) {
            $macro['precision'] += $m['precision'];
            $macro['recall']    += $m['recall'];
            $macro['f1']        += $m['f1'];
        }
        $macro['precision'] /= $n;
        $macro['recall']    /= $n;
        $macro['f1']        /= $n;

        $microP = ($sumTP + $sumFP) ? $sumTP / ($sumTP + $sumFP) : 0.0;
        $microR = ($sumTP + $sumFN) ? $sumTP / ($sumTP + $sumFN) : 0.0;
        $microF = ($microP + $microR) ? (2 * $microP * $microR) / ($microP + $microR) : 0.0;
        $micro = ['precision' => $microP, 'recall' => $microR, 'f1' => $microF];

        $accuracy = $total ? $diag / $total : 0.0;

        return view('akurasi.index', compact(
            'produkOptions',
            'labels',
            'matrix',
            'support',
            'perClass',
            'macro',
            'micro',
            'accuracy',
            'total'
        ));
    }

    public function buildAktual(Request $req)
    {
        $data = $req->validate([
            'start'  => ['required', 'date'],
            'end'    => ['required', 'date', 'after_or_equal:start'],
            'produk' => ['nullable', 'integer'],
            'labels' => ['nullable', 'string'],
        ]);

        $start  = $data['start'];
        $end    = $data['end'];
        $produk = $data['produk'] ?? null;
        $labels = $data['labels'] ?? null;

        $q = DB::table('penjualans as pj')
            ->join('master_produks as mp', 'mp.id', '=', 'pj.produk_id')
            ->selectRaw('pj.produk_id, DATE(pj.created_at) as tanggal, SUM(pj.jumlah) as total_jumlah, SUM(pj.total_harga) as total_harga, mp.kategori_id')
            ->whereBetween(DB::raw('DATE(pj.created_at)'), [$start, $end])
            ->groupBy('pj.produk_id', DB::raw('DATE(pj.created_at)'), 'mp.kategori_id');

        if ($produk) {
            $q->where('pj.produk_id', $produk);
        }

        $aggs  = $q->get();
        $rules = DB::table('rules')->get();

        $getLabel = function ($kategoriId, $totalJumlah, $totalHarga) use ($rules) {
            foreach ($rules as $r) {
                if ((int)$r->kategori_produk === (int)$kategoriId) {
                    if ((int)$totalJumlah >= (int)$r->min_jumlah && (int)$totalHarga >= (int)$r->min_harga) {
                        return $r->kategori_prediksi; 
                    }
                }
            }
            return 'Rendah';
        };

        $ins = [];
        foreach ($aggs as $a) {
            $label = $getLabel($a->kategori_id, (int)$a->total_jumlah, (int)$a->total_harga);
            $ins[] = [
                'produk_id'          => $a->produk_id,
                'tanggal'            => $a->tanggal,
                'aktual_kategori'    => $label,
                'prediksi_kategori'  => null, 
                'created_at'         => now(),
                'updated_at'         => now(),
            ];
        }

        if (!empty($ins)) {
            DB::table('prediksis')->upsert(
                $ins,
                ['produk_id', 'tanggal'],
                ['aktual_kategori', 'updated_at'] 
            );
        }

        $updated = count($ins);

        $hasPrediksi = DB::table('prediksis')
            ->whereBetween('tanggal', [$start, $end])
            ->when($produk, fn($q2) => $q2->where('produk_id', $produk))
            ->whereNotNull('prediksi_kategori')
            ->exists();

        $msg = "Aktual diperbarui: {$updated} baris.";
        if (!$hasPrediksi) {
            $msg .= " Catatan: Belum ada prediksi pada periode ini. Buka halaman Prediksi untuk menghasilkan prediksi, lalu kembali ke sini.";
        }

        return redirect()
            ->route('akurasi.index', compact('start', 'end', 'produk', 'labels'))
            ->with('status', $msg);
    }
}
