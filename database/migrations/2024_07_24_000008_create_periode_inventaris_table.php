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
        Schema::create('periode_inventaris', function (Blueprint $table) {
            $table->increments('id_periode');
            $table->unsignedInteger('id_lab');
            $table->tinyInteger('bulan');
            $table->smallInteger('tahun');
            $table->integer('jumlah_kursi')->default(0);
            $table->integer('jumlah_meja')->default(0);
            $table->integer('jumlah_ac')->default(0);
            $table->string('dicatat_oleh', 100)->nullable();
            $table->string('keterangan', 255)->nullable();
            $table->timestamps();

            $table->unique(['id_lab', 'bulan', 'tahun']);
            $table->foreign('id_lab')->references('id_lab')->on('data_lab')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periode_inventaris');
    }
};
