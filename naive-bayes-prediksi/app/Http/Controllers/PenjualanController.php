<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\MasterProduk;
use Illuminate\Http\Request;

class PenjualanController extends Controller
{
    public function index(Request $request)
    {
       $penjualans = collect(); 

    if ($request->filled('bulan') && $request->filled('tahun')) {
        $penjualans = Penjualan::with('produk')
            ->whereMonth('created_at', $request->bulan)
            ->whereYear('created_at', $request->tahun)
            ->orderBy('created_at', 'asc')
            ->paginate(10)
            ->appends($request->all());
    }

    return view('penjualan.index', compact('penjualans'));
    }

    public function create()
    {
        $masterProduk = MasterProduk::all();

        return view('penjualan.create', compact('masterProduk'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:master_produks,id',
            'jumlah' => 'required|integer|min:1',
        ]);

        $produk = MasterProduk::find($request->produk_id);

        if ($produk->stok < $request->jumlah) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi!');
        }

        $penjualan = new Penjualan();
        $penjualan->produk_id = $request->produk_id;
        $penjualan->jumlah = $request->jumlah;
        $penjualan->total_harga = $produk->harga * $request->jumlah;
        $penjualan->save();

        $produk->stok -= $request->jumlah;
        $produk->save();

        return redirect()->route('penjualan.index')->with('success', 'Penjualan berhasil disimpan!');
    }

    public function edit($id)
    {
        $penjualan = Penjualan::findOrFail($id);

        $masterProduk = MasterProduk::all();

        return view('penjualan.edit', compact('penjualan', 'masterProduk'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'produk_id' => 'required|exists:master_produks,id',
            'jumlah' => 'required|integer|min:1',
        ]);

        $penjualan = Penjualan::findOrFail($id);
        $produkLama = $penjualan->produk;
        $produkBaru = MasterProduk::find($request->produk_id);

        $produkLama->stok += $penjualan->jumlah;
        $produkLama->save();

        $penjualan->produk_id = $request->produk_id;
        $penjualan->jumlah = $request->jumlah;
        $penjualan->total_harga = $produkBaru->harga * $request->jumlah;
        $penjualan->save();

        $produkBaru->stok -= $request->jumlah;
        $produkBaru->save();

        return redirect()->route('penjualan.index')->with('success', 'Penjualan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $penjualan = Penjualan::findOrFail($id);

        $produk = $penjualan->produk;

        $produk->stok += $penjualan->jumlah;
        $produk->save();

        $penjualan->delete();

        return redirect()->route('penjualan.index')->with('success', 'Penjualan berhasil dihapus!');
    }
}
