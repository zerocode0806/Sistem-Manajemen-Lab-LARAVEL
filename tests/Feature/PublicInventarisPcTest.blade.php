<?php

namespace Tests\Feature;

use App\Models\DataLab;
use App\Models\InventarisMeja;
use Database\Seeders\InventarisMejaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicInventarisPcTest extends TestCase
{
    use RefreshDatabase;

    private function createLab(): DataLab
    {
        return DataLab::create([
            'nama_lab' => 'Lab QR Uji',
            'lokasi' => 'Gedung Uji',
            'stok' => 20,
            'jumlah_kursi' => 2,
            'jumlah_meja' => 2,
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

    public function test_new_pcs_receive_distinct_opaque_public_tokens(): void
    {
        $lab = $this->createLab();
        $first = $this->createPc($lab, 1);
        $second = $this->createPc($lab, 2);

        $this->assertNotEmpty($first->qr_token);
        $this->assertNotEmpty($second->qr_token);
        $this->assertNotSame((string) $first->id_meja, $first->qr_token);
        $this->assertNotSame($first->qr_token, $second->qr_token);
        $this->assertMatchesRegularExpression('/^[0-9a-f-]{36}$/i', $first->qr_token);
    }

    public function test_public_page_displays_current_status_condition_and_saved_specifications_without_login(): void
    {
        $lab = $this->createLab();
        $pc = $this->createPc($lab, 4, [
            'cpu_status' => 'trouble',
            'keyboard_kondisi' => 'tidak_ada',
            'spesifikasi_pc' => "Device name\tUMSIDA2-GAME14\nProcessor\tIntel Core i7-14700 (2.10 GHz)\nInstalled RAM\t8 GB",
        ]);

        $this->get(route('public.inventaris.pc.show', ['token' => $pc->qr_token]))
            ->assertOk()
            ->assertSee('PC #4')
            ->assertSee('Lab QR Uji')
            ->assertSee('Perlu pemeriksaan')
            ->assertSee('Trouble')
            ->assertSee('Tidak ada')
            ->assertSee('UMSIDA2-GAME14')
            ->assertSee('Intel Core i7-14700 (2.10 GHz)');
    }

    public function test_qr_svg_is_served_inline_and_as_a_download(): void
    {
        $lab = $this->createLab();
        $pc = $this->createPc($lab, 2);

        $this->get(route('public.inventaris.pc.qr', ['token' => $pc->qr_token]))
            ->assertOk()
            ->assertHeader('content-type', 'image/svg+xml; charset=UTF-8')
            ->assertSee('<svg', false);

        $this->get(route('public.inventaris.pc.qr', ['token' => $pc->qr_token, 'download' => 1]))
            ->assertOk()
            ->assertHeader('content-disposition', 'attachment; filename="qr-pc-lab-'.$lab->id_lab.'-unit-2.svg"')
            ->assertSee('<svg', false);
    }

    public function test_database_id_does_not_act_as_a_public_pc_token(): void
    {
        $lab = $this->createLab();
        $pc = $this->createPc($lab, 1);

        $this->get(route('public.inventaris.pc.show', ['token' => (string) $pc->id_meja]))
            ->assertNotFound();
    }

    public function test_inventory_seeder_assigns_tokens_to_newly_created_pcs(): void
    {
        $lab = $this->createLab();
        $this->createPc($lab, 1);

        app(InventarisMejaSeeder::class)->run();

        $seededPc = InventarisMeja::where('id_lab', $lab->id_lab)->where('nomor_meja', 2)->firstOrFail();
        $this->assertNotEmpty($seededPc->qr_token);
        $this->assertMatchesRegularExpression('/^[0-9a-f-]{36}$/i', $seededPc->qr_token);
    }
}
