<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InventarisMeja;
use Illuminate\Http\Request;

class PcApiController extends Controller
{
    /**
     * Komponen => kolom di tabel inventaris_meja.
     * HARUS sama dengan LabReservationAvailability::conditionFields(),
     * karena itulah yang dipakai sistem peminjaman untuk memblokir kursi/PC.
     */
    private const KOMPONEN = [
        'cpu'      => ['cpu_kondisi', 'cpu_status'],
        'monitor'  => ['monitor_kondisi', 'monitor_status'],
        'keyboard' => ['keyboard_kondisi', 'keyboard_status'],
        'mouse'    => ['mouse_kondisi', 'mouse_status'],
        'windows'  => ['windows_status'],
        'kursi'    => ['kursi_kondisi'],
    ];

    /** GET /api/pc?status=rusak  (tanpa parameter = semua PC) */
    public function index(Request $request)
    {
        $query = InventarisMeja::with('lab')->orderBy('id_lab')->orderBy('nomor_meja');

        if ($request->query('status') === 'rusak') {
            $query->where(function ($q) {
                foreach (self::KOMPONEN as $kolom) {
                    foreach ($kolom as $k) {
                        $q->orWhere($k, '!=', 'normal');
                    }
                }
            });
        }

        return response()->json([
            'data' => $query->get()->map(fn ($pc) => $this->format($pc))->values(),
        ]);
    }

    /** GET /api/pc/{id_meja} */
    public function show(int $id_meja)
    {
        return response()->json(['data' => $this->format(InventarisMeja::with('lab')->findOrFail($id_meja))]);
    }

    /**
     * POST /api/pc/{id_meja}/selesai-maintenance
     * Body: {"komponen": ["cpu", "monitor"]}
     * Komponen yang disebut dikembalikan ke 'normal'. Komponen lain tidak disentuh,
     * jadi PC tetap tidak bisa dipinjam kalau masih ada yang bermasalah.
     */
    public function selesaiMaintenance(Request $request, int $id_meja)
    {
        $data = $request->validate([
            'komponen'   => 'required|array|min:1',
            'komponen.*' => 'in:' . implode(',', array_keys(self::KOMPONEN)),
        ]);

        $pc = InventarisMeja::with('lab')->findOrFail($id_meja);

        $update = ['updated_at' => now()];
        foreach (array_unique($data['komponen']) as $komponen) {
            foreach (self::KOMPONEN[$komponen] as $kolom) {
                $update[$kolom] = 'normal';
            }
        }

        // forceFill karena $timestamps = false di model, jadi updated_at diisi manual
        $pc->forceFill($update)->save();

        return response()->json(['data' => $this->format($pc->fresh('lab'))]);
    }

    private function format(InventarisMeja $pc): array
    {
        $masalah = [];
        foreach (self::KOMPONEN as $nama => $kolom) {
            foreach ($kolom as $k) {
                if ($pc->{$k} !== 'normal') {
                    $masalah[] = ['komponen' => $nama, 'kolom' => $k, 'nilai' => $pc->{$k}];
                }
            }
        }

        return [
            'id_meja'        => $pc->id_meja,
            'id_lab'         => $pc->id_lab,
            'nama_lab'       => $pc->lab->nama_lab ?? null,
            'lokasi'         => $pc->lab->lokasi ?? null,
            'nomor_meja'     => $pc->nomor_meja,
            'spesifikasi_pc' => $pc->spesifikasi_pc,
            'keterangan'     => $pc->keterangan,
            'masalah'        => $masalah,
            'bermasalah'     => count($masalah) > 0,
            'updated_at'     => optional($pc->updated_at)->toDateTimeString(),
        ];
    }
}