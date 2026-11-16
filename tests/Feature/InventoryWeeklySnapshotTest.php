<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\DataLab;
use App\Models\InventarisMeja;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryWeeklySnapshotTest extends TestCase
{
    use RefreshDatabase;

    public function test_each_week_has_a_separate_snapshot_and_resaving_replaces_only_that_week(): void
    {
        $lab = DataLab::create([
            'nama_lab' => 'Lab Snapshot Mingguan',
            'lokasi' => 'Gedung Uji',
            'stok' => 24,
            'jumlah_kursi' => 1,
            'jumlah_meja' => 1,
            'status' => 'availabel',
        ]);

        InventarisMeja::create([
            'id_lab' => $lab->id_lab,
            'nomor_meja' => 1,
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
        ]);

        $admin = Admin::create([
            'nama' => 'Admin Snapshot',
            'email' => 'weekly-snapshot@example.test',
            'username' => 'weekly-snapshot',
            'password' => 'not-used-in-test',
        ]);
        $this->actingAs($admin, 'admin');

        foreach ([1, 2, 3, 4] as $week) {
            $this->withSession(['_token' => 'test-token']);
            $this->post(route('admin.inventaris.simpanPeriode', $lab->id_lab), [
                '_token' => 'test-token',
                'minggu' => $week,
                'bulan' => 11,
                'tahun' => 2026,
                'keterangan' => "Catatan minggu {$week}",
            ])->assertRedirect(route('admin.inventaris.riwayat', $lab->id_lab));
        }

        $this->assertDatabaseCount('periode_inventaris', 4);
        $weekTwo = \App\Models\PeriodeInventaris::where('id_lab', $lab->id_lab)
            ->where('tahun', 2026)->where('bulan', 11)->where('minggu', 2)->firstOrFail();

        $this->withSession(['_token' => 'test-token']);
        $this->post(route('admin.inventaris.simpanPeriode', $lab->id_lab), [
            '_token' => 'test-token',
            'minggu' => 2,
            'bulan' => 11,
            'tahun' => 2026,
            'keterangan' => 'Catatan minggu 2 diperbarui',
        ])->assertRedirect(route('admin.inventaris.riwayat', $lab->id_lab));

        $this->assertDatabaseCount('periode_inventaris', 4);
        $this->assertDatabaseHas('periode_inventaris', [
            'id_periode' => $weekTwo->id_periode,
            'minggu' => 2,
            'keterangan' => 'Catatan minggu 2 diperbarui',
        ]);
    }
}
