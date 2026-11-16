<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const WEEKLY_INDEX = 'periode_inventaris_id_lab_bulan_tahun_minggu_unique';

    public function up(): void
    {
        Schema::table('periode_inventaris', function (Blueprint $table) {
            // Existing monthly snapshots are preserved and assigned to week 1.
            $table->unsignedTinyInteger('minggu')->default(1);
        });

        // Add the new index before dropping the old one: both indexes start with
        // the exact old key prefix, so MySQL retains any existing FK support.
        Schema::table('periode_inventaris', function (Blueprint $table) {
            $table->unique(['id_lab', 'bulan', 'tahun', 'minggu'], self::WEEKLY_INDEX);
        });

        Schema::table('periode_inventaris', function (Blueprint $table) {
            $table->dropUnique('periode_inventaris_id_lab_bulan_tahun_unique');
        });
    }

    public function down(): void
    {
        $hasMultipleWeeks = DB::table('periode_inventaris')
            ->select('id_lab', 'bulan', 'tahun')
            ->groupBy('id_lab', 'bulan', 'tahun')
            ->havingRaw('COUNT(*) > 1')
            ->exists();

        if ($hasMultipleWeeks) {
            throw new RuntimeException('Weekly inventory snapshots exist. Remove duplicate lab/month/year snapshots before rolling this migration back.');
        }

        Schema::table('periode_inventaris', function (Blueprint $table) {
            $table->unique(['id_lab', 'bulan', 'tahun']);
        });

        Schema::table('periode_inventaris', function (Blueprint $table) {
            $table->dropUnique(self::WEEKLY_INDEX);
        });

        Schema::table('periode_inventaris', function (Blueprint $table) {
            $table->dropColumn('minggu');
        });
    }
};
