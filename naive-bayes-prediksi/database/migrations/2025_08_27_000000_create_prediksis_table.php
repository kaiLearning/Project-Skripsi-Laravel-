<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('prediksis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('produk_id')->index();
            $table->date('tanggal')->index();
            $table->string('prediksi_kategori');
            $table->string('aktual_kategori')->nullable();
            $table->timestamps();
            $table->unique(['produk_id','tanggal']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('prediksis');
    }
};
