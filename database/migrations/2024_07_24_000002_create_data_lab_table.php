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
        Schema::create('data_lab', function (Blueprint $table) {
            $table->increments('id_lab');
            $table->string('nama_lab', 100);
            $table->string('lokasi', 100)->nullable();
            $table->integer('stok');
            $table->integer('jumlah_kursi')->default(40);
            $table->integer('jumlah_meja')->default(40);
            $table->enum('status', ['availabel', 'not available']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_lab');
    }
};
