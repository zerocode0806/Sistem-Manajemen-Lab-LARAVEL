<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataLab;
use App\Models\DataPinjam;
use App\Models\InventarisMeja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LabController extends Controller
{
    public function index()
    {
        $labs = DataLab::all();
        return view('admin.lab.index', compact('labs'));
    }

    public function create()
    {
        return view('admin.lab.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lab' => ['required', 'string', 'max:100'],
            'lokasi' => ['nullable', 'string', 'max:100'],
            'stok' => ['required', 'integer', 'min:0'],
            'jumlah_meja' => ['required', 'integer', 'min:0', 'max:10000'],
            'status' => ['required', 'in:availabel,not available'],
        ]);

        DB::transaction(function () use ($validated) {
            $lab = DataLab::create(array_merge($validated, [
                'jumlah_kursi' => 0,
                'jumlah_meja' => 0,
            ]));
            $this->syncPcInventory($lab, (int) $validated['jumlah_meja']);
        });

        return redirect()->route('admin.lab.index')->with('success', 'Data lab dan inventaris PC berhasil ditambahkan.');
    }

    public function edit(DataLab $lab)
    {
        return view('admin.lab.edit', compact('lab'));
    }

    public function update(Request $request, DataLab $lab)
    {
        $validated = $request->validate([
            'nama_lab' => ['required', 'string', 'max:100'],
            'lokasi' => ['nullable', 'string', 'max:100'],
            'stok' => ['required', 'integer', 'min:0'],
            'jumlah_meja' => ['required', 'integer', 'min:0', 'max:10000'],
            'status' => ['required', 'in:availabel,not available'],
        ]);

        DB::transaction(function () use ($validated, $lab) {
            $oldName = $lab->nama_lab;
            $capacity = $this->syncPcInventory($lab, (int) $validated['jumlah_meja']);
            $lab->update(array_merge($validated, [
                'jumlah_meja' => $capacity['count'],
                'jumlah_kursi' => $capacity['max_number'],
            ]));

            // Active reservations store the lab name rather than its ID; keep them linked after a rename.
            if ($oldName !== $lab->nama_lab) {
                DataPinjam::where('nama_lab', $oldName)
                    ->where('jenis', 'lab')
                    ->whereIn('status', ['menunggu', 'disetujui'])
                    ->update(['nama_lab' => $lab->nama_lab]);
            }
        });

        return redirect()->route('admin.lab.index')->with('success', 'Data lab dan jumlah inventaris PC berhasil diperbarui.');
    }

    public function destroy(DataLab $lab)
    {
        $lab->delete();
        return redirect()->route('admin.lab.index')->with('success', 'Data lab berhasil dihapus.');
    }

    /**
     * Match the number of workstation inventory rows to the requested count.
     * When shrinking, refuse to remove a workstation that has an active booking.
     * Returns both row count and highest seat number (seat numbers are not renumbered,
     * so an existing reservation is never silently moved to a different PC).
     */
    private function syncPcInventory(DataLab $lab, int $targetCount): array
    {
        $rows = InventarisMeja::where('id_lab', $lab->id_lab)->orderByDesc('nomor_meja')->get();
        $currentCount = $rows->count();

        if ($targetCount < $currentCount) {
            $toRemove = $rows->take($currentCount - $targetCount);
            $seatNumbers = $toRemove->pluck('nomor_meja')->map(fn ($value) => (int) $value)->all();
            $reserved = DataPinjam::where('nama_lab', $lab->nama_lab)
                ->where('jenis', 'lab')
                ->whereIn('status', ['menunggu', 'disetujui'])
                ->whereIn('kursi', $seatNumbers)
                ->exists();

            if ($reserved) {
                throw ValidationException::withMessages([
                    'jumlah_meja' => 'Jumlah PC tidak dapat dikurangi karena ada unit yang masih dipakai pada peminjaman aktif.',
                ]);
            }

            InventarisMeja::whereIn('id_meja', $toRemove->pluck('id_meja'))->delete();
        } elseif ($targetCount > $currentCount) {
            $nextNumber = ((int) $rows->max('nomor_meja')) + 1;
            for ($i = 0; $i < $targetCount - $currentCount; $i++, $nextNumber++) {
                InventarisMeja::create([
                    'id_lab' => $lab->id_lab,
                    'nomor_meja' => $nextNumber,
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
            }
        }

        $finalRows = InventarisMeja::where('id_lab', $lab->id_lab)->get(['nomor_meja']);
        return [
            'count' => $finalRows->count(),
            'max_number' => (int) ($finalRows->max('nomor_meja') ?? 0),
        ];
    }
}
