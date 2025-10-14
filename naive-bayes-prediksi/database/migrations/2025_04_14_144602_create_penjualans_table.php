<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenjualansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('penjualans', function (Blueprint $table) {
            // Menambahkan kolom produk_id sebagai foreign key
            $table->unsignedBigInteger('produk_id');

            // Membuat relasi dengan tabel master_produks
            $table->foreign('produk_id')->references('id')->on('master_produks')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('penjualans', function (Blueprint $table) {
            // Menghapus foreign key dan kolom produk_id
            $table->dropForeign(['produk_id']);
            $table->dropColumn('produk_id');
        });
    }
}
