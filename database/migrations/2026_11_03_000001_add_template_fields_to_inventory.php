<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inventaris_meja', function (Blueprint $table) {
            foreach (['cpu_status', 'monitor_status', 'keyboard_status', 'mouse_status', 'windows_status'] as $column) {
                if (!Schema::hasColumn('inventaris_meja', $column)) {
                    $table->string($column, 30)->default('normal');
                }
            }
        });
        Schema::table('riwayat_meja', function (Blueprint $table) {
            foreach (['cpu_status', 'monitor_status', 'keyboard_status', 'mouse_status', 'windows_status'] as $column) {
                if (!Schema::hasColumn('riwayat_meja', $column)) {
                    $table->string($column, 30)->default('normal');
                }
            }
        });

        foreach (['inventaris_meja', 'riwayat_meja'] as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->update([
                    'cpu_status' => DB::raw("CASE WHEN cpu_kondisi = 'instal_ulang' THEN 'instal_ulang' WHEN cpu_kondisi = 'rusak' THEN 'trouble' WHEN cpu_kondisi = 'tidak_ada' THEN 'hilang' ELSE 'normal' END"),
                    'monitor_status' => DB::raw("CASE WHEN monitor_kondisi = 'rusak' THEN 'trouble' WHEN monitor_kondisi = 'tidak_ada' THEN 'hilang' ELSE 'normal' END"),
                    'keyboard_status' => DB::raw("CASE WHEN keyboard_kondisi = 'rusak' THEN 'trouble' WHEN keyboard_kondisi = 'tidak_ada' THEN 'hilang' ELSE 'normal' END"),
                    'mouse_status' => DB::raw("CASE WHEN mouse_kondisi = 'rusak' THEN 'trouble' WHEN mouse_kondisi = 'tidak_ada' THEN 'hilang' ELSE 'normal' END"),
                ]);
            }
        }

        Schema::create('inventaris_tambahan', function (Blueprint $table) {
            $table->increments('id_item');
            $table->unsignedInteger('id_lab');
            $table->string('nama_barang', 120);
            $table->string('kategori', 30)->default('peralatan');
            $table->unsignedInteger('jumlah_total')->default(1);
            $table->unsignedInteger('jumlah_rusak')->default(0);
            $table->string('kondisi', 30)->default('normal');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->foreign('id_lab')->references('id_lab')->on('data_lab')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('riwayat_tambahan', function (Blueprint $table) {
            $table->increments('id_riwayat_tambahan');
            $table->unsignedInteger('id_periode');
            $table->unsignedInteger('id_lab');
            $table->string('nama_barang', 120);
            $table->string('kategori', 30)->default('peralatan');
            $table->unsignedInteger('jumlah_total')->default(0);
            $table->unsignedInteger('jumlah_rusak')->default(0);
            $table->string('kondisi', 30)->default('normal');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->foreign('id_periode')->references('id_periode')->on('periode_inventaris')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_lab')->references('id_lab')->on('data_lab')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_tambahan');
        Schema::dropIfExists('inventaris_tambahan');
        foreach (['inventaris_meja', 'riwayat_meja'] as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    $columns = array_filter(['cpu_status', 'monitor_status', 'keyboard_status', 'mouse_status', 'windows_status'], fn ($column) => Schema::hasColumn($tableName, $column));
                    if ($columns) $table->dropColumn($columns);
                });
            }
        }
    }
};
