<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKategorisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kategoris', function (Blueprint $table) {
            $table->string('nama_kategori'); // Menambahkan kolom 'nama_kategori'
        });
    }

    public function down()
    {
        Schema::table('kategoris', function (Blueprint $table) {
            $table->dropColumn('nama_kategori'); // Menghapus kolom 'nama_kategori' jika migrasi di rollback
        });
    }
}
