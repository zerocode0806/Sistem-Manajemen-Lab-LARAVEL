<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataLab;
use App\Models\InventarisMeja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            'jumlah_kursi' => ['required', 'integer', 'min:0'],
            'jumlah_meja' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'in:availabel,not available'],
        ]);

        DB::transaction(function () use ($validated) {

            // Simpan data lab
            $lab = DataLab::create($validated);

            // Buat inventaris meja otomatis
            $dataMeja = [];

            for ($i = 1; $i <= $lab->jumlah_meja; $i++) {
                $dataMeja[] = [
                    'id_lab'            => $lab->id_lab,
                    'nomor_meja'        => $i,
                    'cpu_kondisi'       => 'normal',
                    'keyboard_kondisi'  => 'normal',
                    'mouse_kondisi'     => 'normal',
                    'monitor_kondisi'   => 'normal',
                    'kursi_kondisi'     => 'normal',
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ];
            }

            InventarisMeja::insert($dataMeja);
        });

        return redirect()
            ->route('admin.lab.index')
            ->with('success', 'Data lab dan inventaris meja berhasil ditambahkan.');
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
            'jumlah_kursi' => ['required', 'integer', 'min:0'],
            'jumlah_meja' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'in:availabel,not available'],
        ]);

        $lab->update($validated);

        return redirect()->route('admin.lab.index')->with('success', 'Data lab berhasil diperbarui.');
    }

    public function destroy(DataLab $lab)
    {
        $lab->delete();

        return redirect()->route('admin.lab.index')->with('success', 'Data lab berhasil dihapus.');
    }
}
