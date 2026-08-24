<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('data_pinjam', function (Blueprint $table) {
            $table->string('alamat_instansi', 255)->nullable()->after('kontak_instansi');
            $table->text('keperluan')->nullable()->after('alamat_instansi');
            $table->string('no_surat', 100)->nullable()->after('keperluan');
        });
    }

    public function down(): void
    {
        Schema::table('data_pinjam', function (Blueprint $table) {
            $table->dropColumn(['alamat_instansi', 'keperluan', 'no_surat']);
        });
    }
};