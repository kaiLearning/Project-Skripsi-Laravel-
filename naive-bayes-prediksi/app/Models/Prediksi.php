<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prediksi extends Model
{
    use HasFactory;

    protected $fillable = [];

    public function produk()
    {
        return $this->belongsTo(MasterProduk::class, 'produk_id');
    }
}
