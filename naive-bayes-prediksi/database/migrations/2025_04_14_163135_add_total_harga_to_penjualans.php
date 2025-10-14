<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTotalHargaToPenjualans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('penjualans', function (Blueprint $table) {
            // Menambahkan kolom total_harga yang bertipe decimal
            $table->decimal('total_harga', 10, 2)->nullable();
        });
    }

    public function down()
    {
        Schema::table('penjualans', function (Blueprint $table) {
            // Menghapus kolom total_harga
            $table->dropColumn('total_harga');
        });
    }
}
