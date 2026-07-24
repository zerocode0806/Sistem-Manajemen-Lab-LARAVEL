<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('data_barang', function (Blueprint $table) {
            $table->increments('id_barang');
            $table->unsignedInteger('id_lab');
            $table->string('kode_barang', 30)->unique();
            $table->string('nama_barang', 100);
            $table->string('kategori', 50)->nullable();
            $table->integer('stok')->default(0);
            $table->enum('kondisi', ['baik', 'rusak', 'perbaikan'])->default('baik');
            $table->enum('status', ['availabel', 'tidak availabel'])->default('availabel');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_lab')->references('id_lab')->on('data_lab')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_barang');
    }
};
