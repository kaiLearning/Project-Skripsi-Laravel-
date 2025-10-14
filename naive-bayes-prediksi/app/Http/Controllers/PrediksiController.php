<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Penjualan;
use App\Models\Rule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrediksiController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->get('bulan', Carbon::now()->format('Y-m'));

        $penjualans = Penjualan::with('produk')
            ->whereYear('created_at', Carbon::parse($bulan)->year)
            ->whereMonth('created_at', Carbon::parse($bulan)->month)
            ->get();

        $penjualansGroupedByProduk = $penjualans->groupBy('produk_id')->map(function ($group) {
            return [
                'produk' => $group->first()->produk,
                'total_jumlah' => $group->sum('jumlah'),
                'tanggal' => $group->first()->created_at->toDateString(), 
            ];
        });

        $ins = [];

        $penjualansGroupedByProduk = $penjualansGroupedByProduk->map(function ($data) use (&$ins) {
            $produk = $data['produk'];
            $totalJumlah = $data['total_jumlah'];
            $tanggal = $data['tanggal'];

            $prediksi = $this->prediksiPenjualan($produk, $totalJumlah);
            $data['prediksi'] = $prediksi;

            $ins[] = [
                'produk_id'         => $produk->id,
                'tanggal'           => $tanggal,
                'prediksi_kategori' => $prediksi,
                'created_at'        => now(),
                'updated_at'        => now(),
            ];

            return $data;
        });

        if (!empty($ins)) {
            DB::table('prediksis')->upsert(
                $ins,
                ['produk_id','tanggal'],
                ['prediksi_kategori','updated_at']
            );
        }

        $perPage = 10;
        $currentPage = $request->get('page', 1);
        $currentData = $penjualansGroupedByProduk->slice(($currentPage - 1) * $perPage, $perPage);
        $paginatedData = new LengthAwarePaginator(
            $currentData,
            $penjualansGroupedByProduk->count(),
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );

        $bulanOptions = collect(range(0, 11))->mapWithKeys(function ($i) {
            $date = Carbon::now()->subMonths($i);
            return [$date->format('Y-m') => $date->translatedFormat('F Y')];
        });

        return view('prediksi.index', compact('penjualansGroupedByProduk', 'bulanOptions', 'bulan', 'paginatedData'));
    }

    public function prediksiPenjualan($produk, $totalJumlah)
    {
        $rules = Rule::all();

        foreach ($rules as $rule) {
            if ((int)$produk->kategori_id == (int)$rule->kategori_produk) {
                if ((int)$totalJumlah >= (int)$rule->min_jumlah && (int)($produk->harga*$totalJumlah) >= (int)$rule->min_harga) {
                    return $rule->kategori_prediksi;
                }
            }
        }

        return 'Sangat Rendah';
    }
}
