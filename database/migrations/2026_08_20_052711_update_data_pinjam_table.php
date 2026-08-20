<?php
// database/migrations/xxxx_add_eksternal_to_data_pinjam_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('data_pinjam', function (Blueprint $table) {

            // Ubah nim jadi nullable dulu (eksternal tidak punya nim)
            $table->char('nim', 12)->nullable()->change();

            // Tipe pemohon
            $table->enum('tipe_pemohon', ['internal', 'eksternal'])
                  ->default('internal')
                  ->after('id_data');

            // Data instansi eksternal
            $table->string('nama_instansi', 255)->nullable()->after('nim');
            $table->string('pic_instansi', 255)->nullable()->after('nama_instansi');
            $table->string('kontak_instansi', 50)->nullable()->after('pic_instansi');

            // Biaya eksternal
            $table->date('tanggal_selesai')->nullable()->after('tanggal');
            $table->unsignedInteger('durasi_hari')->nullable()->after('kursi');
            $table->unsignedInteger('biaya_per_hari')->default(75000)->after('durasi_hari');
            $table->unsignedBigInteger('total_biaya')->nullable()->after('biaya_per_hari');
            $table->enum('status_pembayaran', ['belum_bayar', 'lunas'])
                  ->nullable()
                  ->after('total_biaya');
        });
    }

    public function down(): void
    {
        Schema::table('data_pinjam', function (Blueprint $table) {
            $table->dropColumn([
                'tipe_pemohon',
                'nama_instansi',
                'pic_instansi',
                'kontak_instansi',
                'tanggal_selesai',
                'durasi_hari',
                'biaya_per_hari',
                'total_biaya',
                'status_pembayaran',
            ]);
            // Kembalikan nim ke non-nullable
            $table->char('nim', 12)->nullable(false)->change();
        });
    }
};