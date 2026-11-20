<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class InventarisMejaSeeder extends Seeder
{
    /**
     * Create missing workstation rows for each seeded lab. Existing inventory is
     * never overwritten, so re-running db:seed preserves equipment conditions.
     */
    public function run(): void
    {
        $hasQrToken = Schema::hasColumn('inventaris_meja', 'qr_token');

        foreach (DB::table('data_lab')->orderBy('id_lab')->get(['id_lab', 'jumlah_meja']) as $lab) {
            $numbers = DB::table('inventaris_meja')
                ->where('id_lab', $lab->id_lab)
                ->pluck('nomor_meja')
                ->map(fn ($number) => (int) $number)
                ->all();
            $target = max((int) $lab->jumlah_meja, count($numbers), $numbers ? max($numbers) : 0);
            $now = now();

            for ($number = 1; $number <= $target; $number++) {
                if (in_array($number, $numbers, true)) {
                    continue;
                }

                $pc = [
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
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                if ($hasQrToken) {
                    $pc['qr_token'] = (string) Str::uuid();
                }

                DB::table('inventaris_meja')->insertOrIgnore($pc);
            }
        }
    }
}
