<?php

namespace App\Http\Controllers;

use App\Models\Rule;
use App\Models\Kategori;
use Illuminate\Http\Request;

class RuleController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $rules = Rule::with('kategori')
            ->when($search, function($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('min_jumlah', 'LIKE', "%{$search}%")
                        ->orWhere('min_harga', 'LIKE', "%{$search}%")
                        ->orWhereHas('kategori', function($q) use ($search) {
                            $q->where('nama_kategori', 'LIKE', "%{$search}%");
                        });
                });
            })
            ->paginate(10); 

        return view('rule.index', compact('rules'));
    }


    public function create()
    {
        $kategoris = Kategori::all();
        return view('rule.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'min_jumlah' => 'required|numeric',
            'min_harga' => 'required|numeric',
            'kategori_prediksi' => 'required|in:Tinggi,Rendah',
        ]);

        Rule::create($request->all());

        return redirect()->route('rule.index')->with('success', 'Rule berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $rule = Rule::findOrFail($id);
        $kategoris = Kategori::all();
        return view('rule.edit', compact('rule', 'kategoris'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'min_jumlah' => 'required|numeric',
            'min_harga' => 'required|numeric',
            'kategori_prediksi' => 'required|in:Tinggi,Rendah',
            'kategori_produk' => 'required|exists:kategoris,id',
        ]);

        $rule = Rule::findOrFail($id);
        $rule->update($request->all());


        return redirect()->route('rule.index')->with('success', 'Rule berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $rule = Rule::findOrFail($id);
        $rule->delete();

        return redirect()->route('rule.index')->with('success', 'Rule berhasil dihapus.');
    }
}
