<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add nullable snapshot fields for existing history records.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('riwayat_meja', 'keterangan')) {
            Schema::table('riwayat_meja', function (Blueprint $table) {
                $table->text('keterangan')->nullable()->after('kursi_kondisi');
            });
        }

        if (!Schema::hasColumn('riwayat_meja', 'spesifikasi_pc')) {
            Schema::table('riwayat_meja', function (Blueprint $table) {
                $table->text('spesifikasi_pc')->nullable()->after('keterangan');
            });
        }
    }

    /**
     * Remove only the columns introduced by this migration.
     */
    public function down(): void
    {
        if (Schema::hasColumn('riwayat_meja', 'spesifikasi_pc')) {
            Schema::table('riwayat_meja', function (Blueprint $table) {
                $table->dropColumn('spesifikasi_pc');
            });
        }

        if (Schema::hasColumn('riwayat_meja', 'keterangan')) {
            Schema::table('riwayat_meja', function (Blueprint $table) {
                $table->dropColumn('keterangan');
            });
        }
    }
};