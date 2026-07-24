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
        Schema::create('inventaris_ac', function (Blueprint $table) {
            $table->increments('id_ac');
            $table->unsignedInteger('id_lab');
            $table->integer('nomor_ac');
            $table->enum('kondisi', ['normal', 'rusak'])->default('normal');
            $table->timestamps();

            $table->unique(['id_lab', 'nomor_ac']);
            $table->foreign('id_lab')->references('id_lab')->on('data_lab')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventaris_ac');
    }
};
