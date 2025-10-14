<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    use HasFactory;
    protected $fillable = [
        'min_jumlah',
        'min_harga',
        'kategori_prediksi',
        'kategori_produk',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_produk', 'id');
    }
}

