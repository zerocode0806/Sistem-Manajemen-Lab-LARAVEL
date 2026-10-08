<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('data_pinjam', function (Blueprint $table) {
            if (!Schema::hasColumn('data_pinjam', 'kategori_peminjam')) {
                $table->string('kategori_peminjam', 32)->default('internal');
            }
            if (!Schema::hasColumn('data_pinjam', 'nama_peminjam')) {
                $table->string('nama_peminjam', 150)->nullable();
            }
            if (!Schema::hasColumn('data_pinjam', 'nim_eksternal')) {
                $table->string('nim_eksternal', 32)->nullable();
            }
            if (!Schema::hasColumn('data_pinjam', 'prodi_peminjam')) {
                $table->string('prodi_peminjam', 150)->nullable();
            }
            if (!Schema::hasColumn('data_pinjam', 'kontak_peminjam')) {
                $table->string('kontak_peminjam', 50)->nullable();
            }
            // Older external-booking migrations may already own this column.
            if (!Schema::hasColumn('data_pinjam', 'nama_instansi')) {
                $table->string('nama_instansi', 150)->nullable();
            }
            if (!Schema::hasColumn('data_pinjam', 'tujuan_peminjaman')) {
                $table->text('tujuan_peminjaman')->nullable();
            }
            if (!Schema::hasColumn('data_pinjam', 'seluruh_lab')) {
                $table->boolean('seluruh_lab')->default(false);
            }
            if (!Schema::hasColumn('data_pinjam', 'tanggal_selesai')) {
                $table->date('tanggal_selesai')->nullable();
            }
            if (!Schema::hasColumn('data_pinjam', 'tanggal_dikembalikan')) {
                $table->date('tanggal_dikembalikan')->nullable();
            }
            if (!Schema::hasColumn('data_pinjam', 'jam_dikembalikan')) {
                $table->time('jam_dikembalikan')->nullable();
            }
        });

        // External applicants do not necessarily exist in the mahasiswa table.
        Schema::table('data_pinjam', function (Blueprint $table) {
            $table->char('nim', 12)->nullable()->change();
        });
    }

    public function down(): void
    {
        $hasCategoryColumn = Schema::hasColumn('data_pinjam', 'kategori_peminjam');
        $hasExternalRecords = $hasCategoryColumn && DB::table('data_pinjam')
            ->where('kategori_peminjam', '!=', 'internal')
            ->orWhereNull('nim')
            ->exists();

        if ($hasExternalRecords) {
            throw new RuntimeException(
                'Cannot roll back external-loan fields while external or NIM-less bookings still exist.'
            );
        }

        Schema::table('data_pinjam', function (Blueprint $table) {
            $table->char('nim', 12)->nullable(false)->change();
        });

        // nama_instansi is retained because a preceding migration may own it.
        $columnsToDrop = [
            'kategori_peminjam',
            'nama_peminjam',
            'nim_eksternal',
            'prodi_peminjam',
            'kontak_peminjam',
            'tujuan_peminjaman',
            'seluruh_lab',
            'tanggal_selesai',
            'tanggal_dikembalikan',
            'jam_dikembalikan',
        ];
        $existingColumns = array_values(array_filter(
            $columnsToDrop,
            fn (string $column) => Schema::hasColumn('data_pinjam', $column),
        ));

        if ($existingColumns !== []) {
            Schema::table('data_pinjam', function (Blueprint $table) use ($existingColumns) {
                $table->dropColumn($existingColumns);
            });
        }
    }
};
