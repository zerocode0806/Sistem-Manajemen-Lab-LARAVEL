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
        Schema::create('inventaris_meja', function (Blueprint $table) {
            $table->increments('id_meja');
            $table->unsignedInteger('id_lab');
            $table->integer('nomor_meja');
            $table->enum('cpu_kondisi', ['normal', 'rusak', 'instal_ulang'])->default('normal');
            $table->enum('keyboard_kondisi', ['normal', 'rusak', 'tidak_ada'])->default('normal');
            $table->enum('mouse_kondisi', ['normal', 'rusak', 'tidak_ada'])->default('normal');
            $table->enum('monitor_kondisi', ['normal', 'rusak', 'tidak_ada'])->default('normal');
            $table->enum('kursi_kondisi', ['normal', 'rusak', 'tidak_ada'])->default('normal');
            $table->timestamps();

            $table->unique(['id_lab', 'nomor_meja']);
            $table->foreign('id_lab')->references('id_lab')->on('data_lab')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventaris_meja');
    }
};
