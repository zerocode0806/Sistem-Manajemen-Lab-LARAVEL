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
        $labs   = DataLab::where('status', 'availabel')->get();
        $barang = DataBarang::where('stok', '>', 0)->get();
        return view('mahasiswa.peminjaman.create', compact('labs', 'barang'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis'       => ['required', 'in:lab,barang'],
            'tanggal'     => ['required', 'date'],
            'jam_mulai'   => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i'],
            'nama_lab'    => ['nullable', 'string', 'max:100'],
            'id_barang'   => ['nullable', 'exists:data_barang,id_barang'],
            'nama_barang' => ['nullable', 'string', 'max:100'],
            'jumlah'      => ['nullable', 'integer', 'min:1'],
            'kursi'       => ['nullable', 'integer', 'min:1'],
        ]);

        // Validate kursi not already taken for lab booking
        if ($validated['jenis'] === 'lab' && !empty($validated['kursi'])) {
            $conflict = DataPinjam::where('nama_lab', $validated['nama_lab'])
                ->where('jenis', 'lab')
                ->where('tanggal', $validated['tanggal'])
                ->whereIn('status', ['menunggu', 'disetujui'])
                ->where('kursi', $validated['kursi'])
                ->where(function ($q) use ($validated) {
                    $q->where('jam_mulai', '<', $validated['jam_selesai'])
                      ->where('jam_selesai', '>', $validated['jam_mulai']);
                })
                ->exists();

            if ($conflict) {
                return back()->withInput()
                    ->withErrors(['kursi' => 'Meja ' . $validated['kursi'] . ' sudah dipesan untuk jadwal yang dipilih. Silakan pilih meja lain.']);
            }
        }

        $validated['nim']    = Auth::guard('mahasiswa')->user()->nim;
        $validated['status'] = 'menunggu';

        DataPinjam::create($validated);

        return redirect()->route('mahasiswa.peminjaman.riwayat')
                         ->with('success', 'Peminjaman berhasil diajukan.');
    }

    public function riwayat()
    {
        $nim        = Auth::guard('mahasiswa')->user()->nim;
        $peminjaman = DataPinjam::where('nim', $nim)->orderByDesc('id_data')->get();
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
        $nim        = Auth::guard('mahasiswa')->user()->nim;
        $peminjaman = DataPinjam::where('nim', $nim)
                         ->whereIn('status', ['selesai', 'ditolak'])
                         ->orderByDesc('id_data')
                         ->get();
        return view('mahasiswa.peminjaman.arsip', compact('peminjaman'));
    }

    /**
     * AJAX – return taken seats for a given lab / date / time window.
     * Used by the Seat Picker component.
     *
     * GET /mahasiswa/peminjaman/check-seats?nama_lab=X&tanggal=Y&jam_mulai=Z&jam_selesai=W
     */
    public function checkSeats(Request $request)
    {
        $request->validate([
            'nama_lab'    => 'required|string',
            'tanggal'     => 'required|date',
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required',
        ]);

        $lab = DataLab::where('nama_lab', $request->nama_lab)->first();

        if (!$lab) {
            return response()->json(['total_kursi' => 0, 'taken' => []]);
        }

        $taken = DataPinjam::where('nama_lab', $request->nama_lab)
            ->where('jenis', 'lab')
            ->where('tanggal', $request->tanggal)
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->whereNotNull('kursi')
            ->where(function ($q) use ($request) {
                $q->where('jam_mulai', '<', $request->jam_selesai)
                  ->where('jam_selesai', '>', $request->jam_mulai);
            })
            ->pluck('kursi')
            ->map(fn($v) => (int) $v)
            ->values()
            ->toArray();

        return response()->json([
            'total_kursi' => (int) $lab->jumlah_kursi,
            'taken'       => $taken,
        ]);
    }
}
