<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataLabSeeder extends Seeder
{
    /**
     * Seed the `data_lab` table from the provided database export.
     * Uses upsert() keyed on the primary key so this seeder is safe to
     * run multiple times without creating duplicates or failing on
     * unique constraints.
     */
    public function run(): void
    {
        $rows = [
            ['id_lab' => 1, 'nama_lab' => 'Game dan Multimedia', 'lokasi' => 'Gendung Lab lantai 2', 'stok' => 40, 'jumlah_kursi' => 40, 'jumlah_meja' => 40, 'status' => 'availabel', 'created_at' => null, 'updated_at' => null],
            ['id_lab' => 2, 'nama_lab' => 'Sistem Komputer', 'lokasi' => 'Gendung Lab lantai 2', 'stok' => 40, 'jumlah_kursi' => 40, 'jumlah_meja' => 40, 'status' => 'availabel', 'created_at' => null, 'updated_at' => null],
            ['id_lab' => 3, 'nama_lab' => 'Ruang Baca (RBC)', 'lokasi' => 'Gendung Lab lantai 2', 'stok' => 40, 'jumlah_kursi' => 40, 'jumlah_meja' => 40, 'status' => 'availabel', 'created_at' => null, 'updated_at' => null],
            ['id_lab' => 4, 'nama_lab' => 'Komputasi', 'lokasi' => 'Gendung Lab lantai 3', 'stok' => 40, 'jumlah_kursi' => 40, 'jumlah_meja' => 40, 'status' => 'availabel', 'created_at' => null, 'updated_at' => null],
            ['id_lab' => 5, 'nama_lab' => 'Algoritma Pemrograman', 'lokasi' => 'Gendung Lab lantai 3', 'stok' => 40, 'jumlah_kursi' => 40, 'jumlah_meja' => 40, 'status' => 'availabel', 'created_at' => null, 'updated_at' => null],
            ['id_lab' => 6, 'nama_lab' => 'Sistem Cerdas', 'lokasi' => 'Gendung Lab lantai 3', 'stok' => 40, 'jumlah_kursi' => 40, 'jumlah_meja' => 40, 'status' => 'availabel', 'created_at' => null, 'updated_at' => null],
        ];

        foreach (array_chunk($rows, 250) as $chunk) {
            DB::table('data_lab')->upsert($chunk, ['id_lab'], ['nama_lab', 'lokasi', 'stok', 'jumlah_kursi', 'jumlah_meja', 'status', 'created_at', 'updated_at']);
        }
    }
}
