<?php

namespace App\Http\Controllers;

use App\Models\MasterProduk;
use App\Models\Kategori;
use Illuminate\Http\Request;

class MasterProdukController extends Controller
{
    public function index(Request $request)
    {
        $query = MasterProduk::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;

            $query->where('nama_produk', 'like', "%{$search}%")
                ->orWhereHas('kategori', function ($query) use ($search) {
                    $query->where('nama_kategori', 'like', "%{$search}%");
                })
                ->orWhere('harga', 'like', "%{$search}%");
        }

        $masterProduk = $query->paginate(10);

        return view('masterproduk.index', compact('masterProduk'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        return view('masterproduk.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'kategori_id' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'stok' => 'required|integer',
        ]);

        MasterProduk::create($validated);
        return redirect()->route('master-produk.index')->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit($id)
    {
        $produk = MasterProduk::findOrFail($id);
        $kategoris = Kategori::all();
        return view('masterproduk.edit', compact('produk', 'kategoris'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'harga' => 'required|numeric',
            'stok' => 'required|integer',
        ]);

        $produk = MasterProduk::findOrFail($id);
        $produk->update($validated);
        return redirect()->route('master-produk.index')->with('success', 'Produk berhasil diperbarui');
    }

    public function destroy($id)
    {
        $produk = MasterProduk::findOrFail($id);
        $produk->delete();
        return redirect()->route('master-produk.index')->with('success', 'Produk berhasil dihapus');
    }
}
