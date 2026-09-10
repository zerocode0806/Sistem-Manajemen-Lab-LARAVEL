<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add the date recorded field for existing installations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('periode_inventaris', 'tanggal_catat')) {
            Schema::table('periode_inventaris', function (Blueprint $table) {
                $table->dateTime('tanggal_catat')->nullable()->after('jumlah_ac');
            });
        }
    }

    /**
     * Remove only the column introduced by this migration.
     */
    public function down(): void
    {
        if (Schema::hasColumn('periode_inventaris', 'tanggal_catat')) {
            Schema::table('periode_inventaris', function (Blueprint $table) {
                $table->dropColumn('tanggal_catat');
            });
        }
    }
};