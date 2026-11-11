<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Ensure each legacy lab's declared PC count has matching inventory rows.
     * Existing workstation records and their condition/specification values are preserved.
     */
    public function up(): void
    {
        DB::transaction(function () {
            foreach (DB::table('data_lab')->orderBy('id_lab')->get() as $lab) {
                $numbers = DB::table('inventaris_meja')
                    ->where('id_lab', $lab->id_lab)
                    ->pluck('nomor_meja')
                    ->map(fn ($number) => (int) $number)
                    ->all();

                $target = max((int) $lab->jumlah_meja, count($numbers), $numbers ? max($numbers) : 0);
                for ($number = 1; $number <= $target; $number++) {
                    if (in_array($number, $numbers, true)) {
                        continue;
                    }

                    DB::table('inventaris_meja')->insert([
                        'id_lab' => $lab->id_lab,
                        'nomor_meja' => $number,
                        'cpu_kondisi' => 'normal',
                        'keyboard_kondisi' => 'normal',
                        'mouse_kondisi' => 'normal',
                        'monitor_kondisi' => 'normal',
                        'kursi_kondisi' => 'normal',
                        'cpu_status' => 'normal',
                        'monitor_status' => 'normal',
                        'keyboard_status' => 'normal',
                        'mouse_status' => 'normal',
                        'windows_status' => 'normal',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                $finalNumbers = DB::table('inventaris_meja')
                    ->where('id_lab', $lab->id_lab)
                    ->pluck('nomor_meja')
                    ->map(fn ($number) => (int) $number);

                DB::table('data_lab')->where('id_lab', $lab->id_lab)->update([
                    'jumlah_meja' => $finalNumbers->count(),
                    'jumlah_kursi' => (int) ($finalNumbers->max() ?? 0),
                ]);
            }
        });
    }

    /**
     * Do not delete backfilled workstation records on rollback; they may contain
     * inventory information entered after this migration was applied.
     */
    public function down(): void
    {
        // Intentionally non-destructive.
    }
};
