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
        Schema::create('riwayat_meja', function (Blueprint $table) {
            $table->increments('id_riwayat_meja');
            $table->unsignedInteger('id_periode');
            $table->unsignedInteger('id_lab');
            $table->integer('nomor_meja');
            $table->enum('cpu_kondisi', ['normal', 'rusak', 'instal_ulang'])->default('normal');
            $table->enum('keyboard_kondisi', ['normal', 'rusak', 'tidak_ada'])->default('normal');
            $table->enum('mouse_kondisi', ['normal', 'rusak', 'tidak_ada'])->default('normal');
            $table->enum('monitor_kondisi', ['normal', 'rusak', 'tidak_ada'])->default('normal');
            $table->enum('kursi_kondisi', ['normal', 'rusak', 'tidak_ada'])->default('normal');
            $table->timestamps();

            $table->foreign('id_periode')->references('id_periode')->on('periode_inventaris')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_meja');
    }
};
