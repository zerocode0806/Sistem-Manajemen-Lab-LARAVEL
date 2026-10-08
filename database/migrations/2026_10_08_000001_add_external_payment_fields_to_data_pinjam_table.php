<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('data_pinjam', function (Blueprint $table) {
            if (!Schema::hasColumn('data_pinjam', 'biaya_peminjaman')) {
                $table->unsignedBigInteger('biaya_peminjaman')->default(0);
            }

            if (!Schema::hasColumn('data_pinjam', 'status_pembayaran')) {
                $table->string('status_pembayaran', 20)->default('tidak_berlaku');
            }

            if (!Schema::hasColumn('data_pinjam', 'pembayaran_dikonfirmasi_pada')) {
                $table->dateTime('pembayaran_dikonfirmasi_pada')->nullable();
            }
        });
    }

    public function down(): void
    {
        $columns = array_values(array_filter(
            ['biaya_peminjaman', 'status_pembayaran', 'pembayaran_dikonfirmasi_pada'],
            fn (string $column) => Schema::hasColumn('data_pinjam', $column),
        ));

        if ($columns !== []) {
            Schema::table('data_pinjam', function (Blueprint $table) use ($columns) {
                $table->dropColumn($columns);
            });
        }
    }
};
