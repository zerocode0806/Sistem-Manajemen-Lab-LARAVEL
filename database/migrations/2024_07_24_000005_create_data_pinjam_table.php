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
        Schema::create('data_pinjam', function (Blueprint $table) {
            $table->increments('id_data');
            $table->char('nim', 12);
            $table->enum('jenis', ['lab', 'barang'])->default('lab');
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('nama_lab', 100)->nullable();
            $table->unsignedInteger('id_barang')->nullable();
            $table->string('nama_barang', 100)->nullable();
            $table->integer('jumlah')->nullable();
            $table->integer('kursi')->nullable();
            $table->enum('status', ['disetujui', 'ditolak', 'menunggu', 'selesai']);
            $table->timestamps();

            $table->foreign('nim')->references('nim')->on('mahasiswa')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_barang')->references('id_barang')->on('data_barang')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_pinjam');
    }
};
