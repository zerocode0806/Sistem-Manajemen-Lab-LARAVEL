<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('inventaris_meja', 'keterangan')) {
            Schema::table('inventaris_meja', function (Blueprint $table) {
                $table->text('keterangan')->nullable()->after('kursi_kondisi');
            });
        }

        if (!Schema::hasColumn('inventaris_meja', 'spesifikasi_pc')) {
            Schema::table('inventaris_meja', function (Blueprint $table) {
                $table->text('spesifikasi_pc')->nullable()->after('keterangan');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('inventaris_meja', 'spesifikasi_pc')) {
            Schema::table('inventaris_meja', function (Blueprint $table) {
                $table->dropColumn('spesifikasi_pc');
            });
        }

        if (Schema::hasColumn('inventaris_meja', 'keterangan')) {
            Schema::table('inventaris_meja', function (Blueprint $table) {
                $table->dropColumn('keterangan');
            });
        }
    }
};