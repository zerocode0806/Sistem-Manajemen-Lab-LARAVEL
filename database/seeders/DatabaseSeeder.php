<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Order matters: `data_lab` must be seeded before `inventaris_meja`,
     * since inventaris_meja.id_lab has a foreign key to data_lab.id_lab.
     * `admin` and `mahasiswa` have no dependencies and can run anytime.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            DataLabSeeder::class,
            InventarisMejaSeeder::class,
            MahasiswaSeeder::class,
        ]);
    }
}