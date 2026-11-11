<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\DataLab;
use App\Models\InventarisMeja;
use App\Services\LabReservationAvailability;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LabPcAvailabilityTest extends TestCase
{
    use RefreshDatabase;

    private function createLab(string $name = 'Lab Uji', int $pcCount = 1): DataLab
    {
        return DataLab::create([
            'nama_lab' => $name,
            'lokasi' => 'Gedung Uji',
            'stok' => 20,
            'jumlah_kursi' => $pcCount,
            'jumlah_meja' => $pcCount,
            'status' => 'availabel',
        ]);
    }

    private function createPc(DataLab $lab, int $number, array $overrides = []): InventarisMeja
    {
        return InventarisMeja::create(array_merge([
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
        ], $overrides));
    }

    private function admin(): Admin
    {
        return Admin::create([
            'nama' => 'Admin Test',
            'email' => 'lab-pc-test@example.test',
            'username' => 'lab-pc-test',
            'password' => 'not-used-in-test',
        ]);
    }

    public function test_editing_lab_updates_pc_inventory_and_booking_capacity(): void
    {
        $lab = $this->createLab('Lab Lama', 1);
        $this->createPc($lab, 1);
        $this->actingAs($this->admin(), 'admin');

        $this->withSession(['_token' => 'test-token']);
        $response = $this->put(route('admin.lab.update', $lab->id_lab), [
            '_token' => 'test-token',
            'nama_lab' => 'Lab Baru',
            'lokasi' => 'Gedung Baru',
            'stok' => 25,
            'jumlah_meja' => 3,
            'status' => 'availabel',
        ]);
        $response->assertRedirect(route('admin.lab.index'));

        $lab->refresh();
        $this->assertSame('Lab Baru', $lab->nama_lab);
        $this->assertSame(3, (int) $lab->jumlah_meja);
        $this->assertSame(3, (int) $lab->jumlah_kursi);
        $this->assertSame([1, 2, 3], InventarisMeja::where('id_lab', $lab->id_lab)->orderBy('nomor_meja')->pluck('nomor_meja')->map(fn ($n) => (int) $n)->all());
    }

    public function test_adding_pc_from_inventory_increases_booking_seat_count(): void
    {
        $lab = $this->createLab('Lab Inventaris', 1);
        $this->createPc($lab, 1);
        $this->actingAs($this->admin(), 'admin');

        $this->withSession(['_token' => 'test-token']);
        $this->post(route('admin.inventaris.pc.store', $lab->id_lab), ['_token' => 'test-token'])
            ->assertRedirect(route('admin.inventaris.index', $lab->id_lab));

        $lab->refresh();
        $this->assertSame(2, (int) $lab->jumlah_meja);
        $this->assertSame(2, (int) $lab->jumlah_kursi);
        $this->assertDatabaseHas('inventaris_meja', ['id_lab' => $lab->id_lab, 'nomor_meja' => 2]);
    }

    public function test_any_non_normal_pc_component_and_missing_pc_block_seat_booking(): void
    {
        $lab = $this->createLab('Lab Status', 3);
        $this->createPc($lab, 1);
        $this->createPc($lab, 2, ['keyboard_status' => 'trouble']);
        $this->createPc($lab, 3, ['windows_status' => 'instal_ulang']);

        $this->assertSame([2, 3], LabReservationAvailability::unavailableSeats($lab->nama_lab, 3));
        $this->assertTrue(LabReservationAvailability::isAvailable($lab->nama_lab, '2026-11-11', '09:00', '2026-11-11', '10:00', 1, false));
        $this->assertFalse(LabReservationAvailability::isAvailable($lab->nama_lab, '2026-11-11', '09:00', '2026-11-11', '10:00', 2, false));
        $this->assertFalse(LabReservationAvailability::isAvailable($lab->nama_lab, '2026-11-11', '09:00', '2026-11-11', '10:00', 3, false));
        $this->assertFalse(LabReservationAvailability::isAvailable($lab->nama_lab, '2026-11-11', '09:00', '2026-11-11', '10:00', 4, false));
    }
}
