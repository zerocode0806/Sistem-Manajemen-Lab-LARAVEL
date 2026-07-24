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
        Schema::create('riwayat_ac', function (Blueprint $table) {
            $table->increments('id_riwayat_ac');
            $table->unsignedInteger('id_periode');
            $table->unsignedInteger('id_lab');
            $table->integer('nomor_ac');
            $table->enum('kondisi', ['normal', 'rusak'])->default('normal');
            $table->timestamps();

            $table->foreign('id_periode')->references('id_periode')->on('periode_inventaris')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_ac');
    }
};
