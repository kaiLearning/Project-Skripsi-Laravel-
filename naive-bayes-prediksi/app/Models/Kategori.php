<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $fillable = ['nama_kategori'];

    public function masterProduk()
    {
        return $this->hasMany(MasterProduk::class);
    }
    
    public function rules()
    {
        return $this->hasMany(Rule::class, 'kategori_produk', 'id');
    }
}
