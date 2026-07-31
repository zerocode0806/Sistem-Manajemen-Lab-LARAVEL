<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\DataPinjam;
use App\Models\DataLab;
use App\Models\DataBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    public function create()
    {
        $labs = DataLab::all();
        $barang = DataBarang::all();
        return view('mahasiswa.peminjaman.create', compact('labs', 'barang'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis' => ['required', 'in:lab,barang'],
            'tanggal' => ['required', 'date'],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i'],
            'nama_lab' => ['nullable', 'string', 'max:100'],
            'id_barang' => ['nullable', 'exists:data_barang,id_barang'],
            'nama_barang' => ['nullable', 'string', 'max:100'],
            'jumlah' => ['nullable', 'integer', 'min:1'],
            'kursi' => ['nullable', 'integer', 'min:1'],
        ]);

        $validated['nim'] = Auth::guard('mahasiswa')->user()->nim;
        $validated['status'] = 'menunggu';

        DataPinjam::create($validated);

        return redirect()->route('mahasiswa.peminjaman.riwayat')->with('success', 'Peminjaman berhasil diajukan.');
    }

    public function riwayat()
    {
        $nim = Auth::guard('mahasiswa')->user()->nim;

        $peminjaman = DataPinjam::where('nim', $nim)
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('mahasiswa.peminjaman.riwayat', compact('peminjaman'));
    }

    public function show(DataPinjam $peminjaman)
    {
        $nim = Auth::guard('mahasiswa')->user()->nim;

        if ($peminjaman->nim !== $nim) {
            abort(403);
        }

        return view('mahasiswa.peminjaman.show', compact('peminjaman'));
    }

    public function arsip()
    {
        $nim = Auth::guard('mahasiswa')->user()->nim;

        $peminjaman = DataPinjam::where('nim', $nim)
            ->whereIn('status', ['selesai', 'ditolak'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('mahasiswa.peminjaman.arsip', compact('peminjaman'));
    }
}
