<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataLab;
use Illuminate\Http\Request;

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

        DataLab::create($validated);

        return redirect()->route('admin.lab.index')->with('success', 'Data lab berhasil ditambahkan.');
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
