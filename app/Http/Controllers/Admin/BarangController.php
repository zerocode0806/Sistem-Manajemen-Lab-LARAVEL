<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataBarang;
use App\Models\DataLab;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
    {
        $barang = DataBarang::with('lab')->get();
        return view('admin.barang.index', compact('barang'));
    }

    public function create()
    {
        $labs = DataLab::all();
        return view('admin.barang.create', compact('labs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_lab' => ['required', 'exists:data_lab,id_lab'],
            'kode_barang' => ['required', 'string', 'max:30', 'unique:data_barang'],
            'nama_barang' => ['required', 'string', 'max:100'],
            'kategori' => ['nullable', 'string', 'max:50'],
            'stok' => ['required', 'integer', 'min:0'],
            'kondisi' => ['required', 'in:baik,rusak,perbaikan'],
            'status' => ['required', 'in:availabel,tidak availabel'],
            'keterangan' => ['nullable', 'string'],
        ]);

        DataBarang::create($validated);

        return redirect()->route('admin.barang.index')->with('success', 'Data barang berhasil ditambahkan.');
    }

    public function edit(DataBarang $barang)
    {
        $labs = DataLab::all();
        return view('admin.barang.edit', compact('barang', 'labs'));
    }

    public function update(Request $request, DataBarang $barang)
    {
        $validated = $request->validate([
            'id_lab' => ['required', 'exists:data_lab,id_lab'],
            'kode_barang' => ['required', 'string', 'max:30', 'unique:data_barang,kode_barang,' . $barang->id_barang . ',id_barang'],
            'nama_barang' => ['required', 'string', 'max:100'],
            'kategori' => ['nullable', 'string', 'max:50'],
            'stok' => ['required', 'integer', 'min:0'],
            'kondisi' => ['required', 'in:baik,rusak,perbaikan'],
            'status' => ['required', 'in:availabel,tidak availabel'],
            'keterangan' => ['nullable', 'string'],
        ]);

        $barang->update($validated);

        return redirect()->route('admin.barang.index')->with('success', 'Data barang berhasil diperbarui.');
    }

    public function destroy(DataBarang $barang)
    {
        $barang->delete();

        return redirect()->route('admin.barang.index')->with('success', 'Data barang berhasil dihapus.');
    }
}
