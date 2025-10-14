<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKategoriProdukToRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
    {
        if (!Schema::hasColumn('rules', 'kategori_produk')) {
            Schema::table('rules', function (Blueprint $table) {
                // sesuaikan tipe kalau perlu
                $table->unsignedBigInteger('kategori_produk')
                      ->after('kategori_prediksi');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('rules', 'kategori_produk')) {
            Schema::table('rules', function (Blueprint $table) {
                $table->dropColumn('kategori_produk');
            });
        }
    }
}
