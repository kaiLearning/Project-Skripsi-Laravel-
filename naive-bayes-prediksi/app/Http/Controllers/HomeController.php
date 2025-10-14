<?php 

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\MasterProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->get('bulan', Carbon::now()->format('Y-m'));

        $penjualan = Penjualan::with('produk')
            ->selectRaw('produk_id, SUM(jumlah) as total')
            ->whereYear('created_at', Carbon::parse($bulan)->year)  
            ->whereMonth('created_at', Carbon::parse($bulan)->month)
            ->groupBy('produk_id')
            ->get();

        $labels = $penjualan->map(function ($item) {
            return $item->produk->nama_produk ?? 'Unknown';
        });

        $data = $penjualan->pluck('total');

        $bulanOptions = [
            Carbon::now()->subMonth(11)->format('Y-m') => Carbon::now()->subMonth(11)->format('F Y'),
            Carbon::now()->subMonth(10)->format('Y-m') => Carbon::now()->subMonth(10)->format('F Y'),
            Carbon::now()->subMonth(9)->format('Y-m') => Carbon::now()->subMonth(9)->format('F Y'),
            Carbon::now()->subMonth(8)->format('Y-m') => Carbon::now()->subMonth(8)->format('F Y'),
            Carbon::now()->subMonth(7)->format('Y-m') => Carbon::now()->subMonth(7)->format('F Y'),
            Carbon::now()->subMonth(6)->format('Y-m') => Carbon::now()->subMonth(6)->format('F Y'),
            Carbon::now()->subMonth(5)->format('Y-m') => Carbon::now()->subMonth(5)->format('F Y'),
            Carbon::now()->subMonth(4)->format('Y-m') => Carbon::now()->subMonth(4)->format('F Y'),
            Carbon::now()->subMonth(3)->format('Y-m') => Carbon::now()->subMonth(3)->format('F Y'),
            Carbon::now()->subMonth(2)->format('Y-m') => Carbon::now()->subMonth(2)->format('F Y'),
            Carbon::now()->subMonth(1)->format('Y-m') => Carbon::now()->subMonth(1)->format('F Y'),
            Carbon::now()->format('Y-m') => Carbon::now()->format('F Y')
        ];

        return view('home', [
            'labels' => $labels,
            'data' => $data,
            'bulanOptions' => $bulanOptions,
            'selectedBulan' => $bulan
        ]);
    }
}
